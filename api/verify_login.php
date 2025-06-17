<?php
require(__DIR__ . '/../db/db.php');

function loginUser($email, $password)
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, name, email, password_hash FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            return $user; // Successful login
        }
    }

    return false; // Invalid credentials
}
