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