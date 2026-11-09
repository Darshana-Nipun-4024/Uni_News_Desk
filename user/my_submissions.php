<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
requireLogin();

$submissions = $pdo->prepare("SELECT a.*, c.name AS category_name 
                              FROM articles a 
                              LEFT JOIN categories c ON a.category_id = c.category_id 
                              WHERE a.author_id = ? ORDER BY a.created_at DESC");
$submissions->execute([$_SESSION['user_id']]);
$submissions = $submissions->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<section class="my-submissions">
    <div class="container">
        <h1 class="section-heading">My Submissions</h1>
        
        <?php if(empty($submissions)): ?>
            <div class="empty-state">
                <i class="far fa-file-alt"></i>
                <p>You haven't submitted any articles yet.</p>
                <a href="../submit_news.php" class="btn-primary">Submit Your First Article</a>
            </div>
        <?php else: ?>
            <table class="submissions-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Views</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($submissions as $s): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($s['title']) ?></strong></td>
                        <td><?= $s['category_name'] ?></td>
                        <td><?= formatDate($s['created_at']) ?></td>
                        <td>
                            <span class="status-badge status-<?= $s['status'] ?>">
                                <?= ucfirst($s['status']) ?>
                            </span>
                        </td>
                        <td><?= $s['views'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
.my-submissions { padding: 50px 0; min-height: 70vh; background: #f5f8f5; }
.submissions-table {
    width: 100%;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    border-collapse: collapse;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.submissions-table th {
    background: #1a8c2f;
    color: white;
    padding: 15px;
    text-align: left;
}
.submissions-table td { padding: 15px; border-bottom: 1px solid #eee; }
.status-badge {
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-approved { background: #d4edda; color: #155724; }
.status-rejected { background: #f8d7da; color: #721c24; }
.empty-state { text-align: center; padding: 60px; background: white; border-radius: 15px; }
.empty-state i { font-size: 64px; color: #ccc; margin-bottom: 15px; }
.btn-primary {
    display: inline-block;
    margin-top: 15px;
    background: #1a8c2f;
    color: white;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
}
</style>
</body>
</html>