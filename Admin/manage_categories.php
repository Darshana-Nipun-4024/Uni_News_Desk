<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
requireAdmin();

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM categories WHERE category_id=?")->execute([(int)$_GET['delete']]);
    header("Location: manage_categories.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i','-',$name));
    $desc = sanitize($_POST['description']);
    
    if (!empty($_POST['id'])) {
        $pdo->prepare("UPDATE categories SET name=?, slug=?, description=? WHERE category_id=?")
            ->execute([$name, $slug, $desc, (int)$_POST['id']]);
    } else {
        $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (?,?,?)")
            ->execute([$name, $slug, $desc]);
    }
    header("Location: manage_categories.php");
    exit;
}

$editCat = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCat = $stmt->fetch();
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM articles WHERE category_id=c.category_id) AS article_count FROM categories c")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'sidebar.php'; ?>
    <main class="admin-main">
        <h1>Manage Categories</h1>

        <div style="background:white;padding:25px;border-radius:10px;margin-bottom:20px;max-width:500px;">
            <h2><?= $editCat ? 'Edit Category' : 'Add New Category' ?></h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editCat['category_id'] ?? '' ?>">
                <input type="text" name="name" placeholder="Category Name" value="<?= $editCat['name'] ?? '' ?>" required style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:5px;">
                <textarea name="description" placeholder="Description" rows="3" style="width:100%;padding:10px;margin:10px 0;border:1px solid #ddd;border-radius:5px;"><?= $editCat['description'] ?? '' ?></textarea>
                <button type="submit" class="btn-approve" style="padding:10px 25px;"><?= $editCat ? 'Update' : 'Add' ?></button>
                <?php if($editCat): ?><a href="manage_categories.php" class="btn-reject">Cancel</a><?php endif; ?>
            </form>
        </div>

        <table class="admin-table">
            <thead><tr><th>Name</th><th>Slug</th><th>Articles</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($categories as $c): ?>
                <tr>
                    <td><?= $c['name'] ?></td>
                    <td><?= $c['slug'] ?></td>
                    <td><?= $c['article_count'] ?></td>
                    <td>
                        <a href="?edit=<?= $c['category_id'] ?>" class="btn-approve">Edit</a>
                        <a href="?delete=<?= $c['category_id'] ?>" class="btn-reject" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>