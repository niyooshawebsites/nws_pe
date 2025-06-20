<?php include 'comps/header.php'; ?>

<body class="app-background text-white">
    <?php include 'comps/navbar.php' ?>

    <div class="container py-5">
        <div class="d-flex flex-column justify-content-start align-items-between vh-100">
            <?php include 'comps/hero.php' ?>

            <div>
                <h2 class="mb-4 text-center text-dark">Gallery</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    <!-- Image Card -->
                    <div class="col">
                        <div class="card">
                            <img src="https://niyooshawebsitesllp.in/wp-content/uploads/2025/06/Homepage.png" class="card-img-top" alt="Image 1">
                        </div>
                    </div>
                    <!-- Repeat for more images -->
                    <div class="col">
                        <div class="card">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Image 2">
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Image 3">
                        </div>
                    </div>
                    <!-- Add more as needed -->
                </div>
            </div>
        </div>
    </div>
    <?php include 'comps/footer.php' ?>
</body>

</html>