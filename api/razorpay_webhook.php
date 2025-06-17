<?php

require __DIR__ . '/../vendor/autoload.php';
require(__DIR__ . '/../db/db.php');
require(__DIR__ . '/../config.php');

// Get the request body and signature
$body = file_get_contents('php://input');
$headers = getallheaders();
$razorpaySignature = $headers['X-Razorpay-Signature'] ?? '';

use Razorpay\Api\WebhookSignature;

try {
    // Verify signature
    WebhookSignature::verify($body, $razorpaySignature, $webhookSecret);

    $data = json_decode($body, true);
    $event = $data['event'];

    // === Handle Subscription Activated ===
    if ($event === 'subscription.activated') {
        $subscriptionId = $data['payload']['subscription']['entity']['id'];

        // Update your DB to mark subscription as active
        $stmt = $conn->prepare("UPDATE licenses SET is_active = 1 WHERE razorpay_subscription_id = ?");
        $stmt->bind_param("s", $subscriptionId);
        $stmt->execute();
    }

    // === Handle Subscription Cancelled ===
    if ($event === 'subscription.cancelled') {
        $subscriptionId = $data['payload']['subscription']['entity']['id'];

        // Mark license as inactive
        $stmt = $conn->prepare("UPDATE licenses SET is_active = 0 WHERE razorpay_subscription_id = ?");
        $stmt->bind_param("s", $subscriptionId);
        $stmt->execute();
    }

    // === Handle Payment Captured ===
    if ($event === 'payment.captured') {
        $paymentId = $data['payload']['payment']['entity']['id'];
        $subscriptionId = $data['payload']['payment']['entity']['subscription_id'];

        // You can log this in a separate payments table if needed
    }

    http_response_code(200);
    echo "Webhook received";
} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
    http_response_code(400);
    echo 'Invalid signature: ' . $e->getMessage();
}
