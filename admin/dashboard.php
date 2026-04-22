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

<div class="dashboard-content">
    <h1 class="welcome-msg">Welcome back, Admin!</h1>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <span>Total Users</span>
            <h2><?php echo $total_users; ?></h2>
        </div>

        <div class="stat-card">
            <span>Total Bookings</span>
            <h2><?php echo $total_bookings; ?></h2>
        </div>

        <div class="stat-card">
            <span>Total Revenue</span>
            <h2>$<?php echo number_format($total_revenue); ?></h2>
        </div>

        <div class="stat-card">
            <span>Active Destinations</span>
            <h2><?php echo $active_destinations; ?></h2>
        </div>
    </div>

    <!-- RECENT USERS (VIEW ONLY) -->
    <div class="users-section">
    <div class="card-title">
        <i class="fa-solid fa-users"></i> Recent Users
    </div>

    <div class="table-scroll">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php while($user = $new_users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>

                    <td>
                        <span class="role-badge role-<?php echo strtolower($user['role']); ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>

                    <td>
                        <span class="status-badge status-<?php echo strtolower($user['status'] ?? 'active'); ?>">
                            <?php echo ucfirst($user['status'] ?? 'active'); ?>
                        </span>
                    </td>

                    <td><?php echo date("n/j/Y", strtotime($user['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- SYSTEM SUMMARY -->
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

</div>

</body>
</html>