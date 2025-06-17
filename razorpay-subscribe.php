<?php
session_start();
require 'vendor/autoload.php';
require 'config.php'; // For $razorpay_key

$subscription_id = $_SESSION['subscription_id'];
$name = $_SESSION['customer_name'];
$email = $_SESSION['customer_email'];
$phone = $_SESSION['customer_phone'];
?>

<?php include 'comps/header.php'; ?>

<body>
    <?php include 'comps/navbar.php' ?>
    <h2 class="text-center mt-5">Please wait, redirecting to Razorpay...</h2>

    <?php include 'comps/footer.php' ?>

    <!-- ✅ Include Razorpay checkout script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- ✅ Razorpay checkout script -->
    <script>
        var options = {
            key: "<?= $razorpay_key ?>",
            subscription_id: "<?= $subscription_id ?>",
            name: "Property Expert App",
            description: "14-Day Free Trial + Annual Plan",
            image: "https://niyooshawebsitesllp.in/logo.png",
            prefill: {
                name: "<?= $name ?>",
                email: "<?= $email ?>",
                contact: "<?= $phone ?>"
            },
            theme: {
                color: "#3399cc"
            },
            handler: function(response) {
                // Redirect to dashboard after success
                window.location.href = "dashboard/license.php";
            }
        };

        // ✅ Trigger checkout popup on load
        window.onload = function() {
            var rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
</body>

</html>