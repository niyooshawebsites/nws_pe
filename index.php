<?php
session_start();

// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
include "api/create_subscription_and_account_and_license.php";

use Razorpay\Api\Api;

// the key and secreet are coming from config.php
$api = new Api($razorpay_key, $razorpay_secret);

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace('/[^0-9]/', '', $_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$name || !$email || !$phone || !$password) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "Enter a valid 10-digit phone number.";
    } else {
        // ✅ Call and catch errors from your function
        try {
            create_subscription_and_account_and_license($name, $email, $phone, $password, $api);
            $success = "Your trial has started! Please <a href='login'>login here</a>.";
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<?php include "comps/header.php"; ?>
<?php include "comps/navbar.php"; ?>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center align-items-start min-vh-100">
            <div class="row justify-content-center align-items-center">
                <?php include 'comps/hero.php' ?>
                <div class="col-md-8">
                    <p class="lead">Get the full PROPERTY EXPERT experience for</p>
                    <p class="lead text-danger">@ less than the cost of a cup of coffee per day!!!</p>
                    <h1 class="display-1 lead text-success">Pay ₹0 today</h1>
                    <h5>₹666.67 INR per month, billed annually at ₹7,999/year after your 14-day trial.</h5>
                    <h5>Cancel anytime.</h5>
                    <p>A powerful SAAP tailored for property dealers, landlords, buyers, and tenants. Easily list, buy, sell, or rent properties with image galleries, and custom search features. Ideal for streamlining real estate operations.</p>
                    <ul>
                        <li>Supports Buyers, Sellers, Landlords and Tenants</li>
                        <li>Supports Property Listings with images</li>
                        <li>Smart property suggestions to Buyers and Tenants</li>
                        <li>WhatsApp support quick messaging</li>
                        <li>Social share for maximum reach</li>
                        <li>Advanced filtering system</li>
                        <li>Admin dashboard with listing approval functionality</li>
                        <li>User dashboard for listing and sharing property requirements</li>
                        <li>Loan Interest Calculator</li>
                        <li>PW App for Android and IOS devices</li>
                        <li>15+ languages support</li>
                        <li>FREE Installation</li>
                    </ul>
                    <span class="text-danger">* You have your Domain</span>
                </div>
                <div class="col-md-4">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= implode('<br>', $errors) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $success ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <div class="card shadow-sm">
                        <div class="card-header text-center bg-danger text-white">
                            <h3>Get 14-Day Free Trial</h3>
                            <p class="mb-0">Auto-renews at ₹7,999/year</p>
                        </div>
                        <div class="card-body">
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
                                    <button type="submit" class="btn btn-danger">Subscribe and Start Free Trial</button>
                                </div>
                                <p class="text-center text-muted mt-2">If you are already SUBSCRIBED. <br /> Login to check your license details <a href="login">Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "comps/footer.php"; ?>
</body>

</html>