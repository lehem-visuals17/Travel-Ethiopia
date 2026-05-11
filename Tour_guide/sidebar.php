<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// 1. Fetch data for sidebar
$query = "SELECT u.username, u.role, u.profile_pic, g.rating, g.image 
          FROM users u 
          LEFT JOIN guides g ON u.id = g.user_id 
          WHERE u.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// 2. Logic for the profile image (Same as your profile page)
$avatar = '../assets/default-avatar.jpg';
if (!empty($user_data['image'])) {
    $avatar = "../uploads/" . $user_data['image']; // Added ../uploads/ path
} elseif (!empty($user_data['profile_pic'])) {
    $avatar = "../uploads/" . $user_data['profile_pic']; // Added ../uploads/ path
}
?>

<!DOCTYPE html>
<html>
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Sidebar Layout</title>
      <link rel="stylesheet" href="sidebar.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    </head>

    <body>
        <div class="mobile-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </div>

    <div class="sidebar" id="sidebar">
        <div id="sidebarXBtn" class="mobile-close" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </div>
        
        <div class="profile-section">
            <!-- FIXED: Using the PHP $avatar variable here -->
            <img src="<?php echo $avatar; ?>" class="profile-img" onerror="this.src='../assets/default-avatar.jpg'">
            
            <h3><?php echo htmlspecialchars($user_data['username']); ?></h3>
            
            <!-- FIXED: Dynamic Rating from database -->
            <span class="rating">★ <?php echo number_format($user_data['rating'] ?? 0, 1); ?></span>
        </div>

        <nav class="nav-menu">
            <a href="../index.php" style="color: #0061f2; font-weight: bold;">
                <i class="fa-solid fa-arrow-left"></i> Back to Site
            </a>
            
            <a href="dashboard.php" class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="booking.php" class="nav-item <?= ($current_page == 'booking.php') ? 'active' : ''; ?>">
                <i class="far fa-calendar-check"></i> My Bookings
            </a>
            <a href="Tours.php" class="nav-item <?= ($current_page == 'Tours.php') ? 'active' : ''; ?>">
                <i class="fas fa-location-dot"></i> My Tours
            </a>
            <a href="review.php" class="nav-item <?= ($current_page == 'review.php') ? 'active' : ''; ?>">
                <i class="far fa-star"></i> Reviews
            </a>
            <a href="profile.php" class="nav-item <?= ($current_page == 'profile.php') ? 'active' : ''; ?>">
                <i class="far fa-user"></i> Profile
            </a>
            <a href="settings.php" class="nav-item <?= ($current_page == 'settings.php') ? 'active' : ''; ?>">
                <i class="fas fa-gear"></i> Settings
            </a>
        </nav>

        <div class="logout-section">
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }
</script>
</body>
</html>
