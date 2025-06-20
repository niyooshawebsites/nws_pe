<?php
session_start();
$enteredOtp = $_POST['otp'] ?? '';

if (!isset($_SESSION['otp']) || time() > $_SESSION['otp_expiry']) {
    $_SESSION['otp_error'] = "OTP expired or not found.";
    header('Location: ../verify_otp.php');
    exit;
}

if ($enteredOtp != $_SESSION['otp']) {
    $_SESSION['otp_error'] = "Incorrect OTP.";
    header('Location: ../verify_otp.php');
    exit;
}

// OTP correct → redirect to reset form
header('Location: ../reset_password.php');
exit;
