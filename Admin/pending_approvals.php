<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireAdmin();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    $pdo->prepare("UPDATE articles SET status=? WHERE article_id=?")->execute([$action, $id]);
    header("Location: pending_approvals.php");
    exit;
}

$pending = $pdo->query("SELECT a.*, c.name AS cat FROM articles a LEFT JOIN categories c ON a.category_id=c.category_id WHERE a.status='pending' ORDER BY a.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pending Approvals</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; // You can extract sidebar into reusable file ?>
    <main class="admin-main">
        <h1>Pending Approvals (<?= count($pending) ?>)</h1>
        <table class="admin-table">
            <thead>
                <tr><th>Title</th><th>Category</th><th>Author</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach($pending as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['title']) ?></td>
                    <td><?= $p['cat'] ?></td>
                    <td><?= $p['author_name'] ?></td>
                    <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <a href="?action=approve&id=<?= $p['article_id'] ?>" class="btn-approve">Approve</a>
                        <a href="?action=reject&id=<?= $p['article_id'] ?>" class="btn-reject">Reject</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>