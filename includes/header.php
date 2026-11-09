<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header class="main-header">
    <div class="logo">
        <a href="index.php">
            <img src="assets/images/nsbm-logo.png" alt="NSBM" style="height: 50px;">
        </a>
    </div>
    
    <nav class="main-nav">
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="search.php"><i class="fas fa-search"></i> Explore & Search</a></li>
            <li><a href="submit_news.php"><i class="far fa-paper-plane"></i> Submit News</a></li>
        </ul>
    </nav>

    <div class="header-right">
        <a href="breaking_news.php" class="btn-breaking">
            <i class="far fa-newspaper"></i> BREAKING NEWS
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= $_SESSION['role']=='admin' ? 'admin/dashboard.php' : 'user/profile.php' ?>" class="login-link">
                <i class="fas fa-user-circle"></i> <?= $_SESSION['full_name'] ?>
            </a>
            <a href="logout.php" class="login-link"><i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login.php" class="login-link">
                <i class="fas fa-sign-in-alt"></i> Login / Register
            </a>
        <?php endif; ?>
    </div>
</header>