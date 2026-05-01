<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="profile-section">
        <img src="../assets/avatar.jpg" class="profile-img">
        <h3><?php echo $_SESSION['username']; ?></h3>
        <span class="rating">★ 4.9</span>
    </div>

    <nav class="nav-menu">
        <!-- Active logic: Highlights if the current page matches the link -->
        <a href="dashboard.php" class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="profile.php" class="nav-item <?= ($current_page == 'profile.php') ? 'active' : ''; ?>">
            <i class="far fa-user"></i> Profile
        </a>
        <!-- Add other links similarly -->
    </nav>

    <div class="logout-section">
        <a href="../logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
