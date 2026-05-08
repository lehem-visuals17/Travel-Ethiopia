<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('../db.php');

$guide_id = $_SESSION['user_id'];

// 1. Fetch Overall Stats
$stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total_rev FROM reviews WHERE guide_id = '$guide_id'"));
$avg_rating = number_format($stats['avg_rating'] ?? 0, 1);
$total_reviews = $stats['total_rev'];

// 2. Fetch Rating Distribution (5 star, 4 star, etc.)
$distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$dist_query = mysqli_query($conn, "SELECT rating, COUNT(*) as count FROM reviews WHERE guide_id = '$guide_id' GROUP BY rating");
while($row = mysqli_fetch_assoc($dist_query)) {
    $distribution[(int)$row['rating']] = $row['count'];
}

// 3. Fetch Recent Reviews with User Details
$reviews_query = "SELECT r.*, u.fullname, u.nationality 
                  FROM reviews r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.guide_id = ? 
                  ORDER BY r.created_at DESC LIMIT 5";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$recent_reviews = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="sidebar.css">
    
</head>
<body>
<div class="main-layout">
    <?php include('sidebar.php'); ?>

    <div class="content-area">
        <header class="page-header">
            <h1>Reviews & Ratings</h1>
            <p>See what your clients say about you</p>
        </header>

        <div class="stats-row">
            <!-- Overall Rating Card -->
            <div class="rating-card overall">
                <h3>Overall Rating</h3>
                <p class="sub-text">Your average rating from all reviews</p>
                <div class="big-rating"><?= $avg_rating ?></div>
                <div class="stars">★★★★★</div>
                <small><?= $total_reviews ?> reviews</small>
            </div>

            <!-- Rating Distribution Card -->
            <div class="rating-card distribution">
                <h3>Rating Distribution</h3>
                <p class="sub-text">Breakdown of ratings received</p>
                <div class="dist-list">
                    <?php for($i=5; $i>=1; $i--): 
                        $pct = ($total_reviews > 0) ? ($distribution[$i] / $total_reviews) * 100 : 0;
                    ?>
                    <div class="dist-item">
                        <span><?= $i ?> ★</span>
                        <div class="bar-bg"><div class="bar-fill" style="width: <?= $pct ?>%;"></div></div>
                        <span><?= $distribution[$i] ?></span>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="reviews-section">
            <h3>Recent Reviews</h3>
            <p class="sub-text">Latest feedback from your clients</p>
            
            <?php while($rev = $recent_reviews->fetch_assoc()): ?>
            <div class="review-item">
    <!-- Letter Icon like the 'J' in the image -->
    <div class="rev-user-icon"><?= substr($rev['fullname'], 0, 1) ?></div>
    
    <div class="rev-content">
        <div class="rev-user-header">
            <div class="user-info">
                <h4><?= htmlspecialchars($rev['fullname']) ?></h4>
                <span><?= htmlspecialchars($rev['nationality'] ?? 'Global Traveler') ?></span>
            </div>
            <!-- Numeric Rating like the 5.0 in the image -->
            <div class="individual-stars">★ <?= number_format($rev['rating'], 1) ?></div>
        </div>

        <p class="rev-text"><?= htmlspecialchars($rev['comment']) ?></p>

        <div class="rev-footer-meta">
            <span>Lalibela Full Tour</span> <!-- Dynamic tour name if available -->
            <span>•</span>
            <span><?= date('M d, Y', strtotime($rev['created_at'])) ?></span>
        </div>
    </div>
</div>

            <?php endwhile; ?>
        </div>
    </div>
</div>
</body>
</html>
