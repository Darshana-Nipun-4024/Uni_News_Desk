<?php
// Include necessary database connection, authentication checks, and helper functions
require_once 'includes/db_connect.php';
require_once 'includes/auth_check.php';
require_once 'includes/functions.php';
requireLogin();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $author = sanitize($_POST['author']);
    $image = sanitize($_POST['image_url']);

    $stmt = $pdo->prepare("INSERT INTO articles (title, content, category_id, author_id, author_name, image_url, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    if ($stmt->execute([$title, $content, $category_id, $_SESSION['user_id'], $author, $image])) {
        $msg = "Article submitted successfully! It will be reviewed by the admin.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit News - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container" style="padding:40px 20px;">
    <div class="submit-form-wrapper">
        <span class="portal-tag">Student & Club Portal</span>
        <h1>Submit News Article or Announcement</h1>
        <p>Submitted articles undergo review by the Faculty NewsDesk Admin before being published live.</p>
        
        <?php if($msg): ?><div class="alert success"><?= $msg ?></div><?php endif; ?>
        
        <form method="POST" id="newsForm">
            <label>Article Title *</label>
            <input type="text" name="title" placeholder="e.g. Computing Faculty Hackathon 2026" required>

            <div class="form-row">
                <div>
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="">Select</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Author / Club *</label>
                    <input type="text" name="author" placeholder="e.g. Software Engineering Society" required>
                </div>
            </div>

            <label>Featured Image URL (Optional)</label>
            <input type="url" name="image_url" placeholder="https://images.unsplash.com/...">

            <label>Article Content *</label>
            <textarea name="content" rows="8" required></textarea>

            <label class="checkbox-label">
                <input type="checkbox" required> I confirm the submitted content complies with NSBM media policies.
            </label>

            <div class="form-actions">
                <a href="index.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit-main">Submit for Approval</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
.submit-form-wrapper {
    background: #b8e0c2;
    max-width: 750px;
    margin: 20px auto;
    padding: 40px;
    border-radius: 20px;
}
.portal-tag { background:#1a8c2f; color:white; padding:5px 15px; border-radius:15px; font-size:12px; }
.submit-form-wrapper h1 { color:#0d5c1c; margin:15px 0 5px; font-size:26px; }
.submit-form-wrapper label { display:block; margin:15px 0 5px; font-weight:500; font-size:14px; }
.submit-form-wrapper input, .submit-form-wrapper select, .submit-form-wrapper textarea {
    width:100%; padding:12px; background:#92c9a1; border:1px solid #1a8c2f; border-radius:8px; font-family:inherit;
}
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
.checkbox-label { display:flex; align-items:center; gap:10px; background:white; padding:12px; border-radius:8px; margin:20px 0; }
.form-actions { display:flex; justify-content:flex-end; gap:10px; }
.btn-cancel { padding:10px 25px; background:white; border:1px solid #ccc; border-radius:8px; text-decoration:none; color:black; }
.btn-submit-main { padding:10px 25px; background:#1a8c2f; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:700; }
.alert.success { background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin:15px 0; }
</style>
</body>
</html>
