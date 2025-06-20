<?php session_start(); ?>
<?php include 'comps/header.php'; ?>

<body class="app-background text-white">
    <?php include 'comps/navbar.php' ?>

    <div class="container py-5">
        <div class="row justify-content-center align-items-start vh-100">
            <div class="row justify-content-center align-items-center">
                <?php include 'comps/hero.php' ?>
                <div class="col-md-6">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow border-light">
                        <div class="card-header bg-primary text-white text-center">
                            <h4>Reset Password</h4>
                        </div>
                        <div class="card-body">
                            <form method="post" action="helpers/reset_password_action.php">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Reset Password</button>
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