<?php
require(__DIR__ . '/../helpers/base_URL.php');

$baseURL = get_base_URL();
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="index.php">
            <img src="<?= $baseURL ?>assets/img/logo.webp" alt="Logo" style="width: 100px;">
        </a>

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Menu -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="#">
                                Welcome <span class="text-primary"><?= $_SESSION['user']['name'] ?></span>,
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">logout</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item d-flex align-items-center mx-2">
                            <i class="bi bi-phone" style="font-size: 20px"></i> <a class="nav-link" href="mailto:niyooshawebsites@gmail.com"> +919205504115</a>
                        </li>
                        <li class="nav-item d-flex align-items-center mx-2">
                            <i class="bi bi-envelope" style="font-size: 20px"></i> <a class="nav-link" href="mailto:niyooshawebsites@gmail.com"> niyooshawebsites@gmail.com</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-warning text-dark" href="https://niyooshawebsitesllp.in/">Main Website</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-primary" href="index.php">Register and Start FREE Trial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>