<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = (int)$_POST['category_id'];
    $action = $_POST['action'];
    
    if ($action === 'subscribe') {
        $pdo->prepare("INSERT IGNORE INTO subscriptions (user_id, category_id) VALUES (?, ?)")
            ->execute([$_SESSION['user_id'], $cat_id]);
    } else {
        $pdo->prepare("DELETE FROM subscriptions WHERE user_id=? AND category_id=?")
            ->execute([$_SESSION['user_id'], $cat_id]);
    }
    header("Location: subscriptions.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$subscribed = $pdo->prepare("SELECT category_id FROM subscriptions WHERE user_id=?");
$subscribed->execute([$_SESSION['user_id']]);
$subscribed = $subscribed->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subscriptions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<section class="subs-page">
    <div class="container">
        <h1 class="section-heading"><i class="fas fa-bell"></i> My Subscriptions</h1>
        <p style="margin-bottom:25px;color:#666;">Subscribe to categories to stay updated with the latest news.</p>
        
        <div class="sub-grid">
            <?php foreach($categories as $cat): 
                $isSubbed = in_array($cat['category_id'], $subscribed);
            ?>
                <div class="sub-card">
                    <h3><?= $cat['name'] ?></h3>
                    <p><?= $cat['description'] ?></p>
                    <form method="POST">
                        <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
                        <input type="hidden" name="action" value="<?= $isSubbed ? 'unsubscribe' : 'subscribe' ?>">
                        <button type="submit" class="<?= $isSubbed ? 'btn-unsub' : 'btn-sub' ?>">
                            <?= $isSubbed ? '✓ Subscribed' : '+ Subscribe' ?>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
.subs-page { padding: 50px 0; min-height: 70vh; background: #f5f8f5; }
.sub-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 20px; }
.sub-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.sub-card h3 { color: #0d5c1c; margin-bottom: 10px; }
.sub-card p { color: #666; margin-bottom: 15px; font-size: 14px; }
.btn-sub, .btn-unsub {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.btn-sub { background: #1a8c2f; color: white; }
.btn-unsub { background: #e63946; color: white; }
</style>
</body>
</html>