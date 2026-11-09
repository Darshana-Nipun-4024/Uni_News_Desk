<?php
require_once 'includes/db_connect.php';

$error = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $studentId = trim($_POST['student_id']);
    $faculty = trim($_POST['faculty']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, student_id, faculty, role) VALUES (?, ?, ?, ?, ?, 'student')");
        $stmt->execute([$name, $email, $password, $studentId, $faculty]);
        $success = "Registered successfully! You can now login.";
    } catch(PDOException $e) {
        $error = "Email already exists or error occurred.";
    }
}
?>

<html>
<head>
    <title>Register - NSBM NewsDesk</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-container glass">
        <h2>Create Your Account</h2>
        <?php if($error): ?><div class="alert"><?= $error ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email"  name="email"  placeholder="NSBM Email"  pattern="[a-zA-Z0-9._%+\-]+@students\.nsbm\.ac\.lk$" title="Please enter a valid NSBM student email (e.g., name@students.nsbm.ac.lk)" required>
        <input type="text" name="student_id" placeholder="Student ID" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" required
>
            <input type="text" name="faculty" placeholder="Faculty">
            <input type="password" name="password" placeholder="Password" required minlength="6">
            <button type="submit">Register</button>
        </form>
        <p>Already registered? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
