<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$name = htmlspecialchars($_SESSION['user_name'] ?? "Student", ENT_QUOTES, "UTF-8");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM NewsDesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <main class="dashboard-message">
        <h1>Welcome, <?php echo $name; ?>!</h1>
        <p>You have successfully signed in to NSBM NewsDesk.</p>
        <a href="logout.php">Log out</a>
    </main>
</body>
</html>
