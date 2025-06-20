<?php
require(__DIR__ . '/../db/db.php');
header('Content-Type: application/json');
global $conn;

// Get POST input
$license = trim($_POST['license_key'] ?? '');
$domain = trim($_POST['domain'] ?? '');

// Basic validation
if (empty($license) || empty($domain)) {
    echo json_encode(['success' => false, 'message' => 'License key and domain are required']);
    exit;
}

// Normalize domain (e.g., remove http, https, www)
$parsed_domain = parse_url("http://$domain", PHP_URL_HOST);
$clean_domain = preg_replace('/^www\./', '', $parsed_domain);
$ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Find license in database
$stmt = $conn->prepare("SELECT * FROM licenses WHERE license_key = ?");
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid license key']);
    exit;
}

$row = $result->fetch_assoc();
$license_id = $row['id'];

// 1. Check expiration
$today = date('Y-m-d');
if ($row['expiry_date'] < $today) {
    echo json_encode(['success' => false, 'message' => 'License has expired']);
    exit;
}

// 2. Check active flag
if ((int)$row['is_active'] !== 1) {
    echo json_encode(['success' => false, 'message' => 'License is inactive']);
    exit;
}

// 3. Enforce one-time domain lock
if (empty($row['domain_name'])) {
    // First time: Bind the domain
    $stmt = $conn->prepare("UPDATE licenses SET domain_name = ? WHERE id = ?");
    $stmt->bind_param("si", $clean_domain, $license_id);
    $stmt->execute();
} elseif ($row['domain_name'] !== $clean_domain) {
    echo json_encode(['success' => false, 'message' => 'This license is already activated on another domain']);
    exit;
}

// ✅ Log installation attempt
$stmt = $conn->prepare("INSERT INTO installations (license_id, domain_name, ip_address) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $license_id, $clean_domain, $ip_address);
$stmt->execute();

// ✅ Return success
echo json_encode([
    'success' => true,
    'message' => 'License is valid and bound to this domain',
    'domain' => $clean_domain,
    'expires_on' => $row['expiry_date']
]);
exit;
