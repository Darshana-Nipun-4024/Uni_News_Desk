<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireAdmin();

$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM articles WHERE status='pending'")->fetchColumn(),
    'approved' => $pdo->query("SELECT COUNT(*) FROM articles WHERE status='approved'")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <h2><i class="fas fa-newspaper"></i> NewsDesk</h2>
        <nav>
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_articles.php"><i class="fas fa-file-alt"></i> Articles</a>
            <a href="pending_approvals.php"><i class="fas fa-clock"></i> Pending Approvals</a>
            <a href="manage_categories.php"><i class="fas fa-folder"></i> Categories</a>
            <a href="manage_comments.php"><i class="fas fa-comments"></i> Comments</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Users</a>
            <a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="admin-main">
        <h1>Dashboard Overview</h1>
        <div class="stats-grid">
            <div class="stat-card green">
                <i class="fas fa-file-alt"></i>
                <h3><?= $stats['total'] ?></h3>
                <p>Total Articles</p>
            </div>
            <div class="stat-card orange">
                <i class="fas fa-clock"></i>
                <h3><?= $stats['pending'] ?></h3>
                <p>Pending Review</p>
            </div>
            <div class="stat-card blue">
                <i class="fas fa-check-circle"></i>
                <h3><?= $stats['approved'] ?></h3>
                <p>Approved</p>
            </div>
            <div class="stat-card purple">
                <i class="fas fa-users"></i>
                <h3><?= $stats['users'] ?></h3>
                <p>Registered Students</p>
            </div>
        </div>
    </main>
</div>
</body>
</html>