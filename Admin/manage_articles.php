<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
requireAdmin();

// DELETE
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM articles WHERE article_id=?")->execute([(int)$_GET['delete']]);
    header("Location: manage_articles.php");
    exit;
}

// TOGGLE BREAKING
if (isset($_GET['breaking'])) {
    $pdo->prepare("UPDATE articles SET is_breaking = NOT is_breaking WHERE article_id=?")->execute([(int)$_GET['breaking']]);
    header("Location: manage_articles.php");
    exit;
}

// EDIT
$editArticle = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE article_id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editArticle = $stmt->fetch();
}

// UPDATE / INSERT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $image = sanitize($_POST['image_url']);
    $status = $_POST['status'];
    
    if (!empty($_POST['article_id'])) {
        $pdo->prepare("UPDATE articles SET title=?, content=?, category_id=?, image_url=?, status=? WHERE article_id=?")
            ->execute([$title, $content, $category_id, $image, $status, (int)$_POST['article_id']]);
    } else {
        $pdo->prepare("INSERT INTO articles (title, content, category_id, image_url, status, author_name, author_id) VALUES (?,?,?,?,?,?,?)")
            ->execute([$title, $content, $category_id, $image, $status, $_SESSION['full_name'], $_SESSION['user_id']]);
    }
    header("Location: manage_articles.php");
    exit;
}

$articles = $pdo->query("SELECT a.*, c.name AS cat_name FROM articles a LEFT JOIN categories c ON a.category_id=c.category_id ORDER BY a.created_at DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Articles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="admin-main">
        <h1>Manage Articles</h1>
        
        <button onclick="document.getElementById('addForm').style.display='block'" class="btn-approve" style="margin-bottom:20px;padding:10px 20px;">
            <i class="fas fa-plus"></i> Add New Article
        </button>

        <!-- Add/Edit Form -->
        <div id="addForm" style="<?= $editArticle?'display:block':'display:none' ?>;background:white;padding:25px;border-radius:10px;margin-bottom:20px;">
            <h2><?= $editArticle ? 'Edit Article' : 'Add New Article' ?></h2>
            <form method="POST">
                <input type="hidden" name="article_id" value="<?= $editArticle['article_id'] ?? '' ?>">
                <label>Title</label>
                <input type="text" name="title" value="<?= $editArticle['title'] ?? '' ?>" required style="width:100%;padding:10px;margin:5px 0 15px;border:1px solid #ddd;border-radius:5px;">
                
                <label>Category</label>
                <select name="category_id" required style="width:100%;padding:10px;margin:5px 0 15px;border:1px solid #ddd;border-radius:5px;">
                    <?php foreach($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" <?= ($editArticle['category_id']??0)==$c['category_id']?'selected':'' ?>><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Image URL</label>
                <input type="url" name="image_url" value="<?= $editArticle['image_url'] ?? '' ?>" style="width:100%;padding:10px;margin:5px 0 15px;border:1px solid #ddd;border-radius:5px;">
                
                <label>Content</label>
                <textarea name="content" rows="6" required style="width:100%;padding:10px;margin:5px 0 15px;border:1px solid #ddd;border-radius:5px;"><?= $editArticle['content'] ?? '' ?></textarea>
                
                <label>Status</label>
                <select name="status" style="width:100%;padding:10px;margin:5px 0 15px;border:1px solid #ddd;border-radius:5px;">
                    <option value="approved" <?= ($editArticle['status']??'')=='approved'?'selected':'' ?>>Approved</option>
                    <option value="pending" <?= ($editArticle['status']??'')=='pending'?'selected':'' ?>>Pending</option>
                    <option value="rejected" <?= ($editArticle['status']??'')=='rejected'?'selected':'' ?>>Rejected</option>
                </select>

                <button type="submit" class="btn-approve" style="padding:10px 25px;"><?= $editArticle ? 'Update' : 'Create' ?></button>
                <a href="manage_articles.php" class="btn-reject" style="padding:10px 25px;">Cancel</a>
            </form>
        </div>

        <table class="admin-table">
            <thead>
                <tr><th>Title</th><th>Category</th><th>Status</th><th>Views</th><th>Breaking</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach($articles as $a): ?>
                <tr>
                    <td><?= truncate($a['title'], 50) ?></td>
                    <td><?= $a['cat_name'] ?></td>
                    <td><span class="status-badge status-<?= $a['status'] ?>"><?= $a['status'] ?></span></td>
                    <td><?= $a['views'] ?></td>
                    <td>
                        <a href="?breaking=<?= $a['article_id'] ?>" style="font-size:20px;color:<?= $a['is_breaking']?'#e63946':'#ccc' ?>">
                            <i class="fas fa-bolt"></i>
                        </a>
                    </td>
                    <td>
                        <a href="?edit=<?= $a['article_id'] ?>" class="btn-approve">Edit</a>
                        <a href="?delete=<?= $a['article_id'] ?>" class="btn-reject" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
<style>
.status-badge { padding:4px 10px; border-radius:10px; font-size:12px; font-weight:600; }
.status-pending { background:#fff3cd; color:#856404; }
.status-approved { background:#d4edda; color:#155724; }
.status-rejected { background:#f8d7da; color:#721c24; }
</style>
</body>
</html>