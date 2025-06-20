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
                            <h4>Login</h4>
                        </div>
                        <div class="card-body">
                            <h1 class="heading-3">Page not found</h1>
                            <a class="btn btn-dark" href="./">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'comps/footer.php' ?>
</body>

</html>