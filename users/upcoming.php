<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
$user_id = $_SESSION['user_id'];
$current_date = date('Y-m-d');

// SQL Query joining bookings with packages/details
// Adjust column names (guide_name, hotel_name, pickup_location) to match your actual schema
$sql = "SELECT b.*, p.title, p.guide_name, p.guide_phone, p.hotel_name, p.pickup_location 
        FROM bookings b 
        JOIN packages p ON b.package_id = p.id 
        WHERE b.user_id = ? AND b.travel_date >= ?
        ORDER BY b.travel_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_date);
$stmt->execute();
$result = $stmt->get_result();



if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
$user_id = $_SESSION['user_id'];

// SQL Query joining bookings with packages to get the title and type
$sql = "SELECT b.*, p.title, p.type 
        FROM bookings b 
        LEFT JOIN packages p ON b.package_id = p.id 
        WHERE b.user_id = ? 
        ORDER BY b.travel_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


 // Path to your db connection

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
</div>

  <?php
// Get current filename to handle the underline
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Scrolling Navigation -->
<div class="nav-scroll-container">
    <nav class="profile-nav">
       <a href="../index.php" style="color: #0061f2; font-weight: bold;">
        <i class="fa-solid fa-arrow-left"></i> Back to Site
    </a>
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
</div>


<div class="content-body">
    <div class="upcoming-container">
        <h2>Upcoming Trips</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="upcoming-card">
                    <div class="card-header">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <span class="trip-date"><?php echo date("F j, Y", strtotime($row['travel_date'])); ?></span>
                    </div>

                    <div class="trip-details">
                        <div class="detail-item">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            <span><strong>Guide:</strong> <?php echo htmlspecialchars($row['guide_name'] ?? 'To be assigned'); ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-hotel"></i>
                            <span><strong>Hotel:</strong> <?php echo htmlspecialchars($row['hotel_name'] ?? 'TBD'); ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fa-solid fa-car"></i>
                            <span><strong>Pickup:</strong> <?php echo htmlspecialchars($row['pickup_location'] ?? 'Contact office'); ?></span>
                        </div>
                    </div>

                    <!-- Contact Button -->
                    <a href="tel:<?php echo $row['guide_phone']; ?>" class="btn-contact-guide">
                        <i class="fa-solid fa-phone"></i> Contact Guide: <?php echo htmlspecialchars($row['guide_phone'] ?? '+251 ...'); ?>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No upcoming adventures found. Time to plan one!</p>
                <a href="../packages.php" class="btn-primary">Explore Packages</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body></html>