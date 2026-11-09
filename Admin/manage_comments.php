<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM comments WHERE comment_id=?")->execute([(int)$_GET['delete']]);
    header("Location: manage_comments.php");
    exit;
}

if (isset($_GET['toggle'])) {
    $pdo->prepare("UPDATE comments SET status=IF(status='visible','hidden','visible') WHERE comment_id=?")->execute([(int)$_GET['toggle']]);
    header("Location: manage_comments.php");
    exit;
}

$comments = $pdo->query("SELECT c.*, a.title AS article_title, u.full_name 
                         FROM comments c
                         JOIN articles a ON c.article_id = a.article_id
                         JOIN users u ON c.user_id = u.user_id
                         ORDER BY c.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Comments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="admin-main">
        <h1>Moderate Comments</h1>
        <table class="admin-table">
            <thead><tr><th>User</th><th>Article</th><th>Comment</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($comments as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['full_name']) ?></td>
                    <td><?= htmlspecialchars(substr($c['article_title'],0,40)) ?></td>
                    <td><?= htmlspecialchars(substr($c['comment_text'],0,80)) ?>...</td>
                    <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                    <td><?= $c['status'] ?></td>
                    <td>
                        <a href="?toggle=<?= $c['comment_id'] ?>" class="btn-approve">
                            <?= $c['status']=='visible'?'Hide':'Show' ?>
                        </a>
                        <a href="?delete=<?= $c['comment_id'] ?>" class="btn-reject" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>