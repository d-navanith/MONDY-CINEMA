<?php
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_GET['showtime_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing showtime ID']);
    exit();
}

$showtimeId = $_GET['showtime_id'];

try {
    // Get occupied seats for this showtime
    $stmt = $pdo->prepare("
        SELECT seat_number 
        FROM seat_bookings 
        WHERE showtime_id = ?
    ");
    
    $stmt->execute([$showtimeId]);
    $occupiedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get showtime details
    $stmt = $pdo->prepare("
        SELECT s.*, sc.total_seats, sc.seat_layout
        FROM showtimes s
        JOIN screens sc ON s.screen_id = sc.id
        WHERE s.id = ?
    ");
    
    $stmt->execute([$showtimeId]);
    $showtime = $stmt->fetch();
    
    if (!$showtime) {
        http_response_code(404);
        echo json_encode(['error' => 'Showtime not found']);
        exit();
    }
    
    // Generate seat layout (8 rows, 10 seats per row for simplicity)
    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    $seatsPerRow = 10;
    $seats = [];
    
    foreach ($rows as $row) {
        for ($i = 1; $i <= $seatsPerRow; $i++) {
            $seatNumber = $row . $i;
            $seats[] = [
                'seat_number' => $seatNumber,
                'row' => $row,
                'column' => $i,
                'status' => in_array($seatNumber, $occupiedSeats) ? 'occupied' : 'available'
            ];
        }
    }
    
    echo json_encode([
        'seats' => $seats,
        'total_seats' => $showtime['total_seats'],
        'available_seats' => $showtime['available_seats']
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>