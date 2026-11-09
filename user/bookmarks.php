<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
requireLogin();

$bookmarks = $pdo->prepare("SELECT a.*, c.name AS category_name 
                            FROM bookmarks b 
                            JOIN articles a ON b.article_id = a.article_id
                            LEFT JOIN categories c ON a.category_id = c.category_id
                            WHERE b.user_id = ? ORDER BY b.created_at DESC");
$bookmarks->execute([$_SESSION['user_id']]);
$bookmarks = $bookmarks->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookmarks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<section class="bookmarks-page">
    <div class="container">
        <h1 class="section-heading"><i class="fas fa-bookmark"></i> My Bookmarks</h1>
        
        <?php if(empty($bookmarks)): ?>
            <div class="empty-state" style="text-align:center;padding:60px;background:white;border-radius:15px;">
                <i class="far fa-bookmark" style="font-size:64px;color:#ccc;"></i>
                <p style="color:#999;margin-top:15px;">No bookmarks saved yet.</p>
            </div>
        <?php else: ?>
            <div class="news-grid">
                <?php foreach($bookmarks as $b): ?>
                    <article class="news-card">
                        <div class="card-img">
                            <img src="<?= $b['image_url'] ?: 'https://via.placeholder.com/400x220' ?>" alt="">
                        </div>
                        <div class="card-body">
                            <span class="category-tag"><?= $b['category_name'] ?></span>
                            <h3><a href="../article.php?id=<?= $b['article_id'] ?>"><?= htmlspecialchars($b['title']) ?></a></h3>
                            <p><?= truncate(strip_tags($b['content']), 120) ?></p>
                            <small><i class="far fa-clock"></i> <?= formatDate($b['created_at']) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>.bookmarks-page { padding: 50px 0; min-height: 70vh; background: #f5f8f5; }</style>
</body>
</html>