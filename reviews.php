<?php
$users = [
    ["name" => "Amit Sharma", "city" => "Rohini"],
    ["name" => "Rakesh Gupta", "city" => "Lajpat Nagar"],
    ["name" => "Sanjay Mehra", "city" => "Dwarka"],
    ["name" => "Nitin Bansal", "city" => "Karol Bagh"],
    ["name" => "Pradeep Yadav", "city" => "Mayur Vihar"],
    ["name" => "Kunal Tiwari", "city" => "Preet Vihar"],
    ["name" => "Manish Saxena", "city" => "Vasant Kunj"],
    ["name" => "Ravi Chauhan", "city" => "Nehru Place"],
    ["name" => "Deepak Saini", "city" => "Janakpuri"],
    ["name" => "Rohit Arora", "city" => "Kalkaji"],
    ["name" => "Puneet Verma", "city" => "Model Town"],
    ["name" => "Yogesh Tyagi", "city" => "Paschim Vihar"],
    ["name" => "Vinay Bhardwaj", "city" => "Tilak Nagar"],
    ["name" => "Jitender Malik", "city" => "Nangloi"],
    ["name" => "Sourabh Mishra", "city" => "Shahdara"],
    ["name" => "Ashish Ahuja", "city" => "Saket"],
    ["name" => "Mohit Sagar", "city" => "Chattarpur"],
    ["name" => "Rajeev Kaushik", "city" => "Rajouri Garden"],
    ["name" => "Harsh Singh", "city" => "Laxmi Nagar"],
    ["name" => "Tarun Bhatia", "city" => "Patel Nagar"],
    ["name" => "Neeraj Kumar", "city" => "Malviya Nagar"],
    ["name" => "Vipul Goel", "city" => "Ashok Vihar"],
    ["name" => "Siddharth Joshi", "city" => "Dilshad Garden"],
    ["name" => "Rajat Kapoor", "city" => "Trilokpuri"],
    ["name" => "Anil Rathi", "city" => "Bhajanpura"],
    ["name" => "Gaurav Rana", "city" => "Geeta Colony"],
    ["name" => "Sumit Sehgal", "city" => "Govindpuri"],
    ["name" => "Tushar Bhatt", "city" => "Green Park"],
    ["name" => "Aditya Puri", "city" => "Saraswati Vihar"],
    ["name" => "Keshav Tyagi", "city" => "Shalimar Bagh"],
    ["name" => "Devang Rawat", "city" => "Munirka"],
    ["name" => "Ajay Saini", "city" => "Burari"],
    ["name" => "Rahul Nanda", "city" => "Vikaspuri"],
    ["name" => "Lakshay Jain", "city" => "Uttam Nagar"],
    ["name" => "Sahil Malhotra", "city" => "Sonia Vihar"],
    ["name" => "Abhinav Gupta", "city" => "Madangir"],
    ["name" => "Vaibhav Aggarwal", "city" => "Narela"],
    ["name" => "Kartik Dubey", "city" => "Bhalswa"],
    ["name" => "Mayank Chawla", "city" => "Seelampur"],
    ["name" => "Varun Bhalla", "city" => "Yamuna Vihar"],
    ["name" => "Arjun Rana", "city" => "Palam"],
    ["name" => "Deepanshu Jain", "city" => "Jangpura"],
    ["name" => "Ritik Dahiya", "city" => "Vasant Vihar"],
    ["name" => "Naman Singh", "city" => "GTB Nagar"],
    ["name" => "Harshit Mehta", "city" => "Pitampura"],
    ["name" => "Bhavesh Sinha", "city" => "Okhla"],
    ["name" => "Raj Malhotra", "city" => "Sadar Bazaar"],
    ["name" => "Devendra Tyagi", "city" => "Ghaziabad"]
];

$comments = [
    "Finally, an app that understands how real estate works in Delhi.",
    "Love the WhatsApp feature. Makes client communication super easy.",
    "I like the clean UI and quick property uploads.",
    "Buyers love the image galleries. Great tool.",
    "App is smooth, responsive, and really helpful.",
    "Sharing property on social media got me more leads.",
    "Perfect for dealers who manage rentals and sales.",
    "Loan calculator closed 2 deals last month!",
    "Multilingual support is a blessing for my clients.",
    "Lead quality has improved a lot with this app."
];

shuffle($users);

$reviews = [];
foreach ($users as $user) {
    $reviews[] = [
        'name' => $user['name'],
        'city' => $user['city'],
        'rating' => number_format(mt_rand(46, 50) / 10, 1),
        'monthsAgo' => rand(1, 12),
        'message' => $comments[array_rand($comments)],
        'purchased' => true
    ];
}

// Pagination settings
$perPage = 6;
$total = count($reviews);
$totalPages = ceil($total / $perPage);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalPages));
$start = ($page - 1) * $perPage;
$paginatedReviews = array_slice($reviews, $start, $perPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niyoosha Websites - Property Expert Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Lightbox2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.4/dist/css/lightbox.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="manifest" href="/manifest.json">

    <style>
        .review-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ccc;
            display: inline-block;
        }

        .purchased-badge {
            background-color: #000;
            color: #fff;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 6px;
        }

        .star {
            color: #ffc107;
        }

        .fs-sm {
            font-size: 0.9rem;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'comps/navbar.php' ?>
    <div class="container py-5 min-vh-100">

        <?php include 'comps/hero.php' ?>
        <h4 class="mb-4">Customer Reviews</h4>

        <?php foreach ($paginatedReviews as $review): ?>
            <div class="d-flex mb-4 bg-white p-3 rounded shadow-sm">
                <div class="me-3">
                    <div class="review-avatar d-flex align-items-center justify-content-center text-white bg-secondary fw-bold">
                        <?= strtoupper(substr($review['name'], 0, 1)) ?>
                    </div>
                </div>
                <div>
                    <strong><?= htmlspecialchars($review['name']) ?> <span class="text-muted fs-sm">(<?= htmlspecialchars($review['city']) ?>)</span></strong>
                    <span class="purchased-badge">PURCHASED</span>
                    <small class="text-muted ms-2"><?= $review['monthsAgo'] ?> months ago</small>
                    <div class="mt-1 mb-1">
                        <?php for ($i = 0; $i < floor($review['rating']); $i++) echo '<span class="star">★</span>'; ?>
                        <span class="text-muted">(<?= $review['rating'] ?>)</span>
                    </div>
                    <p class="mb-0"><?= htmlspecialchars($review['message']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">&laquo; Prev</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next &raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php include 'comps/footer.php' ?>
</body>

</html>