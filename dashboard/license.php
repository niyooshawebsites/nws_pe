<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require(__DIR__ . '/../config.php');
include '../api/fetch_license_details.php';

$userId = $_SESSION['user']['id'];
$result = fetchLicenseDetails($userId);
?>

<?php include '../comps/header.php'; ?>

<body class="app-background text-white">
    <?php include '../comps/navbar.php'; ?>

    <div class="container py-5">
        <div class="vh-100">
            <!-- Heading row -->
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    <h2 class="text-primary">Your License Details</h2>
                </div>
            </div>

            <!-- License logic -->
            <div class="row justify-content-center">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php if (!empty($row['razorpay_subscription_id']) && !empty($row['razorpay_customer_id'])): ?>
                            <div class="col-md-6 col-lg-5 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-success text-white text-center">
                                        <strong>License Key:</strong> <?= htmlspecialchars($row['license_key'] ?? 'Not assigned') ?>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Domain:</strong> <?= htmlspecialchars($row['domain_name'] ?? 'Not assigned') ?></p>
                                        <p><strong>Start Date:</strong> <?= date('F j, Y', strtotime($row['start_date'])) ?></p>
                                        <p><strong>Expiry Date:</strong> <?= date('F j, Y', strtotime($row['expiry_date'])) ?></p>
                                        <p><strong>Status:</strong>
                                            <span class="badge <?= $row['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $row['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </p>
                                        <p><strong>Trial Used:</strong> <?= $row['trial_used'] ? 'Yes' : 'No' ?></p>
                                        <p><strong>Razorpay Subscription ID:</strong> <?= htmlspecialchars($row['razorpay_subscription_id']) ?></p>
                                        <p><strong>Razorpay Customer ID:</strong> <?= htmlspecialchars($row['razorpay_customer_id']) ?></p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="download.php" class="btn btn-primary" download>Download App</a>
                                    </div>
                                    <div class="card-footer text-muted text-center">
                                        Created: <?= date('F j, Y, g:i A', strtotime($row['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-danger text-center">
                                    You are not subscribed yet.<br>
                                    <a href="../index.php" class="btn btn-warning mt-3">Subscribe Now to Access App & License</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No license found for your account.<br>
                            <a href="../index.php" class="btn btn-warning mt-3">Subscribe Now</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include '../comps/footer.php'; ?>
</body>

</html>