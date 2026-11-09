<?php
require_once '../includes/db_connect.php';
require_once '../includes/auth_check.php';
require_once '../includes/functions.php';
requireLogin();

$user = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$user->execute([$_SESSION['user_id']]);
$user = $user->fetch();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['full_name']);
    $faculty = sanitize($_POST['faculty']);
    $pdo->prepare("UPDATE users SET full_name=?, faculty=? WHERE user_id=?")
        ->execute([$name, $faculty, $_SESSION['user_id']]);
    $_SESSION['full_name'] = $name;
    $msg = "Profile updated successfully!";
}

// Stats
$submitted = $pdo->prepare("SELECT COUNT(*) FROM articles WHERE author_id=?");
$submitted->execute([$_SESSION['user_id']]);
$submitted = $submitted->fetchColumn();

$bookmarks = $pdo->prepare("SELECT COUNT(*) FROM bookmarks WHERE user_id=?");
$bookmarks->execute([$_SESSION['user_id']]);
$bookmarks = $bookmarks->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<section class="profile-page">
    <div class="container">
        <div class="profile-grid">
            
            <aside class="profile-sidebar">
                <div class="avatar"><i class="fas fa-user-circle"></i></div>
                <h2><?= htmlspecialchars($user['full_name']) ?></h2>
                <p><?= htmlspecialchars($user['email']) ?></p>
                
                <div class="stats-mini">
                    <div><strong><?= $submitted ?></strong><span>Submitted</span></div>
                    <div><strong><?= $bookmarks ?></strong><span>Bookmarks</span></div>
                </div>
                
                <nav class="profile-nav">
                    <a href="profile.php" class="active"><i class="fas fa-user"></i> Profile</a>
                    <a href="my_submissions.php"><i class="fas fa-file-alt"></i> My Submissions</a>
                    <a href="bookmarks.php"><i class="fas fa-bookmark"></i> Bookmarks</a>
                    <a href="subscriptions.php"><i class="fas fa-bell"></i> Subscriptions</a>
                </nav>
            </aside>

            <main class="profile-content">
                <h1>Edit Profile</h1>
                <?php if($msg): ?><div class="alert success"><?= $msg ?></div><?php endif; ?>
                
                <form method="POST" class="profile-form">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    
                    <label>Email (read-only)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    
                    <label>Student ID</label>
                    <input type="text" value="<?= htmlspecialchars($user['student_id'] ?? '') ?>" readonly>
                    
                    <label>Faculty</label>
                    <input type="text" name="faculty" value="<?= htmlspecialchars($user['faculty'] ?? '') ?>">
                    
                    <button type="submit">Save Changes</button>
                </form>
            </main>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<style>
.profile-page { padding: 40px 0; min-height: 80vh; background: #f5f8f5; }
.profile-grid { display: grid; grid-template-columns: 280px 1fr; gap: 30px; }
.profile-sidebar {
    background: white;
    padding: 30px 20px;
    border-radius: 15px;
    text-align: center;
    height: fit-content;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.avatar i { font-size: 100px; color: #1a8c2f; }
.profile-sidebar h2 { margin: 15px 0 5px; color: #0d5c1c; }
.profile-sidebar p { color: #666; font-size: 14px; margin-bottom: 20px; }
.stats-mini { display: flex; justify-content: space-around; padding: 15px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; margin-bottom: 20px; }
.stats-mini div { display: flex; flex-direction: column; }
.stats-mini strong { font-size: 22px; color: #1a8c2f; }
.stats-mini span { font-size: 12px; color: #999; }
.profile-nav { display: flex; flex-direction: column; text-align: left; }
.profile-nav a {
    padding: 12px 15px;
    text-decoration: none;
    color: #333;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: 0.2s;
}
.profile-nav a:hover, .profile-nav a.active {
    background: #1a8c2f;
    color: white;
}
.profile-nav a i { width: 20px; margin-right: 8px; }
.profile-content {
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.profile-content h1 { color: #0d5c1c; margin-bottom: 20px; }
.profile-form label { display: block; margin: 15px 0 5px; font-weight: 600; font-size: 14px; }
.profile-form input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
}
.profile-form input:focus { border-color: #1a8c2f; }
.profile-form input[readonly] { background: #f5f5f5; color: #777; }
.profile-form button {
    margin-top: 20px;
    background: #1a8c2f;
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.alert.success { background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 15px; }
@media(max-width:768px) { .profile-grid { grid-template-columns: 1fr; } }
</style>
</body>
</html>