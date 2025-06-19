<?php
require(__DIR__ . '/../db/db.php');
header('Content-Type: application/json');
global $conn;

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get license key and domain
$license = trim($_POST['license_key'] ?? '');
$domain = $_SERVER['HTTP_HOST']; // You can also allow passing domain via $_POST if needed

// Validate license key
if (empty($license)) {
    echo json_encode(['success' => false, 'message' => 'License key is required']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM licenses WHERE license_key = ?");
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid license key']);
    exit;
}

$row = $result->fetch_assoc();

// Check if expired
$today = date('Y-m-d');
if ($row['expiry_date'] < $today) {
    echo json_encode(['success' => false, 'message' => 'License has expired']);
    exit;
}

// Check if active
if ((int)$row['is_active'] !== 1) {
    echo json_encode(['success' => false, 'message' => 'License is inactive']);
    exit;
}

// First-time domain binding
if (empty($row['domain_name'])) {
    $stmt = $conn->prepare("UPDATE licenses SET domain_name = ? WHERE id = ?");
    $stmt->bind_param("si", $domain, $row['id']);
    $stmt->execute();
} elseif ($row['domain_name'] !== $domain) {
    echo json_encode(['success' => false, 'message' => 'License is already used on another domain']);
    exit;
}

// All validations passed
echo json_encode([
    'success' => true,
    'message' => 'License is valid',
    'domain' => $domain,
    'expires_on' => $row['expiry_date']
]);
