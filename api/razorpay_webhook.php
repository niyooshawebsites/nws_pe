<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../db/db.php';
require __DIR__ . '/../config.php';

use Razorpay\Api\WebhookSignature;

header('Content-Type: application/json');

// Get webhook body and headers
$body = file_get_contents('php://input');
$headers = getallheaders();
$razorpaySignature = $headers['X-Razorpay-Signature'] ?? '';

try {
    // 🔐 Verify the signature
    WebhookSignature::verify($body, $razorpaySignature, $webhookSecret);

    $data = json_decode($body, true);
    $event = $data['event'];

    // === ✅ Handle Subscription Activated ===
    if ($event === 'subscription.activated') {
        $subscriptionId = $data['payload']['subscription']['entity']['id'];

        $stmt = $conn->prepare("UPDATE licenses SET is_active = 1 WHERE razorpay_subscription_id = ?");
        $stmt->bind_param("s", $subscriptionId);
        $stmt->execute();
    }

    // === ❌ Handle Subscription Cancelled ===
    if ($event === 'subscription.cancelled') {
        $subscriptionId = $data['payload']['subscription']['entity']['id'];

        $stmt = $conn->prepare("UPDATE licenses SET is_active = 0 WHERE razorpay_subscription_id = ?");
        $stmt->bind_param("s", $subscriptionId);
        $stmt->execute();
    }

    // === 💳 Handle Payment Captured ===
    if ($event === 'payment.captured') {
        $payment = $data['payload']['payment']['entity'];
        $paymentId = $payment['id'];
        $subscriptionId = $payment['subscription_id'];
        $amount = $payment['amount'] / 100; // convert paise to rupees
        $currency = $payment['currency'];
        $paidAt = date('Y-m-d H:i:s', $payment['created_at']);
        $status = $payment['status']; // typically "captured"

        // 🔍 Get license and user
        $stmt = $conn->prepare("SELECT id, user_id, expiry_date FROM licenses WHERE razorpay_subscription_id = ?");
        $stmt->bind_param("s", $subscriptionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $licenseId = $row['id'];
            $userId = $row['user_id'];
            $currentExpiry = $row['expiry_date'];

            // 📅 Extend expiry from current expiry date (not today's date)
            $newExpiry = date('Y-m-d', strtotime('+1 year', strtotime($currentExpiry)));

            // ✅ Update license expiry
            $updateStmt = $conn->prepare("UPDATE licenses SET expiry_date = ?, is_active = 1 WHERE id = ?");
            $updateStmt->bind_param("si", $newExpiry, $licenseId);
            $updateStmt->execute();

            // ✅ Insert into payments table
            $insertPayment = $conn->prepare("INSERT INTO payments (
                user_id,
                razorpay_payment_id,
                razorpay_subscription_id,
                status,
                amount,
                currency,
                paid_at,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

            $insertPayment->bind_param(
                "isssiss",
                $userId,
                $paymentId,
                $subscriptionId,
                $status,
                $amount,
                $currency,
                $paidAt
            );
            $insertPayment->execute();
        }
    }

    // ✅ Respond with success
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Webhook processed successfully']);
} catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid webhook signature: ' . $e->getMessage()]);
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Webhook error: ' . $ex->getMessage()]);
}
