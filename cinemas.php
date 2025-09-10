<?php
session_start();
include 'config/database.php';

// Fetch cinemas data
try {
    $stmt = $pdo->query("SELECT * FROM cinemas ORDER BY name");
    $cinemas = $stmt->fetchAll();
} catch (PDOException $e) {
    $cinemas = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Cinemas - Mondy Cinema</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages.css">
    
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
                <h1>Our Cinemas</h1>
                <p>Discover premium movie experiences across Sri Lanka</p>
                <nav class="breadcrumb">
                    <a href="index.php">Home</a>
                    <span>/</span>
                    <span>Cinemas</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Cinemas Section -->
    <section class="cinemas-section">
        <div class="container">
            <div class="cinemas-grid">
                <?php if (empty($cinemas)): ?>
                    <!-- Sample Cinemas -->
                    <div class="cinema-card">
                        <div class="cinema-image">
                            <img src="assets/images/cinemas/colombo.jpg" alt="Mondy Cinema Colombo" id="cinema-colombo-img">
                            <div class="cinema-badge">FLAGSHIP</div>
                        </div>
                        <div class="cinema-content">
                            <h3>Mondy Cinema Colombo</h3>
                            <div class="cinema-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Cinema Street, Colombo 03</span>
                            </div>
                            <div class="cinema-features">
                                <div class="feature">
                                    <i class="fas fa-couch"></i>
                                    <span>Premium Recliners</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-volume-up"></i>
                                    <span>Dolby Atmos</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-tv"></i>
                                    <span>IMAX Screen</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-parking"></i>
                                    <span>Free Parking</span>
                                </div>
                            </div>
                            <div class="cinema-details">
                                <div class="detail-item">
                                    <strong>Screens:</strong> 8 Premium Theaters
                                </div>
                                <div class="detail-item">
                                    <strong>Capacity:</strong> 1,200 seats
                                </div>
                                <div class="detail-item">
                                    <strong>Opening Hours:</strong> 10:00 AM - 11:00 PM
                                </div>
                                <div class="detail-item">
                                    <strong>Phone:</strong> +94 11 234 5678
                                </div>
                            </div>
                            <div class="cinema-actions">
                                <a href="buy-tickets.php?cinema=1" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt"></i> Book Tickets
                                </a>
                                <a href="#" class="btn btn-outline" onclick="showDirections('colombo')">
                                    <i class="fas fa-directions"></i> Get Directions
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="cinema-card">
                        <div class="cinema-image">
                            <img src="assets/images/cinemas/kandy.jpg" alt="Mondy Cinema Kandy" id="cinema-kandy-img">
                            <div class="cinema-badge">NEW</div>
                        </div>
                        <div class="cinema-content">
                            <h3>Mondy Cinema Kandy</h3>
                            <div class="cinema-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>456 Hill Street, Kandy</span>
                            </div>
                            <div class="cinema-features">
                                <div class="feature">
                                    <i class="fas fa-couch"></i>
                                    <span>Luxury Seating</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-volume-up"></i>
                                    <span>Surround Sound</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-snowflake"></i>
                                    <span>Climate Control</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-coffee"></i>
                                    <span>Café & Snacks</span>
                                </div>
                            </div>
                            <div class="cinema-details">
                                <div class="detail-item">
                                    <strong>Screens:</strong> 6 Modern Theaters
                                </div>
                                <div class="detail-item">
                                    <strong>Capacity:</strong> 900 seats
                                </div>
                                <div class="detail-item">
                                    <strong>Opening Hours:</strong> 10:00 AM - 10:30 PM
                                </div>
                                <div class="detail-item">
                                    <strong>Phone:</strong> +94 81 234 5678
                                </div>
                            </div>
                            <div class="cinema-actions">
                                <a href="buy-tickets.php?cinema=2" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt"></i> Book Tickets
                                </a>
                                <a href="#" class="btn btn-outline" onclick="showDirections('kandy')">
                                    <i class="fas fa-directions"></i> Get Directions
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="cinema-card">
                        <div class="cinema-image">
                            <img src="assets/images/cinemas/galle.jpg" alt="Mondy Cinema Galle" id="cinema-galle-img">
                        </div>
                        <div class="cinema-content">
                            <h3>Mondy Cinema Galle</h3>
                            <div class="cinema-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>789 Coast Road, Galle</span>
                            </div>
                            <div class="cinema-features">
                                <div class="feature">
                                    <i class="fas fa-couch"></i>
                                    <span>Comfort Seating</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-volume-up"></i>
                                    <span>Digital Sound</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-car"></i>
                                    <span>Valet Parking</span>
                                </div>
                                <div class="feature">
                                    <i class="fas fa-wheelchair"></i>
                                    <span>Accessible</span>
                                </div>
                            </div>
                            <div class="cinema-details">
                                <div class="detail-item">
                                    <strong>Screens:</strong> 4 Standard Theaters
                                </div>
                                <div class="detail-item">
                                    <strong>Capacity:</strong> 600 seats
                                </div>
                                <div class="detail-item">
                                    <strong>Opening Hours:</strong> 11:00 AM - 10:00 PM
                                </div>
                                <div class="detail-item">
                                    <strong>Phone:</strong> +94 91 234 5678
                                </div>
                            </div>
                            <div class="cinema-actions">
                                <a href="buy-tickets.php?cinema=3" class="btn btn-primary">
                                    <i class="fas fa-ticket-alt"></i> Book Tickets
                                </a>
                                <a href="#" class="btn btn-outline" onclick="showDirections('galle')">
                                    <i class="fas fa-directions"></i> Get Directions
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cinemas as $cinema): ?>
                        <div class="cinema-card">
                            <div class="cinema-image">
                                <img src="assets/images/cinemas/<?php echo htmlspecialchars($cinema['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($cinema['name']); ?>">
                            </div>
                            <div class="cinema-content">
                                <h3><?php echo htmlspecialchars($cinema['name']); ?></h3>
                                <div class="cinema-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo htmlspecialchars($cinema['address']); ?></span>
                                </div>
                                <div class="cinema-details">
                                    <div class="detail-item">
                                        <strong>Phone:</strong> <?php echo htmlspecialchars($cinema['phone']); ?>
                                    </div>
                                    <div class="detail-item">
                                        <strong>Email:</strong> <?php echo htmlspecialchars($cinema['email']); ?>
                                    </div>
                                </div>
                                <div class="cinema-actions">
                                    <a href="buy-tickets.php?cinema=<?php echo $cinema['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-ticket-alt"></i> Book Tickets
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Cinema Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header">
                <h2>Premium Cinema Experience</h2>
                <p>Discover what makes Mondy Cinema special</p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-couch"></i>
                    </div>
                    <h3>Luxury Recliners</h3>
                    <p>Enjoy ultimate comfort with our premium leather recliners featuring adjustable headrests and cup holders.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-volume-up"></i>
                    </div>
                    <h3>Dolby Atmos Sound</h3>
                    <p>Experience cinema audio like never before with our state-of-the-art Dolby Atmos surround sound system.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tv"></i>
                    </div>
                    <h3>4K Digital Projection</h3>
                    <p>Crystal clear visuals with our advanced 4K digital projection technology for the ultimate viewing experience.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-snowflake"></i>
                    </div>
                    <h3>Climate Control</h3>
                    <p>Perfect temperature maintained throughout your movie experience with our advanced climate control system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Modal -->
    <div id="map-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-header">
                <h3 id="map-title">Cinema Location</h3>
            </div>
            <div class="modal-body">
                <div id="map-container">
                    <p>Loading map...</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cinemas.js"></script>
</body>
</html>