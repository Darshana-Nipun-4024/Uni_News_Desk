<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Terms of Service - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container" style="padding: 50px 20px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08);">
        <h1 style="color: var(--dark-green); margin-bottom: 20px;">Terms of Service</h1>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">1. Submission Guidelines</h3>
        <p>All submitted articles, announcements, and media must comply with NSBM Green University guidelines and media standards.</p>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">2. Content Moderation</h3>
        <p>Submissions undergo approval by the Faculty NewsDesk Admin before publication. Admins reserve the right to edit or decline content that violates rules.</p>
        
        <h3 style="color: var(--primary-green); margin-top: 20px;">3. User Conduct</h3>
        <p>Users must submit accurate information and refrain from submitting plagiarized, misleading, or offensive content.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>