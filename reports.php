<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireAdmin();

// Top viewed articles
$topArticles = $pdo->query("SELECT a.*, c.name AS cat FROM articles a LEFT JOIN categories c ON a.category_id=c.category_id WHERE a.status='approved' ORDER BY views DESC LIMIT 10")->fetchAll();

// Category stats
$catStats = $pdo->query("SELECT c.name, COUNT(a.article_id) AS count, SUM(a.views) AS total_views
                         FROM categories c LEFT JOIN articles a ON c.category_id=a.category_id
                         GROUP BY c.category_id")->fetchAll();

// Monthly submissions
$monthly = $pdo->query("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, COUNT(*) AS count 
                        FROM articles GROUP BY month ORDER BY month DESC LIMIT 6")->fetchAll();

// Most active users
$topUsers = $pdo->query("SELECT u.full_name, COUNT(a.article_id) AS submissions 
                         FROM users u LEFT JOIN articles a ON u.user_id=a.author_id
                         WHERE u.role='student' GROUP BY u.user_id ORDER BY submissions DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="admin-main">
        <h1>Readership & Engagement Reports</h1>

        <div class="report-grid">
            <div class="report-card">
                <h3>Articles by Category</h3>
                <canvas id="catChart"></canvas>
            </div>
            <div class="report-card">
                <h3>Monthly Submissions</h3>
                <canvas id="monthChart"></canvas>
            </div>
        </div>

        <div style="margin-top:30px;">
            <h3>Top 10 Most Viewed Articles</h3>
            <table class="admin-table">
                <thead><tr><th>Title</th><th>Category</th><th>Views</th></tr></thead>
                <tbody>
                    <?php foreach($topArticles as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['title']) ?></td>
                        <td><?= $a['cat'] ?></td>
                        <td><strong><?= $a['views'] ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div style="margin-top:30px;">
            <h3>Most Active Contributors</h3>
            <table class="admin-table">
                <thead><tr><th>Student</th><th>Submissions</th></tr></thead>
                <tbody>
                    <?php foreach($topUsers as $u): ?>
                    <tr><td><?= htmlspecialchars($u['full_name']) ?></td><td><?= $u['submissions'] ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<style>
.report-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.report-card { background:white; padding:25px; border-radius:15px; box-shadow:0 5px 20px rgba(0,0,0,0.05); }
.report-card h3 { color:#0d5c1c; margin-bottom:15px; }
</style>

<script>
const catData = <?= json_encode($catStats) ?>;
const monthData = <?= json_encode(array_reverse($monthly)) ?>;

new Chart(document.getElementById('catChart'), {
    type: 'doughnut',
    data: {
        labels: catData.map(c => c.name),
        datasets: [{
            data: catData.map(c => c.count),
            backgroundColor: ['#1a8c2f','#2196f3','#ff9800','#9c27b0','#e63946']
        }]
    }
});

new Chart(document.getElementById('monthChart'), {
    type: 'bar',
    data: {
        labels: monthData.map(m => m.month),
        datasets: [{
            label: 'Submissions',
            data: monthData.map(m => m.count),
            backgroundColor: '#1a8c2f'
        }]
    }
});
</script>
</body>
</html>