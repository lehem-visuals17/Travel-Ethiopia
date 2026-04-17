<?php
// Database connection
$conn = new mysqli("localhost", "root","","travel_db");

// Fetch all users
$query = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($query);
$user_count = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Connect your CSS file here -->
    <link rel="stylesheet" href="users.css">
    <?php include('includes/dashboard.php'); ?>

    <!-- If you are using Font Awesome, it goes here too -->
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
   

<div class="user-management-container">
    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p>Manage all users and their permissions</p>
        </div>
        <!-- The Add User Button -->
        <a href="add_user.php" class="btn-add-user">
            <i class="fa-solid fa-user-plus"></i> Add User
        </a>
    </div>

    <div class="search-section">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search users by name or email...">
        </div>
    </div>

    <div class="table-card">
        <h3>All Users (<?php echo $user_count; ?>)</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bookings</th>
                        <th>Total Spent</th>
                        <th>Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['name']; ?></strong></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td>
                            <select class="role-select">
                                <option <?php if($row['role'] == 'Customer') echo 'selected'; ?>>Customer</option>
                                <option <?php if($row['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                            </select>
                        </td>
                        <td><span class="status-pill active"><?php echo $row['status']; ?></span></td>
                        <td><?php echo $row['bookings_count']; ?></td>
                        <td>$<?php echo number_format($row['total_spent']); ?></td>
                        <td><?php echo date('m/d/y', strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>