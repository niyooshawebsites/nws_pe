<?php

require(__DIR__ . '/../db/db.php');
require(__DIR__ . '/../config.php');

function create_subscription_and_account_and_license($name, $email, $phone, $password, $api)
{
    global $conn;

    try {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception("Your account already exists. Please login to continue!");
        }

        // Create Razorpay customer
        $customer = $api->customer->create([
            'name' => $name,
            'email' => $email,
            'contact' => $phone
        ]);

        // Create Razorpay plan (in real-world, create once and reuse it)
        $plan = $api->plan->create([
            'period' => 'yearly',
            'interval' => 1,
            'item' => [
                'name' => 'Property Expert App Annual Plan',
                'description' => 'Annual subscription with 14-day trial',
                'amount' => 200,
                'currency' => 'INR'
            ]
        ]);

        // Create subscription with 14-day trial
        $startAt = time() + (14 * 24 * 60 * 60); // 14-day trial
        $subscription = $api->subscription->create([
            'plan_id' => $plan->id,
            'customer_notify' => 1,
            'total_count' => 12,
            'quantity' => 1,
            'customer_id' => $customer->id,
            'start_at' => $startAt,
            'notes' => ['app' => 'Property Expert'],
            'notify_info' => ['email' => $email, 'phone' => $phone],
        ]);

        // Save user
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);
        $stmt->execute();
        $user_id = $stmt->insert_id;

        // Generate secure license key
        $license = strtoupper(bin2hex(random_bytes(16)));

        // Calculate dates
        $start_date = date('Y-m-d', time());
        $expiry_date = date('Y-m-d', strtotime("+1 year", $startAt));

        // Insert license
        $stmt = $conn->prepare("INSERT INTO licenses (user_id, license_key, start_date, expiry_date, is_active, razorpay_subscription_id, razorpay_customer_id, trial_used) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $active = 1;
        $trial = 1;
        $stmt->bind_param("issssssi", $user_id, $license, $start_date, $expiry_date, $active, $subscription->id, $customer->id, $trial);
        $stmt->execute();

        // Save subscription ID and user info in session:
        $_SESSION['subscription_id'] = $subscription->id;
        $_SESSION['customer_name'] = $name;
        $_SESSION['customer_email'] = $email;
        $_SESSION['customer_phone'] = $phone;

        // Redirect to checkout page (your own)
        header("Location: razorpay-subscribe.php");
        exit;
    } catch (Exception $e) {
        // Just rethrow so it can be caught in the calling script
        throw new Exception($e->getMessage());
    }
}
