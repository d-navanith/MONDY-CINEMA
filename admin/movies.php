<?php
session_start();
include '../config/database.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Handle movie operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $stmt = $pdo->prepare("INSERT INTO movies (title, description, genre, duration, rating, director, cast, release_date, poster_image, trailer_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['title'], $_POST['description'], $_POST['genre'], 
                    $_POST['duration'], $_POST['rating'], $_POST['director'], 
                    $_POST['cast'], $_POST['release_date'], $_POST['poster_image'], 
                    $_POST['trailer_url'], $_POST['status']
                ]);
                $success = "Movie added successfully!";
                break;
                
            case 'update':
                $stmt = $pdo->prepare("UPDATE movies SET title=?, description=?, genre=?, duration=?, rating=?, director=?, cast=?, release_date=?, poster_image=?, trailer_url=?, status=? WHERE id=?");
                $stmt->execute([
                    $_POST['title'], $_POST['description'], $_POST['genre'], 
                    $_POST['duration'], $_POST['rating'], $_POST['director'], 
                    $_POST['cast'], $_POST['release_date'], $_POST['poster_image'], 
                    $_POST['trailer_url'], $_POST['status'], $_POST['movie_id']
                ]);
                $success = "Movie updated successfully!";
                break;
                
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
                $stmt->execute([$_POST['movie_id']]);
                $success = "Movie deleted successfully!";
                break;
        }
    }
}

// Get all movies
$stmt = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC");
$movies = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - Mondy Cinema Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="admin-header">
                <h1>Manage Movies</h1>
                <button class="btn btn-primary" onclick="openModal('add')">
                    <i class="fas fa-plus"></i> Add New Movie
                </button>
            </div>

            <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Duration</th>
                            <th>Rating</th>
                            <th>Release Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td>
                                <img src="../assets/images/movies/<?php echo $movie['poster_image']; ?>" 
                                     alt="<?php echo htmlspecialchars($movie['title']); ?>" 
                                     class="poster-thumbnail">
                            </td>
                            <td><?php echo htmlspecialchars($movie['title']); ?></td>
                            <td><?php echo htmlspecialchars($movie['genre']); ?></td>
                            <td><?php echo $movie['duration']; ?> min</td>
                            <td><?php echo $movie['rating']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($movie['release_date'])); ?></td>
                            <td>
                                <span class="status <?php echo $movie['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $movie['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-secondary" onclick="editMovie(<?php echo htmlspecialchars(json_encode($movie)); ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteMovie(<?php echo $movie['id']; ?>, '<?php echo htmlspecialchars($movie['title']); ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Movie Modal -->
    <div id="movie-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Add Movie</h3>
                <span class="close">&times;</span>
            </div>
            <form id="movie-form" method="POST">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="movie_id" id="movie-id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="movie-title" required>
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" id="movie-genre" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="movie-description" rows="4" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Duration (minutes)</label>
                        <input type="number" name="duration" id="movie-duration" required>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <select name="rating" id="movie-rating" required>
                            <option value="G">G</option>
                            <option value="PG">PG</option>
                            <option value="PG-13">PG-13</option>
                            <option value="R">R</option>
                            <option value="NC-17">NC-17</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Director</label>
                        <input type="text" name="director" id="movie-director" required>
                    </div>
                    <div class="form-group">
                        <label>Release Date</label>
                        <input type="date" name="release_date" id="movie-release-date" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Cast</label>
                    <textarea name="cast" id="movie-cast" rows="2" placeholder="Comma-separated list of actors"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Poster Image</label>
                        <input type="text" name="poster_image" id="movie-poster" placeholder="image-name.jpg">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="movie-status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="coming_soon">Coming Soon</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Trailer URL (YouTube)</label>
                    <input type="url" name="trailer_url" id="movie-trailer" placeholder="https://www.youtube.com/watch?v=...">
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Movie</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/movies.js"></script>
</body>
</html>