<?php
header('Content-Type: application/json');
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

// Validate input
$required = ['showtime_id', 'customer_name', 'customer_email', 'customer_phone', 'selected_seats', 'total_amount'];
foreach ($required as $field) {
    if (!isset($input[$field]) || empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit();
    }
}

$showtimeId = $input['showtime_id'];
$customerName = $input['customer_name'];
$customerEmail = $input['customer_email'];
$customerPhone = $input['customer_phone'];
$selectedSeats = $input['selected_seats'];
$totalAmount = $input['total_amount'];
$userId = $_SESSION['user_id'] ?? null;

try {
    $pdo->beginTransaction();
    
    // Check if seats are still available
    $stmt = $pdo->prepare("
        SELECT seat_number 
        FROM seat_bookings 
        WHERE showtime_id = ? AND seat_number IN (" . str_repeat('?,', count($selectedSeats) - 1) . "?)
    ");
    $stmt->execute(array_merge([$showtimeId], $selectedSeats));
    $occupiedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!empty($occupiedSeats)) {
        throw new Exception('Some seats are no longer available: ' . implode(', ', $occupiedSeats));
    }
    
    // Generate booking reference
    $bookingRef = 'MBC' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    // Create booking
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            user_id, showtime_id, booking_reference, customer_name, 
            customer_email, customer_phone, seats_booked, total_seats, 
            total_amount, booking_status, payment_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'confirmed', 'paid')
    ");
    
    $stmt->execute([
        $userId,
        $showtimeId,
        $bookingRef,
        $customerName,
        $customerEmail,
        $customerPhone,
        json_encode($selectedSeats),
        count($selectedSeats),
        $totalAmount
    ]);
    
    $bookingId = $pdo->lastInsertId();
    
    // Create individual seat bookings
    $stmt = $pdo->prepare("
        INSERT INTO seat_bookings (booking_id, showtime_id, seat_number, seat_row, seat_column)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($selectedSeats as $seat) {
        $row = substr($seat, 0, 1);
        $column = (int)substr($seat, 1);
        $stmt->execute([$bookingId, $showtimeId, $seat, $row, $column]);
    }
    
    // Update available seats count
    $stmt = $pdo->prepare("
        UPDATE showtimes 
        SET available_seats = available_seats - ? 
        WHERE id = ?
    ");
    $stmt->execute([count($selectedSeats), $showtimeId]);
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'booking_reference' => $bookingRef,
        'booking_id' => $bookingId,
        'message' => 'Booking confirmed successfully!'
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>