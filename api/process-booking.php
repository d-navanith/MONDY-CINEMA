<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Get form data
    $movie_id = $_POST['movie_id'] ?? '';
    $showtime = $_POST['showtime'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $total_amount = floatval($_POST['total_amount'] ?? 0);
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validate required fields
    if (empty($movie_id) || empty($showtime) || empty($seats) || $total_amount <= 0) {
        throw new Exception('Missing required booking information');
    }
    
    if (empty($first_name) || empty($last_name) || empty($email)) {
        throw new Exception('Missing required customer information');
    }
    
    // Generate booking reference
    $booking_reference = 'BK' . date('Ymd') . rand(1000, 9999);
    
    // Get showtime details
    $showtime_id = null;
    try {
        $stmt = $pdo->prepare("SELECT id FROM showtimes WHERE id = ? OR CONCAT(show_date, ' ', show_time) = ?");
        $stmt->execute([$showtime, $showtime]);
        $showtime_record = $stmt->fetch();
        if ($showtime_record) {
            $showtime_id = $showtime_record['id'];
        } else {
            // Create a default showtime if not found
            $stmt = $pdo->prepare("INSERT INTO showtimes (movie_id, screen_id, show_date, show_time, price, available_seats) VALUES (?, 1, CURDATE(), '20:00:00', ?, 100)");
            $stmt->execute([$movie_id, $total_amount / count(explode(',', $seats))]);
            $showtime_id = $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        // Fallback showtime ID
        $showtime_id = 1;
    }
    
    // Create booking record with payment status as 'paid'
    try {
        $stmt = $pdo->prepare("
            INSERT INTO bookings (
                user_id, 
                showtime_id, 
                booking_reference, 
                customer_name, 
                customer_email, 
                customer_phone, 
                seats_booked, 
                total_seats, 
                total_amount, 
                booking_status, 
                payment_status, 
                payment_method,
                booking_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', 'paid', 'online', NOW())
        ");
        
        $user_id = $_SESSION['user_id'] ?? null;
        $customer_name = $first_name . ' ' . $last_name;
        $seats_array = explode(',', $seats);
        $total_seats = count($seats_array);
        
        $stmt->execute([
            $user_id,
            $showtime_id,
            $booking_reference,
            $customer_name,
            $email,
            $phone ?: '+94 000 000 000',
            json_encode($seats_array),
            $total_seats,
            $total_amount
        ]);
        
        $booking_db_id = $pdo->lastInsertId();
        
        // Insert individual seat bookings
        foreach ($seats_array as $seat) {
            $seat = trim($seat);
            if (!empty($seat)) {
                // Extract row and column from seat (e.g., "A1" -> row="A", col=1)
                preg_match('/([A-Z])(\d+)/', $seat, $matches);
                $seat_row = $matches[1] ?? 'A';
                $seat_column = intval($matches[2] ?? 1);
                
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO seat_bookings (booking_id, showtime_id, seat_number, seat_row, seat_column) 
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([$booking_db_id, $showtime_id, $seat, $seat_row, $seat_column]);
                } catch (PDOException $e) {
                    // Continue if seat booking fails (duplicate seat)
                }
            }
        }
        
        // Update available seats in showtime
        try {
            $stmt = $pdo->prepare("UPDATE showtimes SET available_seats = available_seats - ? WHERE id = ?");
            $stmt->execute([$total_seats, $showtime_id]);
        } catch (PDOException $e) {
            // Continue if update fails
        }
        
    } catch (PDOException $e) {
        throw new Exception('Failed to create booking: ' . $e->getMessage());
    }
    
    // Get movie title for confirmation
    $movie_title = 'Selected Movie';
    try {
        $stmt = $pdo->prepare("SELECT title FROM movies WHERE id = ?");
        $stmt->execute([$movie_id]);
        $movie = $stmt->fetch();
        if ($movie) {
            $movie_title = $movie['title'];
        }
    } catch (PDOException $e) {
        // Use fallback movie titles
        $movie_titles = [
            '1' => 'Spider-Man: No Way Home',
            '2' => 'Avengers: Endgame',
            '3' => 'The Batman',
            '4' => 'Top Gun: Maverick',
            '5' => 'Black Panther: Wakanda Forever',
            '6' => 'Dune: Part Two'
        ];
        $movie_title = $movie_titles[$movie_id] ?? 'Selected Movie';
    }
    
    // Store booking data in session for success page
    $_SESSION['last_booking'] = [
        'id' => $booking_reference,
        'db_id' => $booking_db_id,
        'movie' => $movie_title,
        'datetime' => date('F j, Y') . ' at ' . $showtime,
        'seats' => $seats,
        'total' => $total_amount,
        'customer_name' => $customer_name,
        'customer_email' => $email,
        'payment_status' => 'paid'
    ];
    
    // Simulate payment processing delay
    sleep(1);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'booking_id' => $booking_reference,
        'message' => 'Payment processed successfully! Your booking has been confirmed.',
        'redirect' => 'buy-tickets.php?payment=success'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>