<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'Please login first']);
    exit;
}

$article_id = (int)($_POST['article_id'] ?? 0);
$user_id = $_SESSION['user_id'];


$check = $pdo->prepare("SELECT 1 FROM bookmarks WHERE user_id=? AND article_id=?");
$check->execute([$user_id, $article_id]);

if ($check->fetchColumn()) {
    // Remove bookmark
    $pdo->prepare("DELETE FROM bookmarks WHERE user_id=? AND article_id=?")->execute([$user_id, $article_id]);
    echo json_encode(['status'=>'success','action'=>'removed']);
} else {
    // Add bookmark
    $pdo->prepare("INSERT INTO bookmarks (user_id, article_id) VALUES (?, ?)")->execute([$user_id, $article_id]);
    echo json_encode(['status'=>'success','action'=>'added']);
}
?>