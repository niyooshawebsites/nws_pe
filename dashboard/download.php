<?php
session_start();

require(__DIR__ . '/../config.php');
require(__DIR__ . '/../api/fetch_license_details.php');

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(403);
    exit("Unauthorized access.");
}

$userId = $_SESSION['user']['id'];
$result = fetchLicenseDetails($userId);

if ($result->num_rows == 0) {
    http_response_code(403);
    exit("No license found.");
}

$row = $result->fetch_assoc();

// Check Razorpay subscription and customer ID
if (empty($row['razorpay_subscription_id']) || empty($row['razorpay_customer_id'])) {
    http_response_code(403);
    exit("No active subscription found.");
}

// Optional: Check if subscription is still active based on expiry date
if (strtotime($row['expiry_date']) < time()) {
    http_response_code(403);
    exit("Your license has expired.");
}

// Absolute path to the protected file
$filePath = __DIR__ . '/../app/property_expert.zip';

if (!file_exists($filePath)) {
    http_response_code(404);
    exit("File not found.");
}

// Send download headers
header('Content-Description: File Transfer');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="your-app.zip"');
header('Content-Length: ' . filesize($filePath));
flush();
readfile($filePath);
exit;
