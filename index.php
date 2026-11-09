<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';


$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Fetch latest approved articles
$latest = $pdo->query("SELECT a.*, c.name AS category_name 
                       FROM articles a 
                       LEFT JOIN categories c ON a.category_id = c.category_id 
                       WHERE a.status='approved' 
                       ORDER BY a.created_at DESC LIMIT 6")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NSBM NewsDesk - Campus News Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-overlay"></div>
    
    <div class="hero-content">
        <div class="hero-card glass">
            <div class="hero-text">
                <h1 class="hero-title">NSBM<br>NEWSDESK</h1>
                <img src="assets/images/nsbm-shield.png" alt="NSBM" class="hero-shield" onerror="this.style.display='none'">
            </div>
            <h2 class="welcome-title">Welcome to NSBM NewsDesk!</h2>
            <p class="hero-tagline">Your Ultimate Hub for Campus News, Events & Announcements.</p>
            <p class="hero-desc">
                Stay connected with everything happening around NSBM Green University! From official academic 
                notices and upcoming club events to student stories and breaking campus news, NSBM NewsDesk 
                brings all updates right to your fingertips. Explore stories, stay informed, and engage with 
                the vibrant campus community today!
            </p>
        </div>

        <div class="search-float glass">
            <form action="search.php" method="GET">
                <input type="text" name="q" placeholder="Search...." required>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</section>

<!-- CATEGORY CARDS -->
<section class="category-section">
    <div class="container">
        <div class="category-grid">
            <?php 
            $images = [
                'sports' => 'assets/images/sports.jpeg',
                'academic' => 'assets/images/academic.jpg',
                'events' => 'assets/images/events.jpg',
                'general' => 'assets/images/general.jpg',
                'clubs' => 'assets/images/clubs.jpg'
            ];
            foreach($categories as $cat): 
                $slug = strtolower($cat['slug']);
                $img = $images[$slug] ?? 'https://via.placeholder.com/400';
            ?>
                <a href="category.php?slug=<?= $cat['slug'] ?>" class="category-card glass">
                    <h3><?= $cat['name'] ?></h3>
                    <div class="cat-img" style="background-image:url('<?= $img ?>')"></div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- LATEST NEWS -->
<section class="latest-news">
    <div class="container">
        <h2 class="section-heading">Latest News</h2>
        <div class="news-grid">
            <?php foreach($latest as $article): ?>
                <article class="news-card glass">
                    <div class="card-img">
                        <img src="<?= $article['image_url'] ?: 'https://via.placeholder.com/400x220' ?>" alt="">
                    </div>
                    <div class="card-body">
                        <span class="category-tag"><?= $article['category_name'] ?></span>
                        <h3><a href="article.php?id=<?= $article['article_id'] ?>"><?= sanitize($article['title']) ?></a></h3>
                        <p><?= truncate(strip_tags($article['content']), 120) ?></p>
                        <small><i class="far fa-clock"></i> <?= formatDate($article['created_at']) ?></small>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="assets/js/main.js"></script>
</body>
</html>
