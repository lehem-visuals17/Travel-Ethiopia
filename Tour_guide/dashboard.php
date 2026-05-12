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



// write the php+ the users table (u) with the guides table (g) using users.id = guides.user_id
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

$recent_rev_query = "SELECT r.*, u.fullname 
                     FROM reviews r 
                     JOIN users u ON r.user_id = u.id 
                     WHERE r.guide_id = ? 
                     ORDER BY r.created_at DESC 
                     LIMIT 2";
$stmt_rev = $conn->prepare($recent_rev_query);
$stmt_rev->bind_param("i", $guide_id);
$stmt_rev->execute();
$recent_reviews = $stmt_rev->get_result();
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
    
    <?php if ($recent_reviews && $recent_reviews->num_rows > 0): ?>
        <div class="reviews-list">
            <?php while($rev = $recent_reviews->fetch_assoc()): ?>
                <div class="review-item" style="padding: 12px 0; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: #333;">
                            <?= htmlspecialchars($rev['fullname']) ?>
                        </span>
                        <span style="color: #ff9800; font-weight: bold;">
                            <?= number_format($rev['rating'], 1) ?> ★
                        </span>
                    </div>
                    <p style="font-size: 0.9rem; color: #666; margin: 5px 0;">
                        "<?= htmlspecialchars(strlen($rev['comment']) > 70 ? substr($rev['comment'], 0, 70) . '...' : $rev['comment']) ?>"
                    </p>
                    <small style="color: #999; font-size: 0.75rem;">
                        <?= date('M d, Y', strtotime($rev['created_at'])) ?>
                    </small>
                </div>
            <?php endwhile; ?>
        </div>
        <div style="margin-top: 15px;">
            <a href="review.php" style="color: #ff9800; font-size: 0.85rem; text-decoration: none; font-weight: 600;">View All Reviews &rarr;</a>
        </div>
    <?php else: ?>
        <p style="color: #888; font-size: 0.9rem; padding: 20px 0;">No reviews received yet.</p>
    <?php endif; ?>
</div>
        </div>
    </div>
</div>
    </body></html>