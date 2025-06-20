<?php
session_start();

require(__DIR__ . '/../config.php');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../db/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Validate Email
$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    $_SESSION['otp_sent'] = '❌ Email is required.';
    header('Location: ../forgot_password.php');
    exit;
}

// 2. Generate and store OTP in session
$otp = rand(100000, 999999);
$_SESSION['reset_email'] = $email;
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // Valid for 5 minutes

// 3. Prepare email using PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $email_host;          // from config.php
    $mail->SMTPAuth = true;
    $mail->Username = $email_username;  // from config.php
    $mail->Password = $email_password;  // from config.php
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Recommended secure setting
    $mail->Port = 465; // Use 587 if using TLS

    $mail->setFrom($admin_email, 'Property Expert');  // from config.php
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Password Reset';
    $mail->Body = "
        <h3>Password Reset Request</h3>
        <p>Use the following OTP to reset your password:</p>
        <h2 style='color: blue;'>$otp</h2>
        <p>This OTP is valid for 5 minutes.</p>
    ";

    if ($mail->send()) {
        $_SESSION['otp_sent'] = '✅ OTP has been sent to your email!';
        header('Location: ../verify_otp.php');
        exit;
    } else {
        $_SESSION['otp_sent'] = '❌ Mailer failed: ' . $mail->ErrorInfo;
        header('Location: ../forgot_password.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['otp_sent'] = '❌ Mailer Error: ' . $mail->ErrorInfo;
    header('Location: ../forgot_password.php');
    exit;
}
