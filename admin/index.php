<?php
session_start();
include '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics
$stats = [];

try {
    // Total movies
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM movies");
    $stats['movies'] = $stmt->fetch()['count'];

    // Total bookings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings");
    $stats['bookings'] = $stmt->fetch()['count'];

    // Total revenue
    $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM bookings WHERE payment_status = 'paid'");
    $stats['revenue'] = $stmt->fetch()['total'] ?? 0;

    // Today's bookings
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM bookings WHERE DATE(booking_date) = CURDATE()");
    $stats['today_bookings'] = $stmt->fetch()['count'];

    // Recent bookings with better error handling
    $stmt = $pdo->prepare("
        SELECT 
            b.id,
            b.booking_reference,
            b.customer_name,
            b.customer_email,
            b.total_seats,
            b.total_amount,
            b.booking_status,
            b.payment_status,
            b.booking_date,
            m.title as movie_title,
            COALESCE(s.show_date, CURDATE()) as show_date,
            COALESCE(s.show_time, '20:00:00') as show_time
        FROM bookings b 
        LEFT JOIN showtimes s ON b.showtime_id = s.id 
        LEFT JOIN movies m ON s.movie_id = m.id OR (s.movie_id IS NULL AND m.id = 1)
        ORDER BY b.booking_date DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $recent_bookings = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // Set default values if queries fail
    $stats = ['movies' => 0, 'bookings' => 0, 'revenue' => 0, 'today_bookings' => 0];
    $recent_bookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mondy Cinema</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="dashboard-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['movies']; ?></h3>
                        <p>Total Movies</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['bookings']; ?></h3>
                        <p>Total Bookings</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Rs. <?php echo number_format($stats['revenue'], 2); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['today_bookings']; ?></h3>
                        <p>Today's Bookings</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-content">
                <div class="recent-bookings">
                    <h2>Recent Bookings</h2>
                    <div class="table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer</th>
                                    <th>Movie</th>
                                    <th>Show Date</th>
                                    <th>Seats</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recent_bookings)): ?>
                                <tr>
                                    <td colspan="8" class="no-data">No bookings found</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['booking_reference']); ?></td>
                                        <td>
                                            <div class="customer-info">
                                                <strong><?php echo htmlspecialchars($booking['customer_name']); ?></strong>
                                                <small><?php echo htmlspecialchars($booking['customer_email']); ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['movie_title'] ?? 'Movie'); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($booking['show_date'])); ?></td>
                                        <td><?php echo $booking['total_seats']; ?></td>
                                        <td>Rs. <?php echo number_format($booking['total_amount'], 2); ?></td>
                                        <td>
                                            <?php if ($booking['payment_status'] === 'paid'): ?>
                                                <span class="payment-status paid">
                                                    <i class="fas fa-check-circle"></i> Payment Successful
                                                </span>
                                            <?php elseif ($booking['payment_status'] === 'pending'): ?>
                                                <span class="payment-status pending">
                                                    <i class="fas fa-clock"></i> Payment Pending
                                                </span>
                                            <?php else: ?>
                                                <span class="payment-status failed">
                                                    <i class="fas fa-times-circle"></i> Payment Failed
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status <?php echo $booking['booking_status']; ?>">
                                                <?php echo ucfirst($booking['booking_status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>