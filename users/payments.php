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

if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
$user_id = $_SESSION['user_id'];

// 1. Fetch Summary Totals
$stats_query = $conn->prepare("SELECT 
    SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_paid,
    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as balance 
    FROM payments WHERE user_id = ?");
$stats_query->bind_param("i", $user_id);
$stats_query->execute();
$stats = $stats_query->get_result()->fetch_assoc();

// 2. Fetch Payment History (Joining with packages/bookings for the title)
$sql = "SELECT p.*, pk.title 
        FROM payments p 
        LEFT JOIN bookings b ON p.booking_id = b.id
        LEFT JOIN packages pk ON b.package_id = pk.id
        WHERE p.user_id = ? 
        ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$history = $stmt->get_result();
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


<div class="content-body">
    <div class="payment-section">
        <h2>Payment Information</h2>
        <!-- Top Summary Cards -->
        <div class="pay-stats-grid">
            <div class="pay-card blue-bg">
                <span>Total Paid</span>
                <h3>$<?php echo number_format($stats['total_paid'] ?? 0); ?></h3>
            </div>
            <div class="pay-card yellow-bg">
                <span>Remaining Balance</span>
                <h3>$<?php echo number_format($stats['balance'] ?? 0); ?></h3>
            </div>
            <div class="pay-card green-bg">
                <span>Refunds</span>
                <h3>$0</h3>
            </div>
        </div>

        <h2 style="margin-top:40px;">Payment History</h2>
        <div class="payment-list">
            <?php while($row = $history->fetch_assoc()): ?>
                <div class="payment-item">
                    <div class="pay-info">
                        <strong><?php echo htmlspecialchars($row['title'] ?? 'Travel Booking'); ?></strong>
                        <small><?php echo htmlspecialchars($row['method']); ?></small>
                        <p><?php echo date("M j, Y", strtotime($row['created_at'])); ?></p>
                        <span class="pay-amount">$<?php echo number_format($row['amount']); ?></span>
                    </div>
                    
                    <div class="pay-status-actions">
                        <span class="status-pill <?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                        <a href="receipt.php?id=<?php echo $row['id']; ?>" class="btn-receipt">
                            <i class="fa-solid fa-download"></i> Receipt
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

</body></html>