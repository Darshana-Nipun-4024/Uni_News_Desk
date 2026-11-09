<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$breaking = $pdo->query("SELECT a.*, c.name AS category_name 
                         FROM articles a
                         LEFT JOIN categories c ON a.category_id = c.category_id
                         WHERE a.is_breaking = 1 AND a.status='approved'
                         ORDER BY a.created_at DESC")->fetchAll();
?>

<html>
<head>
    <title>Breaking News - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="breaking-page">
    <div class="container">
        <div class="breaking-header">
            <i class="fas fa-bolt"></i>
            <h1>Breaking News</h1>
            <span class="live-dot"></span> LIVE
        </div>

        <?php if (empty($breaking)): ?>
            <div class="empty-state">
                <i class="far fa-newspaper"></i>
                <p>No breaking news at the moment.</p>
            </div>
        <?php else: ?>
            <div class="breaking-list">
                <?php foreach($breaking as $b): ?>
                    <a href="article.php?id=<?= $b['article_id'] ?>" class="breaking-item">
                        <div class="breaking-img">
                            <img src="<?= $b['image_url'] ?: 'https://via.placeholder.com/300x200' ?>" alt="">
                            <span class="breaking-badge">BREAKING</span>
                        </div>
                        <div class="breaking-content">
                            <span class="category-tag"><?= $b['category_name'] ?></span>
                            <h3><?= htmlspecialchars($b['title']) ?></h3>
                            <p><?= truncate(strip_tags($b['content']), 180) ?></p>
                            <small><i class="far fa-clock"></i> <?= formatDate($b['created_at']) ?></small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<style>
.breaking-page { padding: 50px 0; min-height: 80vh; background: #fff5f5; }
.breaking-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid #e63946;
}
.breaking-header i { font-size: 40px; color: #e63946; }
.breaking-header h1 { font-size: 36px; color: #e63946; margin-right: 20px; }
.live-dot {
    width: 12px; height: 12px;
    background: #e63946;
    border-radius: 50%;
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0%,100% { opacity: 1; }
    50% { opacity: 0.3; }
}
.breaking-list { display: flex; flex-direction: column; gap: 20px; }
.breaking-item {
    display: grid;
    grid-template-columns: 300px 1fr;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: 0.3s;
}
.breaking-item:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.breaking-img { position: relative; height: 100%; min-height: 200px; }
.breaking-img img { width: 100%; height: 100%; object-fit: cover; }
.breaking-badge {
    position: absolute;
    top: 15px; left: 15px;
    background: #e63946;
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 700;
}
.breaking-content { padding: 25px; }
.breaking-content h3 { font-size: 22px; margin: 10px 0; color: #0d5c1c; }
.breaking-content p { color: #666; margin-bottom: 12px; }
@media(max-width:768px) {
    .breaking-item { grid-template-columns: 1fr; }
}
</style>
</body>
</html>