<?php
require(__DIR__ . '/../db/db.php');

function fetchLicenseDetails($userId)
{
    global $conn;

    // ✅ Fetch licenses for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM licenses WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}
