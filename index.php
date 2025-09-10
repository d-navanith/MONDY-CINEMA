<?php
session_start();
include 'config/database.php';
try {
    $stmt = $pdo->query("SELECT * FROM movies WHERE status = 'active' ORDER BY release_date DESC LIMIT 6");
    $movies = $stmt->fetchAll();
} catch (PDOException $e) {
    $movies = [];
}
if (empty($movies)) {
    $movies = [
        [
            'id' => 1,
            'title' => 'Coolie',
            'description' => 'A high-octane action thriller directed by Lokesh Kanagaraj, featuring Superstar Rajinikanth as a fearless gangster navigating loyalty, revenge, and survival in a dangerous underworld.',
            'genre' => 'Action, Thriller, Drama',
            'duration' => 145,
            'rating' => 8.4,
            'poster_image' => 'coolie.jpg',
            'trailer_url' => 'https://www.youtube.com/watch?v=TnhorwP9tcs',
            'release_date' => '2025-06-13'
        ],
        [
            'id' => 2,
            'title' => 'Clarence: Rhythm of the Guitar',
            'description' => 'A musical biopic capturing the inspiring journey of Sri Lankan guitar legend Clarence Wijewardena, chronicling his life, struggles, and timeless melodies that changed the nation’s music forever.',
            'genre' => 'Biography, Musical, Drama',
            'duration' => 152,
            'rating' => 8.0,
            'poster_image' => 'clarence.jpg',
            'trailer_url' => 'https://www.youtube.com/watch?v=F4U3LkmbISc', 
            'release_date' => '2025-05-02'
        ],
        [
            'id' => 3,
            'title' => 'Saiyaara',
            'description' => 'A romantic drama that follows two star-crossed lovers whose destinies collide amidst sacrifice, passion, and fate, taking audiences on an emotional rollercoaster.',
            'genre' => 'Romance, Drama, Musical',
            'duration' => 138,
            'rating' => 7.8,
            'poster_image' => 'saiyaara.jpg',
            'trailer_url' => 'https://www.youtube.com/watch?v=H1ufVumLQfQ',
            'release_date' => '2025-04-18'
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mondy Cinema - Premium Movie Experience</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <!-- Hero Carousel Section -->
    <section class="hero-carousel">
        <div class="carousel-container">
            <!-- Slide 1 -->
            <div class="carousel-slide active">
                <div class="hero-content">
                    <div class="hero-text animate-slide-in-left">
                        <h1>Experience Cinema Like Never Before</h1>
                        <p>Immerse yourself in stunning visuals, crystal-clear sound, and ultimate comfort at Mondy Cinema. Your premium entertainment destination.</p>
                        <div class="hero-buttons">
                            <a href="buy-tickets.php" class="btn btn-primary">
                                <i class="fas fa-ticket-alt"></i> Book Tickets Now
                            </a>
                            <a href="#now-showing" class="btn btn-outline">
                                <i class="fas fa-film"></i> Browse Movies
                            </a>
                        </div>
                    </div>
                    <div class="hero-image animate-slide-in-right">
                        <img src="assets/images/hero/hero-1.png" alt="Premium Cinema Experience" id="hero-1-img">
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-slide">
                <div class="hero-content">
                    <div class="hero-text animate-slide-in-left">
                        <h1>Latest Blockbusters</h1>
                        <p>Watch the hottest movies on the big screen with cutting-edge technology and premium sound systems.</p>
                        <div class="hero-buttons">
                            <a href="#now-showing" class="btn btn-primary">
                                <i class="fas fa-play"></i> Watch Now
                            </a>
                            <a href="#trailers" class="btn btn-outline">
                                <i class="fas fa-video"></i> View Trailers
                            </a>
                        </div>
                    </div>
                    <div class="hero-image animate-slide-in-right">
                        <img src="assets/images/hero/hero-2.png" alt="Latest Movies" id="hero-2-img">
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-slide">
                <div class="hero-content">
                    <div class="hero-text animate-slide-in-left">
                        <h1>Luxury & Comfort</h1>
                        <p>Enjoy reclining seats, gourmet snacks, and exceptional service in our premium theaters designed for your comfort.</p>
                        <div class="hero-buttons">
                            <a href="cinemas.php" class="btn btn-primary">
                                <i class="fas fa-map-marker-alt"></i> Our Locations
                            </a>
                            <a href="contact.php" class="btn btn-outline">
                                <i class="fas fa-phone"></i> Contact Us
                            </a>
                        </div>
                    </div>
                    <div class="hero-image animate-slide-in-right">
                        <img src="assets/images/hero/hero-3.png" alt="Luxury Cinema" id="hero-3-img">
                    </div>
                </div>
            </div>
        </div>
        <!-- Carousel Controls -->
        <div class="carousel-controls">
            <button class="carousel-btn" id="prevBtn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-btn" id="nextBtn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <div class="indicator active" data-slide="0"></div>
            <div class="indicator" data-slide="1"></div>
            <div class="indicator" data-slide="2"></div>
        </div>
    </section>
    <!-- Now Showing Movies Section -->
    <section class="movies-section" id="now-showing">
        <div class="container">
            <div class="section-header">
                <h2>Now Showing</h2>
                <p>Don't miss these amazing movies currently playing in our theaters</p>
            </div>
            
            <div class="movies-grid">
                <?php foreach (array_slice($movies, 0, 6) as $movie): ?>
                    <div class="movie-card">
                        <div class="movie-poster">
                            <img src="assets/images/movies/<?php echo htmlspecialchars($movie['poster_image']); ?>" 
                                alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            <div class="movie-overlay">
                                <a href="movie-details.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-info-circle"></i> More Info
                                </a>
                            </div>
                        </div>
                        <div class="movie-info">
                            <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <div class="movie-meta">
                                <span><i class="fas fa-star"></i> <?php echo $movie['rating']; ?></span>
                                <span><i class="fas fa-clock"></i> <?php echo $movie['duration']; ?> min</span>
                                <span class="genre"><?php echo htmlspecialchars($movie['genre']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Upcoming Movies Section -->
    <section class="movies-section upcoming-section" id="upcoming">
        <div class="container">
            <div class="section-header">
                <h2>Coming Soon</h2>
                <p>Get ready for these upcoming blockbusters</p>
            </div>
            
            <div class="movies-grid">
                <div class="movie-card upcoming-card">
                    <div class="movie-poster">
                        <img src="assets/images/movies/conjuring.jpg" alt="The Conjuring: Last Rites">
                        <div class="coming-soon-badge">Coming Soon</div>
                    </div>
                    <div class="movie-info">
                        <h3>The Conjuring: Last Rites</h3>
                        <div class="movie-meta">
                            <div class="release-date">
                                <i class="fas fa-calendar"></i> September 8, 2025
                            </div>
                        </div>
                        <p class="description">The latest installment in the terrifying saga of the Warrens.</p>
                    </div>
                </div>
                <div class="movie-card upcoming-card">
                    <div class="movie-poster">
                        <img src="assets/images/movies/zootopia.jpg" alt="Zootopia 2">
                        <div class="coming-soon-badge">Coming Soon</div>
                    </div>
                    <div class="movie-info">
                        <h3>Zootopia 2</h3>
                        <div class="movie-meta">
                            <div class="release-date">
                                <i class="fas fa-calendar"></i> November 26, 2025
                            </div>
                        </div>
                        <p class="description">Plot details are currently under wraps, but the sequel will continue the adventures of officer Judy Hopps and Nick Wilde.</p>
                    </div>
                </div>
                <div class="movie-card upcoming-card">
                    <div class="movie-poster">
                        <img src="assets/images/movies/tron.jpg" alt="TRON: Ares">
                        <div class="coming-soon-badge">Coming Soon</div>
                    </div>
                    <div class="movie-info">
                        <h3>TRON: Ares</h3>
                        <div class="movie-meta">
                            <div class="release-date">
                                <i class="fas fa-calendar"></i> October 10, 2025
                            </div>
                        </div>
                        <p class="description">A highly sophisticated program, Ares, is sent from the digital world into the real world on a dangerous mission.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trailers Section -->
    <section class="trailers-section" id="trailers">
        <div class="container">
            <div class="section-header">
                <h2>Latest Trailers</h2>
                <p>Watch the hottest movie trailers and get excited for what's coming</p>
            </div>
            
            <div class="trailers-grid">
                <div class="trailer-card" onclick="openTrailer('https://www.youtube.com/embed/pAsmrKyMqaA')">
                    <div class="trailer-thumbnail">
                        <img src="assets/images/trailers/fantastic4.png" alt="Fantastic Four Trailer">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="trailer-duration">2:25</div>
                    </div>
                    <div class="trailer-info">
                        <h3>Fantastic Four</h3>
                        <p>Official Trailer</p>
                    </div>
                </div>
                <div class="trailer-card" onclick="openTrailer('https://www.youtube.com/embed/pykffbNGCZE')">
                    <div class="trailer-thumbnail">
                        <img src="assets/images/trailers/kgf3.jpeg" alt="KGF 3 Trailer">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="trailer-duration">2:31</div>
                    </div>
                    <div class="trailer-info">
                        <h3>KGF 3</h3>
                        <p>Official Trailer</p>
                    </div>
                </div>
                <div class="trailer-card" onclick="openTrailer('https://www.youtube.com/embed/UWMzKXsY9A4')">
                    <div class="trailer-thumbnail">
                        <img src="assets/images/trailers/destination.jpeg" alt="Final Destination Trailer">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="trailer-duration">2:25</div>
                    </div>
                    <div class="trailer-info">
                        <h3>Final Destination: Bloodlines</h3>
                        <p>Official Trailer</p>
                    </div>
                </div>
                <div class="trailer-card" onclick="openTrailer('https://www.youtube.com/embed/GY4BgdUSpbE')">
                    <div class="trailer-thumbnail">
                        <img src="assets/images/trailers/rrr.jpg" alt="RRR Trailer">
                        <div class="play-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                        <div class="trailer-duration">3:16</div>
                    </div>
                    <div class="trailer-info">
                        <h3>RRR</h3>
                        <p>Official Trailer</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Special Offers Section -->
    <section class="offers-section" id="offers">
        <div class="container">
            <div class="section-header">
                <h2>Special Offers</h2>
                <p>Don't miss out on these amazing deals and discounts</p>
            </div>
            
            <div class="offers-grid">
                <div class="offer-card featured-offer">
                    <div class="offer-image">
                        <img src="assets/images/offers/offer-1.png" alt="Weekend Special">
                        <div class="offer-badge">50% OFF</div>
                    </div>
                    <div class="offer-content">
                        <h3>Weekend Special</h3>
                        <p>Enjoy 50% off on all movie tickets during weekends. Perfect for family outings and date nights!</p>
                        <div class="offer-validity">
                            <i class="fas fa-calendar"></i>
                            <span>Valid until December 31, 2024</span>
                        </div>
                        <a href="buy-tickets.php" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Book Now
                        </a>
                    </div>
                </div>
                <div class="offer-card">
                    <div class="offer-image">
                        <img src="assets/images/offers/offer-2.png" alt="Student Discount">
                        <div class="offer-badge">25% OFF</div>
                    </div>
                    <div class="offer-content">
                        <h3>Student Discount</h3>
                        <p>Students get 25% off on all tickets with valid student ID.</p>
                        <div class="offer-validity">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Valid Student ID Required</span>
                        </div>
                        <a href="buy-tickets.php" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="offer-card">
                    <div class="offer-image">
                        <img src="assets/images/offers/offer-3.png" alt="Family Pack">
                        <div class="offer-badge">BUY 3 GET 1</div>
                    </div>
                    <div class="offer-content">
                        <h3>Family Pack</h3>
                        <p>Buy 3 tickets and get 1 free! Perfect for family movie nights.</p>
                        <div class="offer-validity">
                            <i class="fas fa-users"></i>
                            <span>Minimum 4 tickets</span>
                        </div>
                        <a href="buy-tickets.php" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
                <div class="offer-card">
                    <div class="offer-image">
                        <img src="assets/images/offers/offer-4.jpg" alt="Premium Experience">
                        <div class="offer-badge">UPGRADE</div>
                    </div>
                    <div class="offer-content">
                        <h3>Premium Experience</h3>
                        <p>Free upgrade to premium seats with any IMAX ticket purchase.</p>
                        <div class="offer-validity">
                            <i class="fas fa-star"></i>
                            <span>IMAX movies only</span>
                        </div>
                        <a href="buy-tickets.php" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Trailer Modal -->
    <div id="trailer-modal" class="modal">
        <div class="modal-content trailer-modal-content">
            <span class="close">&times;</span>
            <iframe id="trailer-iframe" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <!-- JavaScript Files -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/carousel.js"></script>
    
    <script>
        // Set fallback images if images fail to load
        document.addEventListener('DOMContentLoaded', function() {
            const images = [
                'hero-1-img', 'hero-2-img', 'hero-3-img',
                'movie-1-img', 'movie-2-img', 'movie-3-img',
                'upcoming-1-img', 'upcoming-2-img', 'upcoming-3-img',
                'trailer-1-img', 'trailer-2-img', 'trailer-3-img', 'trailer-4-img',
                'offer-1-img', 'offer-2-img', 'offer-3-img', 'offer-4-img'
            ];
            
            images.forEach(function(imgId) {
                const img = document.getElementById(imgId);
                if (img) {
                    img.onerror = function() {
                        this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgZmlsbD0iIzMzMzMzMyIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTgiIGZpbGw9IiNmZmZmZmYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5JbWFnZSBQbGFjZWhvbGRlcjwvdGV4dD48L3N2Zz4=';
                        this.style.objectFit = 'cover';
                    };
                }
            });
        });
        // Trailer functionality
        function openTrailer(url) {
            const modal = document.getElementById('trailer-modal');
            const iframe = document.getElementById('trailer-iframe');
            iframe.src = url + '?autoplay=1';
            modal.style.display = 'block';
        }
        // Close modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('trailer-modal');
            const closeBtn = modal.querySelector('.close');
            const iframe = document.getElementById('trailer-iframe');
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                iframe.src = '';
            };
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    iframe.src = '';
                }
            };
        });
    </script>
</body>
</html>