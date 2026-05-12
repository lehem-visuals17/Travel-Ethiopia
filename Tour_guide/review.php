<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('../db.php');

// 1. SECURITY & ID MAPPING
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// Get the actual Guide ID from the guides table using the logged-in User ID
$guide_lookup = mysqli_query($conn, "SELECT id FROM guides WHERE user_id = '$u_id'");
$guide_data = mysqli_fetch_assoc($guide_lookup);
$guide_id = $guide_data['id'] ?? 0;

// 2. STATS CALCULATIONS
// Overall Rating & Total Reviews
$stats_query = mysqli_query($conn, "SELECT AVG(rating) as avg_rating, COUNT(*) as total_rev FROM reviews WHERE guide_id = '$guide_id'");
$stats = mysqli_fetch_assoc($stats_query);
$avg_rating = number_format($stats['avg_rating'] ?? 0, 1);
$total_reviews = $stats['total_rev'];

// Rating Distribution (1 to 5 stars)
$distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$dist_query = mysqli_query($conn, "SELECT FLOOR(rating) as star_level, COUNT(*) as count FROM reviews WHERE guide_id = '$guide_id' GROUP BY star_level");
while($row = mysqli_fetch_assoc($dist_query)) {
    $level = (int)$row['star_level'];
    if($level >= 1 && $level <= 5) {
        $distribution[$level] = $row['count'];
    }
}

// 3. FETCH RECENT REVIEWS
$reviews_query = "
    SELECT r.*, u.fullname, u.nationality, u.profile_pic 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.guide_id = ? 
    ORDER BY r.created_at DESC";

$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$recent_reviews = $stmt->get_result();

// 4. STAR RATING FUNCTION
function renderStars($rating) {
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) {
            $output .= '<i class="fas fa-star" style="color: #ff9800;"></i>';
        } elseif ($rating > ($i - 1) && $rating < $i) {
            $output .= '<i class="fas fa-star-half-alt" style="color: #ff9800;"></i>';
        } else {
            $output .= '<i class="far fa-star" style="color: #ccc;"></i>';
        }
    }
    return $output;
}
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
                <div class="stars">
                    <?= renderStars($avg_rating) ?>
                </div>
                <small style="display:block; margin-top:10px;"><?= $total_reviews ?> reviews in total</small>
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
                        <span style="width: 30px;"><?= $i ?> ★</span>
                        <div class="bar-bg">
                            <div class="bar-fill" style="width: <?= $pct ?>%;"></div>
                        </div>
                        <span style="width: 20px; text-align: right;"><?= $distribution[$i] ?></span>
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
