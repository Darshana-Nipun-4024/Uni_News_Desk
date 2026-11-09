<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Privacy Policy - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container" style="padding: 50px 20px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
        <h1 style="color: var(--dark-green); margin-bottom: 20px;">Privacy Policy</h1>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">1. Information We Collect</h3>
        <p>We collect personal information such as your name, student ID, and official NSBM email address when you register or submit articles on NSBM NewsDesk.</p>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">2. How Your Information is Used</h3>
        <p>Your details are strictly used to verify student identity, process article submissions, and maintain platform security.</p>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">3. Data Protection</h3>
        <p>Your account information is stored securely and is accessible only to authorized administrators for moderation purposes.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>