<?php
require 'vendor/autoload.php';
require 'config.php';

use Razorpay\Api\Api;

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$name || !$email || !$phone || !$password) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already registered. Please log in.";
        } else {
            try {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $password_hash]);
                $user_id = $pdo->lastInsertId();

                $license_key = strtoupper(md5(uniqid($user_id . microtime(), true)));
                $start_date = date('Y-m-d');
                $expiry_date = date('Y-m-d', strtotime('+14 days'));

                $api = new Api($razorpay_key, $razorpay_secret);
                $subscription = $api->subscription->create([
                    'plan_id' => 'plan_XXXXXX', // Replace with your actual plan ID
                    'customer_notify' => 1,
                    'total_count' => 12,
                    'quantity' => 1,
                    'trial_days' => 14,
                    'notes' => [
                        'user_id' => $user_id,
                        'email' => $email
                    ]
                ]);

                $stmt = $pdo->prepare("INSERT INTO licenses (user_id, license_key, start_date, expiry_date, is_active, razorpay_subscription_id, trial_used) VALUES (?, ?, ?, ?, 1, ?, 1)");
                $stmt->execute([$user_id, $license_key, $start_date, $expiry_date, $subscription->id]);

                $success = "Registration successful! Your license key: <strong>{$license_key}</strong>";
            } catch (Exception $e) {
                $errors[] = "Something went wrong: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Buy PHP App – 14 Day Free Trial</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy PHP App – 14 Day Free Trial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Lightbox2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card shadow-sm">
                    <div class="card-header text-center bg-success text-white">
                        <h3>Get 14-Day Free Trial</h3>
                        <p class="mb-0">Auto-renews at ₹8000/year</p>
                    </div>

                    <div class="card-body">

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?= implode('<br>', $errors) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Create Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Start Free Trial</button>
                                </div>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>