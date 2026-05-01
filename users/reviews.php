<?php
session_start();
include '../db.php'; // Path to your db connection

// Security Check: Only customers allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['user_id'];

$current_date = date('Y-m-d');
$user_id = $_SESSION['user_id'];

// 1. Total Trips (All bookings)
$total_trips_query = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
$total_trips_query->bind_param("i", $user_id);
$total_trips_query->execute();
$total_trips = $total_trips_query->get_result()->fetch_assoc()['total'] ?? 0;

// 2. Upcoming Trips (Bookings with a travel date in the future)
// Note: Ensure your 'bookings' table has a 'travel_date' or 'start_date' column
$upcoming_query = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ? AND travel_date > ?");
$upcoming_query->bind_param("is", $user_id, $current_date);
$upcoming_query->execute();
$upcoming = $upcoming_query->get_result()->fetch_assoc()['total'] ?? 0;

// 3. Saved Destinations (Total from favorites table)
$favorites_query = $conn->prepare("SELECT COUNT(*) AS total FROM favorites WHERE user_id = ?");
$favorites_query->bind_param("i", $user_id);
$favorites_query->execute();
$favorites = $favorites_query->get_result()->fetch_assoc()['total'] ?? 0;

// 4. Reviews Given (Total from reviews table)
$reviews_query = $conn->prepare("SELECT COUNT(*) AS total FROM reviews WHERE user_id = ?");
$reviews_query->bind_param("i", $user_id);
$reviews_query->execute();
$reviews_count = $reviews_query->get_result()->fetch_assoc()['total'] ?? 0;

$user_id = $_SESSION['user_id'];

// 2. Fetch all user data from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// 3. Store the data in the $user_data variable
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    
    // Also update fullname in session just in case it changed
    $_SESSION['fullname'] = $user_data['fullname']; 
} else {
    die("User not found.");
}

$filter = isset($_GET['type']) ? $_GET['type'] : 'all';

// Base SQL joining only what we know exists
$sql = "SELECT r.*, 
               d.name as dest_name, 
               g.name as guide_name
        FROM reviews r
        LEFT JOIN destinations d ON r.destination_id = d.id
        LEFT JOIN guides g ON r.guide_id = g.id
        WHERE r.user_id = ?";

// Apply filters based on tab selection
if ($filter == 'destinations') {
    $sql .= " AND r.destination_id IS NOT NULL";
} elseif ($filter == 'guides') {
    $sql .= " AND r.guide_id IS NOT NULL";
} elseif ($filter == 'website') {
    $sql .= " AND r.destination_id IS NULL AND r.guide_id IS NULL AND r.package_id IS NULL";
}

$sql .= " ORDER BY r.created_at DESC";

// Now prepare the statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // This will tell you EXACTLY what is wrong with the SQL syntax
    die("SQL Error: " . $conn->error); 
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if (isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $rating  = $_POST['rating'];
    $comment = $_POST['comment'];
    $type    = $_POST['review_type'];
    
    $dest_id  = ($type == 'destination') ? $_POST['destination_id'] : null;
    $guide_id = ($type == 'guide') ? $_POST['guide_id'] : null;

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, destination_id, guide_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $user_id, $dest_id, $guide_id, $rating, $comment);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your review!'); window.location.href='reviews.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Ethiopia Tours</title>
    <link rel="stylesheet" href="profile.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

    
<header class="profile-header">
    <div class="header-container">
        
        <p>Manage your account and bookings</p>
    </div>
</header>


    <div class="main-container">
        <!-- Summary Stats Grid -->
        <div class="stats-grid">
    <div class="stat-card">
        <div class="icon-circle blue"><i class="fa-regular fa-calendar"></i></div>
        <div class="stat-info"><span>Total Trips</span><h3><?php echo $total_trips; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle light-blue"><i class="fa-regular fa-calendar-check"></i></div>
        <div class="stat-info"><span>Upcoming Trips</span><h3><?php echo $upcoming; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle purple"><i class="fa-regular fa-heart"></i></div>
        <div class="stat-info"><span>Saved Destinations</span><h3><?php echo $favorites; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle dark-blue"><i class="fa-regular fa-star"></i></div>
        <div class="stat-info"><span>Reviews Given</span><h3><?php echo $reviews_count; ?></h3></div>
    </div>
</div>


<!-- Scrolling Navigation -->
<div class="nav-scroll-container">
    <nav class="profile-nav">
        <a href="../index.php" style="color: #0061f2; font-weight: bold;">
        <i class="fa-solid fa-arrow-left"></i> Back to Site
    </a>
        <!-- 1. Overview -->
        <a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-user"></i> Overview
        </a>

        <!-- 2. Settings -->
        <a href="settings.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-gear"></i> Settings
        </a>

        <!-- 3. Booking History -->
        <a href="history.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-calendar-check"></i> Booking History
        </a>

        <!-- 4. Upcoming Trips -->
        <a href="upcoming.php" class="<?php echo ($current_page == 'upcoming.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-calendar"></i> Upcoming Trips
        </a>

        <!-- 5. Favorites -->
        <a href="favorites.php" class="<?php echo ($current_page == 'favorites.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-heart"></i> Favorites
        </a>

        <!-- 6. Saved Guides -->
        <a href="guides.php" class="<?php echo ($current_page == 'guides.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-id-badge"></i> Saved Guides
        </a>

        <!-- 7. Reviews -->
        <a href="reviews.php" class="<?php echo ($current_page == 'reviews.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-star"></i> Reviews
        </a>

        <!-- 8. Payments -->
        <a href="payments.php" class="<?php echo ($current_page == 'payments.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-credit-card"></i> Payments
        </a>

        <!-- 9. Notifications -->
        <a href="notifications.php" class="<?php echo ($current_page == 'notifications.php') ? 'active' : ''; ?>">
            <i class="fa-regular fa-bell"></i> Notifications
        </a>

        <!-- 10. Support -->
        <a href="support.php" class="<?php echo ($current_page == 'support.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-headset"></i> Support
        </a>

        <!-- 11. Rewards -->
        <a href="rewards.php" class="<?php echo ($current_page == 'rewards.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-award"></i> Rewards
        </a>  
    </nav>
</div></div>


<!-- ... Header and Nav ... -->

<div class="content-body">
    <!-- 1. Header Section with Action Button -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="margin:0;">My Reviews</h2>
        
        <button class="btn-write-review" onclick="openModal('modal-add-review')">
            <i class="fa-solid  fa-pen-nib"></i> Write a Review
        </button>
    </div>

    <!-- 2. Filtering Menus (Original Tabs) -->
    <div class="review-tabs">
        <a href="reviews.php?type=all" class="<?= $filter == 'all' ? 'active-tab' : '' ?>">All (<?= ($filter == 'all') ? $result->num_rows : '?' ?>)</a>
        <a href="reviews.php?type=destinations" class="<?= $filter == 'destinations' ? 'active-tab' : '' ?>">Destinations</a>
        <a href="reviews.php?type=guides" class="<?= $filter == 'guides' ? 'active-tab' : '' ?>">Guides</a>
        <a href="reviews.php?type=hotels" class="<?= $filter == 'hotels' ? 'active-tab' : '' ?>">Hotels</a>
        <a href="reviews.php?type=website" class="<?= $filter == 'website' ? 'active-tab' : '' ?>">Website</a>
    </div>

    <!-- 3. Dynamic Reviews List -->
    <div class="reviews-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="review-item-card">
                    <div class="review-header">
                        
                            <?php 
                                // Logic to determine icon based on category
                                if($row['dest_name']) { $icon = "fa-location-dot"; $name = $row['dest_name']; $type="Destination"; $color="#3b82f6"; }
                                elseif($row['guide_name']) { $icon = "fa-user"; $name = $row['guide_name']; $type="Guide"; $color="#22c55e"; }
                                else { $icon = "fa-globe"; $name = "Website Feedback"; $type="Website"; $color="#64748b"; }
                            ?>
                            <i class="fa-solid <?= $icon ?>" style="color: <?= $color ?>"></i>
                            <div>
                                <strong><?= htmlspecialchars($name) ?></strong>
                                <small><?= $type ?></small>
                            </div>
                        </div>
                        <span class="rev-date"><?= date("M j, Y", strtotime($row['created_at'])) ?></span>
                    </div>

                    <div class="rev-stars">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fa-solid fa-star <?= ($i <= $row['rating']) ? 'filled' : 'empty' ?>"></i>
                        <?php endfor; ?>
                    </div>

                    <p class="rev-comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align:center; padding:40px; color:#9ca3af;">
                <i class="fa-regular fa-comment-dots" style="font-size:40px; margin-bottom:10px; display:block;"></i>
                <p>No reviews found in this category.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ensure the Add Review Modal is also present at the end of this file -->


<!-- --- ADD REVIEW MODAL --- -->
<div id="modal-add-review" class="settings-modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal-add-review')">&times;</span>
        <h3>Share Your Experience</h3>
        <form action="save_review.php" method="POST">
            
            <label>What are you reviewing?</label>
            <select name="review_type" id="review_type" onchange="toggleReviewFields()" required>
                <option value="website">The Website</option>
                <option value="destination">A Destination</option>
                <option value="guide">A Tour Guide</option>
                <option value="hotel">A Hotel</option>
            </select>

            <!-- Dynamic Dropdowns (Hidden by default) -->
            <div id="dest_select" style="display:none;">
                <label>Select Destination</label>
                <select name="destination_id">
                    <?php 
                    $dests = $conn->query("SELECT id, name FROM destinations");
                    while($d = $dests->fetch_assoc()) echo "<option value='{$d['id']}'>{$d['name']}</option>";
                    ?>
                </select>
            </div>

            <div id="guide_select" style="display:none;">
                <label>Select Guide</label>
                <select name="guide_id">
                    <?php 
                    $guides = $conn->query("SELECT id, name FROM guides");
                    while($g = $guides->fetch_assoc()) echo "<option value='{$g['id']}'>{$g['name']}</option>";
                    ?>
                </select>
            </div>

            <label>Rating</label>
            <div class="star-rating-input">
                <input type="number" name="rating" min="1" max="5" value="5" required> <span>/ 5 Stars</span>
            </div>

            <label>Your Comment</label>
            <textarea name="comment" rows="4" placeholder="Write your feedback here..." required></textarea>

            <button type="submit" name="submit_review" class="btn-save">Post Review</button>
        </form>
    </div>
</div>


</div>
<script>
function toggleReviewFields() {
    const type = document.getElementById('review_type').value;
    document.getElementById('dest_select').style.display = (type === 'destination') ? 'block' : 'none';
    document.getElementById('guide_select').style.display = (type === 'guide') ? 'block' : 'none';
}

function openModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.style.display = "block";
    }
}

function closeModal(id) {
    var modal = document.getElementById(id);
    if (modal) {
        modal.style.display = "none";
    }
}

// Close modal if user clicks the dark background
window.onclick = function(event) {
    if (event.target.className === 'settings-modal') {
        event.target.style.display = "none";
    }
}

// Logic for the dropdowns inside the Review Modal
function toggleReviewFields() {
    const type = document.getElementById('review_type').value;
    document.getElementById('dest_select').style.display = (type === 'destination') ? 'block' : 'none';
    document.getElementById('guide_select').style.display = (type === 'guide') ? 'block' : 'none';
}
</script>

</body></html>