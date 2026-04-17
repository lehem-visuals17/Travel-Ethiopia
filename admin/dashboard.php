<?php
include "../db.php";

$pageTitle = "Dashboard";
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments")->fetch_assoc()['total'];
$result = $conn->query("SELECT COUNT(*) as total FROM destinations");
$row = $result->fetch_assoc();
$active_destinations = $row['total'];// Placeholder for percentages (You can calculate these with more complex SQL)
$user_growth = "+12%"; 

$popular_dest = $conn->query("
    SELECT d.name, COUNT(b.id) AS total_bookings
    FROM destinations d
    LEFT JOIN bookings b ON d.id = b.destination_id
    GROUP BY d.id
    ORDER BY total_bookings DESC
    LIMIT 1
")->fetch_assoc();


/* Most booked package */
$top_package = $conn->query("
    SELECT p.title, COUNT(b.id) AS total_sales
    FROM packages p
    LEFT JOIN bookings b ON p.id = b.package_id
    GROUP BY p.id
    ORDER BY total_sales DESC
    LIMIT 1
")->fetch_assoc();


/* Recent bookings */
$recent_bookings = $conn->query("
    SELECT 
        b.id,
        b.travel_date,
        b.status,
        b.total_price,
        u.fullname,
        p.title
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    LEFT JOIN packages p ON b.package_id = p.id
    ORDER BY b.created_at DESC
    LIMIT 5
");


/* New users */
$new_users = $conn->query("
    SELECT fullname, email, role, created_at
    FROM users
    ORDER BY created_at DESC
    LIMIT 5
");


$pending_bookings = $conn->query("
    SELECT COUNT(*) as count 
    FROM bookings 
    WHERE status='pending'
")->fetch_assoc()['count'];

$active_destinations = $conn->query("
    SELECT COUNT(*) as count 
    FROM destinations
")->fetch_assoc()['count'];

$total_tour_packages = $conn->query("
    SELECT COUNT(*) as count 
    FROM packages
")->fetch_assoc()['count'];

$active_users = $conn->query("
    SELECT COUNT(*) as count 
    FROM users 
    WHERE role='customer'
")->fetch_assoc()['count'];

?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 
include "layout.php"; 
  "header.php"; ?>

 
<div class="header-main">
        <h1 class="dynamic-title"><?php echo $pageTitle; ?></h1>
        
        <div class="user-controls">
           
            <div class="profile">
                <img src="admin\admin.jpg" alt="Admin">
                <div class="profile-info">
                    
                    <span class="user-name">Admin User <i class="arrow">▼</i></span>
                    <span class="user-role">Super Admin</span>
                </div>
            </div>

<div class="dashboard-content">
    <h1 class="welcome-msg">Welcome back, Admin!</h1>
    <p class="sub-text">Here's what's happening with your travel business today.</p>

    <div class="stats-grid">
        <!-- Card 1: Users -->
        <div class="stat-card">
            <div class="card-header">
                <span>Total Users</span>
                <div class="icon-box blue"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="card-body">
                <h2><?php echo number_format($total_users); ?></h2>
                <span class="trend pos"><i class="fa-solid fa-arrow-trend-up"></i> <?php echo $user_growth; ?> from last month</span>
            </div>
        </div>

        <!-- Card 2: Bookings -->
        <div class="stat-card">
            <div class="card-header">
                <span>Total Bookings</span>
                <div class="icon-box green"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="card-body">
                <h2><?php echo number_format($total_bookings); ?></h2>
                <span class="trend pos"><i class="fa-solid fa-arrow-trend-up"></i> +8% from last month</span>
            </div>
        </div>

        <!-- Card 3: Revenue -->
        <div class="stat-card">
            <div class="card-header">
                <span>Total Revenue</span>
                <div class="icon-box orange"><i class="fa-solid fa-dollar-sign"></i></div>
            </div>
            <div class="card-body">
                <h2>$<?php echo number_format($total_revenue); ?></h2>
                <span class="trend pos"><i class="fa-solid fa-arrow-trend-up"></i> +23% from last month</span>
            </div>
        </div>

        <!-- Card 4: Destinations -->
        <div class="stat-card">
            <div class="card-header">
                <span>Active Destinations</span>
                <div class="icon-box purple"><i class="fa-solid fa-location-dot"></i></div>
            </div>
            <div class="card-body">
                <h2><?php echo $active_destinations; ?></h2>
                <span class="trend pos">+2 from last month</span>
            </div>
        </div>
    </div>

    <div class="secondary-grid">
    <!-- Most Popular Destination -->
    <div class="info-card">
        <div class="card-title"><i class="fa-solid fa-compass"></i> Most Popular Destination</div>
        <div class="item-detail">
            <img src="uploads/<?php echo $popular_dest; ?>" class="thumb">
            <div class="item-text">
                <h3><?php echo $popular_dest['name']; ?></h3>
                <p><?php echo $popular_dest['location']; ?></p>
                <span class="rating">⭐ 4.8 <span>(267 reviews)</span></span>
            </div>
        </div>
    </div>

    <!-- Most Booked Package -->
    <div class="info-card">
        <div class="card-title"><i class="fa-solid fa-box-archive"></i> Most Booked Package</div>
        <div class="item-detail">
            <img src="uploads/<?php echo $top_package; ?>" class="thumb">
            <div class="item-text">
                <h3><?php echo $top_package['name']; ?></h3>
                <p><?php echo $top_package['duration']; ?></p>
                <span class="price">$<?php echo number_format($top_package['price']); ?></span>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="list-card">
        <div class="card-title"><i class="fa-solid fa-calendar-day"></i> Recent Bookings</div>
        <?php while($row = $recent_bookings->fetch_assoc()): ?>
        <div class="list-item">
            <div>
                <strong><?php echo $row['user_name']; ?></strong><br>
                <small><?php echo $row['package_name']; ?></small><br>
                <small class="date"><?php echo date('n/j/Y', strtotime($row['created_at'])); ?></small>
            </div>
            <div class="status-col">
                <span class="price-small">$<?php echo $row['amount']; ?></span>
                <span class="status-pill <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- New User Registrations -->
    <div class="list-card">
        <div class="card-title"><i class="fa-solid fa-user-plus"></i> New User Registrations</div>
        <?php while($user = $new_users->fetch_assoc()): ?>
        <div class="list-item">
            <div class="user-avatar-small"><?php echo substr($user['name'], 0, 1); ?></div>
            <div class="user-meta">
                <strong><?php echo $user['name']; ?></strong><br>
                <small><?php echo $user['email']; ?></small>
            </div>
            <div class="user-badge-col">
                <span class="role-badge"><?php echo $user['role']; ?></span>
                <small><?php echo date('n/j/Y', strtotime($user['created_at'])); ?></small>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="activity-summary-card">
    <div class="summary-header">
        <i class="fa-solid fa-bolt-lightning"></i> 
        <span>System Activity Summary</span>
    </div>

    <div class="summary-grid">
        <!-- Pending Bookings -->
        <div class="summary-item">
            <div class="summary-number"><?php echo $pending_bookings; ?></div>
            <div class="summary-label">Pending Bookings</div>
        </div>

        <!-- Active Destinations -->
        <div class="summary-item">
            <div class="summary-number"><?php echo $active_destinations; ?></div>
            <div class="summary-label">Active Destinations</div>
        </div>

        <!-- Tour Packages -->
        <div class="summary-item">
            <div class="summary-number"><?php echo $total_tour_packages; ?></div>
            <div class="summary-label">Tour Packages</div>
        </div>

        <!-- Active Users -->
        <div class="summary-item">
            <div class="summary-number"><?php echo $active_users; ?></div>
            <div class="summary-label">Active Users</div>
        </div>
    </div>
</div>


</div>


</body>
</html>