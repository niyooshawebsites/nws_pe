<?php
session_start();
require(__DIR__ . '/../db/db.php');

$email = $_SESSION['reset_email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    die("Invalid attempt.");
}

$hashed = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
$stmt->bind_param("ss", $hashed, $email);
$stmt->execute();

// Cleanup
unset($_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['reset_email']);

echo "<div class='alert alert-success text-center mt-5 container'>✅ Password updated successfully! You can now <a href='../login.php'>login</a>.</div>";
