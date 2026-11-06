<?php
session_start();

// Demo account (replace with a database check later)
$demo_email    = "student@students.nsbm.ac.lk";
$demo_password = "12345678";

// Only accept form submissions
if ($_SERVER["REQUEST_METHOD"] != "POST") {
  header("Location: ../login.html");
  exit;
}

$email    = trim($_POST["email"]);
$password = $_POST["password"];

if ($email == "" || $password == "") {
  header("Location: ../login.html?error=empty");
} elseif ($email == $demo_email && $password == $demo_password) {
  $_SESSION["user"] = $email;      // remember the user
  header("Location: ../index.html"); // go to the home page
} else {
  header("Location: ../login.html?error=wrong");
}
exit;
?>
