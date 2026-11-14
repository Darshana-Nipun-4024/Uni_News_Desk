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