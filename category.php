<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) die("Category not found.");

// Pagination  
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Total articles
$total = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE category_id=? AND status='approved'");
$total->execute([$category['category_id']]);
$total = $total->fetchColumn();
$total_pages = ceil($total / $per_page);

// Fetch articles
$articles = $pdo->prepare("SELECT * FROM articles WHERE category_id=? AND status='approved' 
                           ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
$articles->execute([$category['category_id']]);
$articles = $articles->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $category['name'] ?> - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="category-page">
    <div class="container">
        <h1 class="section-heading"><?= $category['name'] ?></h1>
        <p class="cat-desc"><?= $category['description'] ?></p>

        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <i class="far fa-newspaper"></i>
                <p>No articles published in this category yet.</p>
            </div>
        <?php else: ?>
            <div class="news-grid">
                <?php foreach($articles as $a): ?>
                    <article class="news-card">
                        <div class="card-img">
                            <img src="<?= $a['image_url'] ?: 'https://via.placeholder.com/400x220' ?>" alt="">
                        </div>
                        <div class="card-body">
                            <span class="category-tag"><?= $category['name'] ?></span>
                            <h3><a href="article.php?id=<?= $a['article_id'] ?>"><?= htmlspecialchars($a['title']) ?></a></h3>
                            <p><?= truncate(strip_tags($a['content']), 120) ?></p>
                            <small><i class="far fa-clock"></i> <?= formatDate($a['created_at']) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
                        //Pagination 
            <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?slug=<?= $slug ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<style>
.category-page { padding: 50px 0; min-height: 70vh; background: #f5f8f5; }
.cat-desc { color: #666; margin-bottom: 30px; font-size: 16px; }
.empty-state { text-align: center; padding: 60px; background: white; border-radius: 15px; }