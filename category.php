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
