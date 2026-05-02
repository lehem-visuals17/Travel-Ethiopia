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
        <div class="sidebar">
    <div class="profile-section">
        <img src="../assets/avatar.jpg" class="profile-img">
        <h3><?php echo $_SESSION['username']; ?></h3>
        <span class="rating">★ 4.9</span>
    </div>

    <nav class="nav-menu">
         <a href="../index.php" style="color: #0061f2; font-weight: bold;">
        <i class="fa-solid fa-arrow-left"></i> Back to Site
    </a>
        <!-- Active logic: Highlights if the current page matches the link -->
        <a href="dashboard.php" class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="booking.php" class="nav-item <?= ($current_page == 'booking.php') ? 'active' : ''; ?>">
            <i class="far fa-calendar-check"></i>My Bookings
        </a><a href="Tours.php" class="nav-item <?= ($current_page == 'Tours.php') ? 'active' : ''; ?>">
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
       
        <!-- Add other links similarly -->
    </nav>

    <div class="logout-section">
        <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

    </body>
</html>
