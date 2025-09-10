<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="/images/MondyCinema.jpg" alt="Mondy Cinema">
            <span>Admin Panel</span>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="movies.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'movies.php' ? 'active' : ''; ?>">
                    <i class="fas fa-film"></i>
                    <span>Movies</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="cinemas.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cinemas.php' ? 'active' : ''; ?>">
                    <i class="fas fa-building"></i>
                    <span>Cinemas</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="showtimes.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'showtimes.php' ? 'active' : ''; ?>">
                    <i class="fas fa-clock"></i>
                    <span>Showtimes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="bookings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Bookings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="customers.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="reports.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="../index.php" class="back-to-site">
            <i class="fas fa-arrow-left"></i>
            Back to Website
        </a>
    </div>
</aside>