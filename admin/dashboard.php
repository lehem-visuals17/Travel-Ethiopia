<?php

session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

include "../db.php";

$pageTitle = "Dashboard";

$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments")->fetch_assoc()['total'] ?? 0;
$active_destinations = $conn->query("SELECT COUNT(*) as count FROM destinations")->fetch_assoc()['count'];

$user_growth = "+12%";

/* Most Popular Destination */
$popular_dest = $conn->query("
    SELECT 
        d.name,
        d.region,
        d.image,
        d.rating,
        COUNT(b.id) AS total_bookings
    FROM destinations d
    LEFT JOIN bookings b ON d.id = b.destination_id
    GROUP BY d.id
    ORDER BY total_bookings DESC
    LIMIT 1
")->fetch_assoc();

/* Most booked package */
$top_package = $conn->query("
    SELECT 
        p.title,
        p.duration,
        p.price,
        p.image,
        COUNT(b.id) AS total_sales
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
        b.created_at,
        b.status,
        b.total_price,
        u.fullname AS user_name,
        p.title AS package_name
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    LEFT JOIN packages p ON b.package_id = p.id
    ORDER BY b.created_at DESC
    LIMIT 5
");

/* New users */
$new_users = $conn->query("
    SELECT id, fullname, email, role, created_at
FROM users
    ORDER BY created_at DESC
    LIMIT 5
");

$pending_bookings = $conn->query("
    SELECT COUNT(*) as count 
    FROM bookings 
    WHERE status='pending'
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

<?php include "layout.php"; ?>

<div class="header-main">
    <h1 class="dynamic-title"><?php echo $pageTitle; ?></h1>

    <div class="user-controls">
        <div class="profile">
            <img src="admin/admin.jpg" alt="Admin">
            <div class="profile-info">
                <span class="user-name">Admin User <i class="arrow">▼</i></span>
                <span class="user-role">Super Admin</span>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <h1 class="welcome-msg">Welcome back, Admin!</h1>
    <p class="sub-text">Here's what's happening with your travel business today.</p>

    <div class="stats-grid">
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

    <!-- Most Popular -->
    <div class="info-card">
        <div class="card-title">
            <i class="fa-solid fa-location-dot"></i> Most Popular Destination
        </div>

        <div class="item-detail">
            <img src="../uploads/<?php echo htmlspecialchars($popular_dest['image']); ?>" class="thumb">
            <div class="item-text">
                <h3><?php echo $popular_dest['name']; ?></h3>
                <p><?php echo $popular_dest['region']; ?></p>
                <span class="rating">⭐ <?php echo $popular_dest['rating']; ?></span><br>
                <small><?php echo $popular_dest['total_bookings']; ?> bookings</small>
            </div>
        </div>
    </div>

    <!-- Most booked package -->
    <div class="info-card">
        <div class="card-title">
            <i class="fa-solid fa-box"></i> Most Booked Package
        </div>

        <div class="item-detail">
            <img src="../uploads/<?php echo htmlspecialchars($top_package['image']); ?>" class="thumb">
            <div class="item-text">
                <h3><?php echo $top_package['title']; ?></h3>
                <p><?php echo $top_package['duration']; ?></p>
                <span class="price">$<?php echo number_format($top_package['price']); ?></span><br>
                <small><?php echo $top_package['total_sales']; ?> bookings</small>
            </div>
        </div>
    </div>

    <!-- Recent bookings -->
    <div class="info-card">
        <div class="card-title">
            <i class="fa-solid fa-calendar-check"></i> Recent Bookings
        </div>

        <?php while($row = $recent_bookings->fetch_assoc()): ?>
        <div class="list-item">
            <div>
                <strong><?php echo $row['user_name']; ?></strong><br>
                <small><?php echo $row['package_name']; ?></small>
            </div>
            <div>
                <span class="price-small">$<?php echo number_format($row['total_price']); ?></span><br>
                <span class="status-pill <?php echo $row['status']; ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </div>
        </div>
        <?php endwhile; ?>

    </div>

</div>

<div class="users-section">
    <div class="card-title">
        <i class="fa-solid fa-users"></i> User Management
    </div>

    <table class="user-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php while($user = $new_users->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['fullname']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
    <span class="role-badge role-<?php echo strtolower($user['role']); ?>">
        <?php echo ucfirst($user['role']); ?>
    </span>
</td>
                <td><?php echo date("n/j/Y", strtotime($user['created_at'])); ?></td>
                <td>
    <span class="status-badge status-<?php echo strtolower($user['status'] ?? 'active'); ?>">
        <?php echo ucfirst($user['status'] ?? 'active'); ?>
    </span>
</td>
                <td class="action-icons">
    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit" title="Edit">
        <i class="fa fa-pen"></i>
    </a>

    <a href="reset_password.php?id=<?php echo $user['id']; ?>" class="btn-password" title="Reset Password">
        <i class="fa fa-key"></i>
    </a>

    <a href="change_role.php?id=<?php echo $user['id']; ?>" class="btn-role" title="Change Role">
        <i class="fa fa-user-shield"></i>
    </a>

    <a href="toggle_user.php?id=<?php echo $user['id']; ?>" class="btn-status" title="Suspend/Activate">
        <i class="fa fa-user-lock"></i>
    </a>

    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete"
       onclick="return confirm('Delete this user?')" title="Delete">
        <i class="fa fa-trash"></i>
    </a>
</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

    <div class="activity-summary-card">
        <div class="summary-header">
            <i class="fa-solid fa-bolt-lightning"></i>
            <span>System Activity Summary</span>
        </div>

        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number"><?php echo $pending_bookings; ?></div>
                <div class="summary-label">Pending Bookings</div>
            </div>

            <div class="summary-item">
                <div class="summary-number"><?php echo $active_destinations; ?></div>
                <div class="summary-label">Active Destinations</div>
            </div>

            <div class="summary-item">
                <div class="summary-number"><?php echo $total_tour_packages; ?></div>
                <div class="summary-label">Tour Packages</div>
            </div>

            <div class="summary-item">
                <div class="summary-number"><?php echo $active_users; ?></div>
                <div class="summary-label">Active Users</div>
            </div>
        </div>
    </div>


</body>
</html>