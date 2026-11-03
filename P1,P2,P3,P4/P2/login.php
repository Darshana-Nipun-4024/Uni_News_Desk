<?php
session_start();

function failLogin($message) {
    $_SESSION['login_error'] = $message;
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    failLogin("Your session expired. Please try again.");
}

$email = trim($_POST['email'] ?? "");
$password = $_POST['password'] ?? "";

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
    failLogin("Please enter a valid email and password.");
}

require_once "db.php";

$stmt = $pdo->prepare("SELECT id, full_name, email, password_hash FROM users WHERE email = :email LIMIT 1");
$stmt->execute(["email" => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    failLogin("Incorrect email or password.");
}

session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];

header("Location: dashboard.php");
exit;
