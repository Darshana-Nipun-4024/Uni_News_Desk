<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/auth_check.php';

$id = (int)($_GET['id'] ?? 0);

$pdo->prepare("UPDATE articles SET views = views + 1 WHERE article_id = ?")->execute([$id]);

$stmt = $pdo->prepare("SELECT a.*, c.name AS category_name, c.slug AS category_slug, u.full_name AS submitter
                       FROM articles a
                       LEFT JOIN categories c ON a.category_id = c.category_id
                       LEFT JOIN users u ON a.author_id = u.user_id
                       WHERE a.article_id = ? AND a.status = 'approved'");
$stmt->execute([$id]);
$article = $stmt->fetch();

if (!$article) {
    die("Article not found or not yet approved.");
}

$comments = $pdo->prepare("SELECT c.*, u.full_name, u.profile_pic 
                           FROM comments c 
                           JOIN users u ON c.user_id = u.user_id 
                           WHERE c.article_id = ? AND c.status='visible' 
                           ORDER BY c.created_at DESC");
$comments->execute([$id]);
$comments = $comments->fetchAll();

$isBookmarked = false;
if (isLoggedIn()) {
    $bm = $pdo->prepare("SELECT 1 FROM bookmarks WHERE user_id=? AND article_id=?");
    $bm->execute([$_SESSION['user_id'], $id]);
    $isBookmarked = (bool)$bm->fetchColumn();
}

$related = $pdo->prepare("SELECT * FROM articles WHERE category_id=? AND article_id!=? AND status='approved' LIMIT 3");
$related->execute([$article['category_id'], $id]);
$related = $related->fetchAll();
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?> - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/article.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<article class="article-page">
    <div class="container">
        
        <nav class="breadcrumb">
            <a href="index.php">Home</a> › 
            <a href="category.php?slug=<?= $article['category_slug'] ?>"><?= $article['category_name'] ?></a> › 
            <span><?= truncate($article['title'], 50) ?></span>
        </nav>

        <div class="article-hero">
            <span class="cat-badge"><?= $article['category_name'] ?></span>
            <h1><?= htmlspecialchars($article['title']) ?></h1>
            <div class="article-meta">
                <span><i class="far fa-user"></i> <?= htmlspecialchars($article['author_name']) ?></span>
                <span><i class="far fa-calendar"></i> <?= formatDate($article['created_at']) ?></span>
                <span><i class="far fa-eye"></i> <?= $article['views'] ?> views</span>
            </div>
        </div>

        <?php if ($article['image_url']): ?>
            <div class="article-banner">
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="">
            </div>
        <?php endif; ?>

        <div class="article-body">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>

        <div class="action-bar">
            <?php if (isLoggedIn()): ?>
                <button class="action-btn bookmark-btn <?= $isBookmarked ? 'active' : '' ?>" 
                        data-id="<?= $id ?>">
                    <i class="<?= $isBookmarked ? 'fas' : 'far' ?> fa-bookmark"></i> 
                    <span><?= $isBookmarked ? 'Bookmarked' : 'Bookmark' ?></span>
                </button>
            <?php endif; ?>
            <button class="action-btn" onclick="navigator.share && navigator.share({title:document.title, url:location.href})">
                <i class="fas fa-share"></i> Share
            </button>
        </div>

        <section class="comments-section">
            <h2><i class="far fa-comments"></i> Comments (<?= count($comments) ?>)</h2>
            
            <?php if (isLoggedIn()): ?>
                <form id="commentForm" class="comment-form">
                    <input type="hidden" name="article_id" value="<?= $id ?>">
                    <textarea name="comment" placeholder="Share your thoughts..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            <?php else: ?>
                <p class="login-prompt"><a href="login.php">Login</a> to post comments.</p>
            <?php endif; ?>

            <div class="comments-list" id="commentsList">
                <?php foreach($comments as $c): ?>
                    <div class="comment">
                        <div class="comment-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="comment-content">
                            <h4><?= htmlspecialchars($c['full_name']) ?> 
                                <small><?= formatDate($c['created_at']) ?></small>
                            </h4>
                            <p><?= nl2br(htmlspecialchars($c['comment_text'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if ($related): ?>
        <section class="related-articles">
            <h2>Related Articles</h2>
            <div class="news-grid">
                <?php foreach($related as $r): ?>
                    <article class="news-card">
                        <div class="card-img">
                            <img src="<?= $r['image_url'] ?: 'https://via.placeholder.com/400x220' ?>" alt="">
                        </div>
                        <div class="card-body">
                            <h3><a href="article.php?id=<?= $r['article_id'] ?>"><?= htmlspecialchars($r['title']) ?></a></h3>
                            <small><?= formatDate($r['created_at']) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </div>
</article>

<?php include 'includes/footer.php'; ?>

<script src="assets/js/article.js"></script>
</body>
</html>