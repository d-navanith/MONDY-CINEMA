<header class="header">
    <nav class="nav">
        <a href="index.php" class="logo">
            <img src="/images/CinemaLogo.jpg" alt="Mondy Cinema" onerror="this.style.display='none'">
            MONDY CINEMA
        </a>
        
        <ul class="nav-menu" id="nav-menu">
            <li><a href="index.php" class="nav-link">Home</a></li>
            <li><a href="buy-tickets.php" class="nav-link">Buy Tickets</a></li>
            <li><a href="cinemas.php" class="nav-link">Cinemas</a></li>
            <li><a href="contact.php" class="nav-link">Contact Us</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php" class="nav-link">My Account</a></li>
                <li><a href="logout.php" class="nav-link">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="nav-link">Login</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="nav-toggle" id="nav-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </nav>
</header>