<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db.php');

// Make sure your login.php sets $_SESSION['user_id'] = $row['id'];
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// JOIN the users table (u) with the guides table (g) using users.id = guides.user_id
$query = "SELECT u.username, u.role, u.profile_pic, g.rating, g.image 
          FROM users u 
          LEFT JOIN guides g ON u.id = g.user_id 
          WHERE u.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Logic for the profile image: 
// 1. Use guide table image 2. Use user table profile_pic 3. Use default
$avatar = '../assets/default-avatar.jpg';
if (!empty($user_data['image'])) {
    $avatar = $user_data['image'];
} elseif (!empty($user_data['profile_pic'])) {
    $avatar = $user_data['profile_pic'];
}
$guide_id = $u_id; // Assuming user_id is the same as guide_id or linked

// 1. Completed Tours
$comp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$guide_id' AND travel_date < CURDATE()"));
// 2. Upcoming (30 Days)
$upc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$guide_id' AND travel_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)"));
// 3. Earnings
$earn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM payments p JOIN bookings b ON p.booking_id=b.id WHERE b.guide_id='$guide_id' AND p.status='completed'"));
// 4. Review Count
$rev_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reviews WHERE guide_id='$guide_id'"));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Guide Dashboard</title>
        <link rel="stylesheet" href="sidebar.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>

    <body>
<div class="main-layout">
    <!-- 1. Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- 2. Main Content Area -->
    <div class="content-area">
        <header>
            <h1>Dashboard</h1>
            <p>Welcome back! Here's your overview</p>
        </header>

        <!-- Stats Grid (The 4 Cards) -->
  <div class="stats-grid">
    <!-- Card 1 -->
    <div class="stat-card">
        <i class="fa-solid fa-circle-check"></i>
        <div class="card-label">Total Tours Completed</div>
        <h2><?= $comp['total'] ?></h2>
        <small>Lifetime tours</small>
    </div>

    <!-- Card 2 -->
    <div class="stat-card">
        <i class="fa-solid fa-calendar-days"></i>
        <div class="card-label">Upcoming Bookings</div>
        <h2><?= $upc['total'] ?></h2>
        <small>Next 30 days</small>
    </div>

    <!-- Card 3 -->
    <div class="stat-card">
        <i class="fa-solid fa-dollar-sign"></i>
        <div class="card-label">Total Earnings</div>
        <h2>$<?= number_format($earn['total'] ?? 0) ?></h2>
        <small>All time</small>
    </div>

    <!-- Card 4 -->
    <div class="stat-card">
        <i class="fa-solid fa-star"></i>
        <div class="card-label">Reviews</div>
        <h2><?= $rev_count['total'] ?></h2>
        <small>Total reviews received</small>
    </div>
</div>


        <!-- Lists Grid (Recent Bookings & Reviews) -->
        <div class="lists-container">
            <div class="data-box">
                <h3>Recent Bookings</h3>
                <!-- Loop your recent bookings here as done before -->
            </div>
            <div class="data-box">
                <h3>Recent Reviews</h3>
                <!-- Loop your recent reviews here as done before -->
            </div>
        </div>
    </div>
</div>
    </body></html>