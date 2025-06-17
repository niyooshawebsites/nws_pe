<?php

require(__DIR__ . '/../config.php');

$conn = new mysqli($host, $user, $pass, $db);

// Check connection and handle errors
if ($conn->connect_error) {
    // Optionally log error details (do NOT show detailed error to users)
    error_log("Database connection error: " . $conn->connect_error);

    // Show a generic error message
    http_response_code(500); // Internal Server Error
    echo "Oops! We’re having trouble connecting to the database. Please try again later.";
    exit;
}

// You can now safely use $conn
