<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$q = trim($_GET['q'] ?? '');
$category = $_GET['category'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();


$sql = "SELECT a.*, c.name AS category_name FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.category_id
        WHERE a.status = 'approved'";
$params = [];

if ($q) {
    $sql .= " AND (a.title LIKE ? OR a.content LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}
if ($category) {
    $sql .= " AND c.slug = ?";
    $params[] = $category;
}
if ($date_from) {
    $sql .= " AND DATE(a.created_at) >= ?";
    $params[] = $date_from;
}
if ($date_to) {
    $sql .= " AND DATE(a.created_at) <= ?";
    $params[] = $date_to;
}

$sql .= " ORDER BY a.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="search-page">
    <div class="container">
        <h1 class="section-heading">Explore & Search</h1>

        <form method="GET" class="search-filters">
            <input type="text" name="q" placeholder="Search articles..." value="<?= htmlspecialchars($q) ?>">
            
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c['slug'] ?>" <?= $category == $c['slug'] ? 'selected' : '' ?>>
                        <?= $c['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <input type="date" name="date_from" value="<?= $date_from ?>" title="From Date">
            <input type="date" name="date_to" value="<?= $date_to ?>" title="To Date">
            
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <div class="results-info">
            Found <strong><?= count($results) ?></strong> articles
            <?= $q ? "for \"<em>$q</em>\"" : '' ?>
        </div>

        <div class="news-grid">
            <?php if(empty($results)): ?>
                <div class="empty-state" style="grid-column:1/-1;">
                    <i class="fas fa-search"></i>
                    <p>No articles found. Try different keywords or filters.</p>
                </div>
            <?php else: ?>
                <?php foreach($results as $a): ?>
                    <article class="news-card">
                        <div class="card-img">
                            <img src="<?= $a['image_url'] ?: 'https://via.placeholder.com/400x220' ?>" alt="">
                        </div>
                        <div class="card-body">
                            <span class="category-tag"><?= $a['category_name'] ?></span>
                            <h3><a href="article.php?id=<?= $a['article_id'] ?>"><?= htmlspecialchars($a['title']) ?></a></h3>
                            <p><?= truncate(strip_tags($a['content']), 120) ?></p>
                            <small><i class="far fa-clock"></i> <?= formatDate($a['created_at']) ?></small>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<style>
.search-page { padding: 50px 0; min-height: 80vh; background: #f5f8f5; }
.search-filters {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 10px;
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.search-filters input, .search-filters select {
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
}
.search-filters button {
    background: #1a8c2f;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.results-info {
    margin-bottom: 20px;
    color: #666;
    font-size: 15px;
}
.empty-state {
    text-align: center;
    padding: 50px;
    background: white;
    border-radius: 15px;
}
.empty-state i { font-size: 48px; color: #ccc; margin-bottom: 15px; }
@media(max-width:768px) {
    .search-filters { grid-template-columns: 1fr; }
}
</style>
</body>
</html>