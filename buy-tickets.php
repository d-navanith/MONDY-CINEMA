<?php
session_start();
include 'config/database.php';

// Get all active movies
$stmt = $pdo->query("SELECT * FROM movies WHERE status = 'active' ORDER BY title");
$movies = $stmt->fetchAll();

// Get all cinemas
$stmt = $pdo->query("SELECT * FROM cinemas ORDER BY name");
$cinemas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Tickets - Mondy Cinema</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="booking-container">
        <div class="container">
            <div class="booking-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-title">Select Movie</div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-title">Choose Cinema & Time</div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-title">Select Seats</div>
                </div>
                <div class="step" data-step="4">
                    <div class="step-number">4</div>
                    <div class="step-title">Payment</div>
                </div>
            </div>

            <!-- Step 1: Select Movie -->
            <div class="booking-step step-1 active">
                <h2>Select Movie</h2>
                <div class="movies-selection">
                    <?php foreach ($movies as $movie): ?>
                    <div class="movie-selection-card" data-movie-id="<?php echo $movie['id']; ?>">
                        <div class="movie-poster">
                            <img src="assets/images/movies/<?php echo $movie['poster_image']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        </div>
                        <div class="movie-details">
                            <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <div class="movie-info">
                                <span class="genre"><?php echo htmlspecialchars($movie['genre']); ?></span>
                                <span class="duration"><?php echo $movie['duration']; ?> min</span>
                                <span class="rating"><?php echo $movie['rating']; ?></span>
                            </div>
                            <p class="description"><?php echo htmlspecialchars(substr($movie['description'], 0, 150)); ?>...</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="btn btn-primary next-step" disabled>Next: Choose Cinema & Time</button>
            </div>

            <!-- Step 2: Choose Cinema & Time -->
            <div class="booking-step step-2">
                <h2>Choose Cinema & Time</h2>
                <div class="cinema-selection">
                    <div class="selected-movie-info">
                        <div class="movie-poster-small">
                            <img id="selected-movie-poster" src="" alt="">
                        </div>
                        <div class="movie-info-small">
                            <h4 id="selected-movie-title"></h4>
                            <p id="selected-movie-details"></p>
                        </div>
                    </div>

                    <div class="date-selection">
                        <h3>Select Date</h3>
                        <div class="date-picker">
                            <?php
                            for ($i = 0; $i < 7; $i++) {
                                $date = date('Y-m-d', strtotime("+$i days"));
                                $display_date = date('M j', strtotime($date));
                                $day = date('D', strtotime($date));
                                echo "<div class='date-option' data-date='$date'>";
                                echo "<div class='day'>$day</div>";
                                echo "<div class='date'>$display_date</div>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="cinema-times">
                        <h3>Select Cinema & Show Time</h3>
                        <div id="showtimes-container">
                            <!-- Showtimes will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="step-navigation">
                    <button class="btn btn-secondary prev-step">Previous</button>
                    <button class="btn btn-primary next-step" disabled>Next: Select Seats</button>
                </div>
            </div>

            <!-- Step 3: Select Seats -->
            <div class="booking-step step-3">
                <h2>Select Seats</h2>
                <div class="seat-selection">
                    <div class="booking-summary">
                        <div class="selected-details">
                            <h4 id="booking-movie-title"></h4>
                            <p id="booking-cinema-date"></p>
                            <p id="booking-showtime"></p>
                        </div>
                    </div>

                    <div class="cinema-layout">
                        <div class="screen">
                            <div class="screen-text">SCREEN</div>
                        </div>
                        
                        <div class="seats-container">
                            <div class="seats-grid" id="seats-grid">
                                <!-- Seats will be generated here -->
                            </div>
                        </div>

                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="seat-icon available"></div>
                                <span>Available</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-icon selected"></div>
                                <span>Selected</span>
                            </div>
                            <div class="legend-item">
                                <div class="seat-icon occupied"></div>
                                <span>Occupied</span>
                            </div>
                        </div>
                    </div>

                    <div class="booking-details">
                        <div class="selected-seats">
                            <h4>Selected Seats: <span id="selected-seats-list">None</span></h4>
                            <div class="price-breakdown">
                                <div class="price-item">
                                    <span>Tickets (<span id="ticket-count">0</span>): </span>
                                    <span>Rs. <span id="tickets-total">0</span></span>
                                </div>
                                <div class="price-item">
                                    <span>Service Fee: </span>
                                    <span>Rs. <span id="service-fee">0</span></span>
                                </div>
                                <div class="price-total">
                                    <span>Total: Rs. <span id="total-amount">0</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-navigation">
                    <button class="btn btn-secondary prev-step">Previous</button>
                    <button class="btn btn-primary next-step" disabled>Proceed to Payment</button>
                </div>
            </div>

            <!-- Step 4: Payment -->
            <div class="booking-step step-4">
                <h2>Payment Details</h2>
                <div class="payment-container">
                    <div class="booking-review">
                        <h3>Booking Summary</h3>
                        <div class="summary-details">
                            <div class="summary-item">
                                <span>Movie:</span>
                                <span id="final-movie-title"></span>
                            </div>
                            <div class="summary-item">
                                <span>Cinema:</span>
                                <span id="final-cinema"></span>
                            </div>
                            <div class="summary-item">
                                <span>Date & Time:</span>
                                <span id="final-datetime"></span>
                            </div>
                            <div class="summary-item">
                                <span>Seats:</span>
                                <span id="final-seats"></span>
                            </div>
                            <div class="summary-item">
                                <span>Total Amount:</span>
                                <span class="total-price">Rs. <span id="final-total">0</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="payment-form">
                        <h3>Payment Information</h3>
                        <form id="payment-form">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" id="customer-name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" id="customer-email" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" id="customer-phone" required>
                            </div>
                            
                            <h4>Payment Method</h4>
                            <div class="payment-methods">
                                <div class="payment-method">
                                    <input type="radio" id="card" name="payment-method" value="card" checked>
                                    <label for="card">Credit/Debit Card</label>
                                </div>
                                <div class="payment-method">
                                    <input type="radio" id="mobile" name="payment-method" value="mobile">
                                    <label for="mobile">Mobile Payment</label>
                                </div>
                            </div>

                            <div class="card-details">
                                <div class="form-group">
                                    <label>Card Number</label>
                                    <input type="text" id="card-number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="text" id="expiry-date" placeholder="MM/YY">
                                    </div>
                                    <div class="form-group">
                                        <label>CVV</label>
                                        <input type="text" id="cvv" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="step-navigation">
                    <button class="btn btn-secondary prev-step">Previous</button>
                    <button class="btn btn-success" id="complete-booking">Complete Booking</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/booking.js"></script>
</body>
</html>