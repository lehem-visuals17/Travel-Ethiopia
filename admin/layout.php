<?php
// Get the current filename (e.g., 'users.php')
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Ethiopia Tours Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
</head>
<body>
    <div class="sidebar">
    <div class="logo">
        <h2><i class="fa fa-plane"></i>Betora Travels</h2>
        <p>Admin Panel</p>
        
    </div>
    <hr style="height: 0.5px; background-color: #ccc; border: none; width: 150px;">

    <ul class="sidebar-menu">
    <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
        <a href="dashboard.php">Dashboard</a>
    </li>
    <li class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
        <a href="users.php">Users</a>
    </li>
    <li class="<?php echo ($current_page == 'destinations.php') ? 'active' : ''; ?>">
        <a href="destinations.php">Destinations</a>
    </li>
    <li class="<?php echo ($current_page == 'guides.php') ? 'active' : ''; ?>">
        <a href="guides.php">Tour Guides</a>
    </li>
    <li class="<?php echo ($current_page == 'packages.php') ? 'active' : ''; ?>">
        <a href="packages.php">Packages</a>
    </li>
    <li class="<?php echo ($current_page == 'bookings.php') ? 'active' : ''; ?>">
        <a href="bookings.php">Bookings</a>
    </li>
     <li class="<?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
        <a href="services.php">Services</a>
    </li>
     <li class="<?php echo ($current_page == 'payments.php') ? 'active' : ''; ?>">
        <a href="payments.php">Payments</a>
    </li>
     <li class="<?php echo ($current_page == 'reviews.php') ? 'active' : ''; ?>">
        <a href="reviews.php">Reviews</a>
    </li>
     <li class="<?php echo ($current_page == 'experience.php') ? 'active' : ''; ?>">
        <a href="experience.php">Experience</a>
    </li>
    <li class="<?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>">
    <a href="blog.php">Blog</a>
    </li>
    <li class="<?php echo ($current_page == 'deals.php') ? 'active' : ''; ?>">
    <a href="deals.php">Deals</a>
    </li>
     
    <!-- Repeat for the rest of your links... -->
</ul>

</div>

<div class="topbar">
    <h1><?php echo $pageTitle; ?></h1>
    
    

     <div class="admin-info">
             <div class="notification-container">
    <!-- Using Font Awesome for the bell icon -->
    <i class="fa-regular fa-bell"></i>
    <span class="badge">5</span>
</div>
        <img src="admin\admin.jpg" alt="Admin">

        <div class="admin-text">
            <span class="admin-name">Admin User</span>
            <span class="admin-role">Super Admin</span>
        </div>
    </div>
   
    
</div>

</body>