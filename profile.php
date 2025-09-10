<?php
session_start();
include 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$messageType = '';

// Fetch user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit();
    }
} catch (PDOException $e) {
    $message = 'Error loading user data.';
    $messageType = 'error';
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$name, $email, $phone, $address, $_SESSION['user_id']]);
            
            $message = 'Profile updated successfully!';
            $messageType = 'success';
            
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
            
        } catch (PDOException $e) {
            $message = 'Error updating profile.';
            $messageType = 'error';
        }
    }
    
    if ($_POST['action'] === 'change_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (password_verify($currentPassword, $user['password'])) {
            if ($newPassword === $confirmPassword && strlen($newPassword) >= 6) {
                try {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$hashedPassword, $_SESSION['user_id']]);
                    
                    $message = 'Password changed successfully!';
                    $messageType = 'success';
                    
                } catch (PDOException $e) {
                    $message = 'Error changing password.';
                    $messageType = 'error';
                }
            } else {
                $message = 'New passwords do not match or password is too short.';
                $messageType = 'error';
            }
        } else {
            $message = 'Current password is incorrect.';
            $messageType = 'error';
        }
    }
}

// Fetch user bookings
try {
    $stmt = $pdo->prepare("
        SELECT b.*, m.title as movie_title, c.name as cinema_name 
        FROM bookings b
        LEFT JOIN showtimes s ON b.showtime_id = s.id
        LEFT JOIN movies m ON s.movie_id = m.id
        LEFT JOIN screens sc ON s.screen_id = sc.id
        LEFT JOIN cinemas c ON sc.cinema_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookings = $stmt->fetchAll();
} catch (PDOException $e) {
    $bookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Mondy Cinema</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="page-header-content">
                <h1>My Account</h1>
                <p>Manage your profile and view your booking history</p>
                <nav class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span>/</span>
                    <span>My Account</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Account Section -->
    <section class="account-section">
        <div class="container">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="account-content">
                <!-- Account Sidebar -->
                <div class="account-sidebar">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    
                    <nav class="account-nav">
                        <a href="#" class="nav-item active" data-tab="profile">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="#" class="nav-item" data-tab="bookings">
                            <i class="fas fa-ticket-alt"></i> My Bookings
                        </a>
                        <a href="#" class="nav-item" data-tab="password">
                            <i class="fas fa-lock"></i> Change Password
                        </a>
                        <a href="logout.php" class="nav-item logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </nav>
                </div>

                <!-- Account Main Content -->
                <div class="account-main">
                    <!-- Profile Tab -->
                    <div class="tab-content active" id="profile-tab">
                        <div class="tab-header">
                            <h2>Profile Information</h2>
                            <p>Update your personal information</p>
                        </div>
                        
                        <form class="profile-form" method="POST" action="">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Bookings Tab -->
                    <div class="tab-content" id="bookings-tab">
                        <div class="tab-header">
                            <h2>My Bookings</h2>
                            <p>View your booking history and details</p>
                        </div>
                        
                        <div class="bookings-container">
                            <?php if (empty($bookings)): ?>
                                <div class="no-bookings">
                                    <i class="fas fa-ticket-alt"></i>
                                    <h3>No Bookings Yet</h3>
                                    <p>You haven't made any bookings yet. Start exploring our movies!</p>
                                    <a href="buy-tickets.php" class="btn btn-primary">Book Tickets</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <div class="booking-card">
                                        <div class="booking-header">
                                            <div class="booking-ref">
                                                <strong><?php echo htmlspecialchars($booking['booking_reference']); ?></strong>
                                            </div>
                                            <div class="booking-status status-<?php echo strtolower($booking['booking_status']); ?>">
                                                <?php echo ucfirst($booking['booking_status']); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-details">
                                            <div class="booking-info">
                                                <h4><?php echo htmlspecialchars($booking['movie_title'] ?? 'Movie'); ?></h4>
                                                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($booking['cinema_name'] ?? 'Cinema'); ?></p>
                                                <p><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($booking['created_at'])); ?></p>
                                                <p><i class="fas fa-users"></i> <?php echo $booking['total_seats']; ?> seat(s)</p>
                                            </div>
                                            
                                            <div class="booking-amount">
                                                <span class="amount">Rs. <?php echo number_format($booking['total_amount'], 2); ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-actions">
                                            <button class="btn btn-outline btn-sm" onclick="viewBookingDetails('<?php echo $booking['booking_reference']; ?>')">
                                                <i class="fas fa-eye"></i> View Details
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-content" id="password-tab">
                        <div class="tab-header">
                            <h2>Change Password</h2>
                            <p>Update your account password</p>
                        </div>
                        
                        <form class="password-form" method="POST" action="">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" required>
                                <small>Password must be at least 6 characters long</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/profile.js"></script>
</body>
</html>