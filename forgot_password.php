<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

$success = $_SESSION['otp_sent'] ?? '';
unset($_SESSION['otp_sent']);
?>

<?php include 'comps/header.php'; ?>

<body class="app-background text-white">
    <?php include 'comps/navbar.php' ?>

    <div class="container py-5">
        <div class="row justify-content-center align-items-start vh-100">
            <div class="row justify-content-center align-items-center">
                <?php include 'comps/hero.php' ?>
                <div class="col-md-6">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <div class="card shadow border-light">
                        <div class="card-header bg-primary text-white text-center">
                            <h4>Forgot Password?</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="helpers/send_otp.php">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Enter your email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Send OTP</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'comps/footer.php' ?>
</body>

</html>