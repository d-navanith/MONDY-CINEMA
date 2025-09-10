<?php
session_start();
include 'config/database.php';

$movieId = $_GET['id'] ?? null;
if (!$movieId) {
    header('Location: index.php');
    exit();
}

// Fetch movie details
try {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->execute([$movieId]);
    $movie = $stmt->fetch();
    
    if (!$movie) {
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    header('Location: index.php');
    exit();
}

// Fetch showtimes
try {
    $stmt = $pdo->prepare("
        SELECT s.*, c.name as cinema_name, sc.screen_name
        FROM showtimes s
        JOIN screens sc ON s.screen_id = sc.id
        JOIN cinemas c ON sc.cinema_id = c.id
        WHERE s.movie_id = ? AND s.show_date >= CURDATE()
        ORDER BY s.show_date, s.show_time
        LIMIT 10
    ");
    $stmt->execute([$movieId]);
    $showtimes = $stmt->fetchAll();
} catch (PDOException $e) {
    $showtimes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> - Mondy Cinema</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pages.css">
    <link rel="stylesheet" href="assets/css/movie-details.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Movie Hero Section -->
    <section class="movie-hero">
        <div class="movie-backdrop">
            <div class="movie-poster">
                    <img src="assets/images/movies/<?php echo htmlspecialchars($movie['poster_image']); ?>" 
                        alt="<?php echo htmlspecialchars($movie['title']); ?>">
            </div>
                <div class="backdrop-overlay"></div>
        </div>

        <div class="container">
                <div class="movie-hero-content">
                <div class="movie-poster">
                    <img src="assets/images/movies/<?php echo htmlspecialchars($movie['poster_image']); ?>" 
                        alt="<?php echo htmlspecialchars($movie['title']); ?>">
                </div>
                
                <div class="movie-info">
                    <nav class="breadcrumb">
                        <a href="index.php">Home</a>
                        <span>/</span>
                        <a href="index.php#now-showing">Movies</a>
                        <span>/</span>
                        <span><?php echo htmlspecialchars($movie['title']); ?></span>
                    </nav>
                    
                    <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                    
                    <div class="movie-meta">
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span><?php echo $movie['rating']; ?>/10</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span><?php echo $movie['duration']; ?> min</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('Y', strtotime($movie['release_date'])); ?></span>
                        </div>
                        <div class="meta-item">
                            <span class="genre"><?php echo htmlspecialchars($movie['genre']); ?></span>
                        </div>
                    </div>
                    
                    <p class="movie-description"><?php echo htmlspecialchars($movie['description']); ?></p>
                    
                    <div class="movie-actions">
                        <a href="buy-tickets.php?movie=<?php echo $movie['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Book Tickets
                        </a>
                        <?php if ($movie['trailer_url']): ?>
                            <button class="btn btn-outline" onclick="openTrailer('<?php echo htmlspecialchars($movie['trailer_url']); ?>')">
                                <i class="fas fa-play"></i> Watch Trailer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Movie Details Section -->
    <section class="movie-details-section">
        <div class="container">
            <div class="details-content">
                <!-- Trailer Section -->
                <?php if ($movie['trailer_url']): ?>
                    <div class="details-card">
                        <h3><i class="fas fa-play-circle"></i> Official Trailer</h3>
                        <div class="trailer-container">
                            <div class="trailer-wrapper">
                                <iframe src="<?php echo str_replace('watch?v=', 'embed/', htmlspecialchars($movie['trailer_url'])); ?>" 
                                        frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- IMDB Rating -->
                <div class="details-card">
                    <h3><i class="fas fa-star"></i> Rating & Reviews</h3>
                    <div class="rating-container">
                        <div class="rating-score">
<?php
// $movie['rating'] is MPAA like "PG-13". Only use it as text.
// If you later add a numeric rating, change $ratingRaw to that numeric column.
$ratingRaw   = $movie['rating'] ?? null;
$ratingValue = is_numeric($ratingRaw) ? (float)$ratingRaw : 0.0;
?>
<div class="score">
    <?php echo is_numeric($ratingRaw) ? htmlspecialchars($ratingRaw) : 'N/A'; ?>
</div>
<div class="rating-stars">
<?php
$fullStars = (int) floor($ratingValue);
$halfStar  = ($ratingValue - $fullStars) >= 0.5;

for ($i = 1; $i <= 5; $i++) {
    if ($i <= $fullStars) {
        echo '<i class="fas fa-star"></i>';
    } elseif ($i == $fullStars + 1 && $halfStar) {
        echo '<i class="fas fa-star-half-alt"></i>';
    } else {
        echo '<i class="far fa-star"></i>';
    }
}
?>
</div>

                            <div class="rating-text">Based on audience reviews</div>
                        </div>
                    </div>
                </div>

                <!-- Cast Section -->
                <div class="details-card">
                    <h3><i class="fas fa-users"></i> Cast & Crew</h3>
                    <div class="cast-container">
                        <div class="cast-section">
                            <h4>Director</h4>
                            <p><?php echo htmlspecialchars($movie['director']); ?></p>
                        </div>
                        
                        <div class="cast-section">
                            <h4>Cast</h4>
                            <div class="cast-list">
                                <?php
                                $castMembers = explode(',', $movie['cast']);
                                foreach ($castMembers as $cast) {
                                    echo '<span class="cast-member">' . htmlspecialchars(trim($cast)) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Characters Section (Enhanced Cast Info) -->
                <div class="details-card">
                    <h3><i class="fas fa-theater-masks"></i> Characters</h3>
                    <div class="characters-container">
                        <?php
                        $castMembers = explode(',', $movie['cast']);
                        $sampleCharacters = ['Lead Role', 'Supporting Actor', 'Villain', 'Comic Relief', 'Love Interest'];
                        
                        foreach (array_slice($castMembers, 0, 5) as $index => $cast) {
                            $character = $sampleCharacters[$index] ?? 'Character';
                            echo '
                            <div class="character-item">
                                <div class="character-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="character-info">
                                    <h5>' . htmlspecialchars(trim($cast)) . '</h5>
                                    <p>as ' . $character . '</p>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Movie Synopsis -->
                <div class="details-card">
                    <h3><i class="fas fa-book-open"></i> Synopsis</h3>
                    <div class="synopsis-container">
                        <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                        
                        <div class="movie-specs">
                            <div class="spec-item">
                                <strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?>
                            </div>
                            <div class="spec-item">
                                <strong>Duration:</strong> <?php echo $movie['duration']; ?> minutes
                            </div>
                            <div class="spec-item">
                                <strong>Release Date:</strong> <?php echo date('F d, Y', strtotime($movie['release_date'])); ?>
                            </div>
                            <div class="spec-item">
                                <strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Showtimes Sidebar -->
            <div class="showtimes-sidebar">
                <div class="showtimes-card">
                    <h3><i class="fas fa-clock"></i> Showtimes</h3>
                    
                    <?php if (empty($showtimes)): ?>
                        <p>No showtimes available at the moment.</p>
                    <?php else: ?>
                        <div class="showtimes-list">
                            <?php
                            $currentDate = '';
                            foreach ($showtimes as $showtime):
                                $showDate = date('M d, Y', strtotime($showtime['show_date']));
                                if ($showDate !== $currentDate):
                                    if ($currentDate !== '') echo '</div>';
                                    echo '<div class="showtime-date">' . $showDate . '</div>';
                                    echo '<div class="showtime-slots">';
                                    $currentDate = $showDate;
                                endif;
                            ?>
                                <div class="showtime-slot">
                                    <div class="time"><?php echo date('g:i A', strtotime($showtime['show_time'])); ?></div>
                                    <div class="cinema"><?php echo htmlspecialchars($showtime['cinema_name']); ?></div>
                                    <div class="price">Rs. <?php echo number_format($showtime['price'], 2); ?></div>
                                    <a href="buy-tickets.php?movie=<?php echo $movie['id']; ?>&showtime=<?php echo $showtime['id']; ?>" class="btn btn-sm btn-primary">Book</a>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
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
    <script src="assets/js/movie-details.js"></script>
</body>
</html>