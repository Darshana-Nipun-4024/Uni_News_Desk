<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM users WHERE user_id=?")->execute([(int)$_GET['delete']]);
    header("Location: manage_users.php");
    exit;
}

if (isset($_GET['toggle_role'])) {
    $pdo->prepare("UPDATE users SET role=IF(role='admin','student','admin') WHERE user_id=?")->execute([(int)$_GET['toggle_role']]);
    header("Location: manage_users.php");
    exit;
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="admin-main">
        <h1>Manage Users</h1>
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Student ID</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['full_name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><span style="background:<?= $u['role']=='admin'?'#1a8c2f':'#2196f3' ?>;color:white;padding:3px 10px;border-radius:10px;font-size:12px;"><?= $u['role'] ?></span></td>
                    <td><?= $u['student_id'] ?></td>
                    <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <a href="?toggle_role=<?= $u['user_id'] ?>" class="btn-approve">Toggle Role</a>
                        <?php if($u['user_id'] != $_SESSION['user_id']): ?>
                        <a href="?delete=<?= $u['user_id'] ?>" class="btn-reject" onclick="return confirm('Delete user?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>