<?php
session_start();

// Enable error reporting for development (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "api/verify_login.php";

$error = "";

// Sanitize redirect target
$redirectAfterLogin = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';
$redirectAfterLogin = filter_var($redirectAfterLogin, FILTER_SANITIZE_URL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate email
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "Please enter a valid email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // ✅ Verify credentials using secure login function
        $user = loginUser($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;

            // Redirect safely
            $redirectPath = (!empty($redirectAfterLogin) &&
                strpos($redirectAfterLogin, '/') === 0 &&
                !preg_match('/^https?:\/\//', $redirectAfterLogin))
                ? $redirectAfterLogin
                : "dashboard/license.php";

            header("Location: " . $redirectPath);
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>



<?php include 'comps/header.php'; ?>

<body class="app-background text-white">
    <?php include 'comps/navbar.php' ?>

    <div class="container py-5">
        <div class="row justify-content-center align-items-start vh-100">
            <div class="row justify-content-center align-items-center">
                <div class="mb-5">
                    <h1 class="text-center display-1 text-primary">Property Expert Applicaton</h1>
                    <p class="text-center lead text-muted">Take your property business to the next level!</p>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow border-sucsess">
                        <div class="card-header bg-success text-white text-center">
                            <h4>Login</h4>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <!-- ✅ Hidden redirect field -->
                                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirectAfterLogin) ?>">

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" name="email" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center">
                            <small>Don't have an account? <a href="index.php">Register here</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'comps/footer.php' ?>
</body>

</html>