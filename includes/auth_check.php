<?php
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /nsbm-newsdesk/login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: /nsbm-newsdesk/login.php");
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>