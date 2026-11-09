<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status'=>'error','message'=>'Please login to comment']);
    exit;
}

$article_id = (int)($_POST['article_id'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if (empty($comment)) {
    echo json_encode(['status'=>'error','message'=>'Comment cannot be empty']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO comments (article_id, user_id, comment_text) VALUES (?, ?, ?)");
if ($stmt->execute([$article_id, $_SESSION['user_id'], $comment])) {
    echo json_encode([
        'status'=>'success',
        'name'=>$_SESSION['full_name'],
        'comment'=>htmlspecialchars($comment)
    ]);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to post comment']);
}
?>