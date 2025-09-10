<?php
header('Content-Type: application/json');
include '../config/database.php';

if (!isset($_GET['movie_id']) || !isset($_GET['date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit();
}

$movieId = $_GET['movie_id'];
$date = $_GET['date'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            s.id as showtime_id,
            s.show_time,
            s.price,
            s.available_seats,
            c.id as cinema_id,
            c.name as cinema_name,
            sc.screen_name
        FROM showtimes s
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE s.movie_id = ? AND s.show_date = ? AND s.status = 'active'
        ORDER BY c.name, s.show_time
    ");
    
    $stmt->execute([$movieId, $date]);
    $showtimes = $stmt->fetchAll();
    
    // Group by cinema
    $cinemas = [];
    foreach ($showtimes as $showtime) {
        $cinemaId = $showtime['cinema_id'];
        if (!isset($cinemas[$cinemaId])) {
            $cinemas[$cinemaId] = [
                'cinema_id' => $cinemaId,
                'cinema_name' => $showtime['cinema_name'],
                'showtimes' => []
            ];
        }
        
        $cinemas[$cinemaId]['showtimes'][] = [
            'showtime_id' => $showtime['showtime_id'],
            'time' => date('g:i A', strtotime($showtime['show_time'])),
            'price' => $showtime['price'],
            'available_seats' => $showtime['available_seats'],
            'screen_name' => $showtime['screen_name']
        ];
    }
    
    echo json_encode(array_values($cinemas));
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>