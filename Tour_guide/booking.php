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
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'All';

// 1. Get counts for the tabs
$counts = [
    'All' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$u_id'"))['total'],
    'Pending' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$u_id' AND status='Pending'"))['total'],
    'Confirmed' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$u_id' AND status='Confirmed'"))['total'],
    'Completed' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$u_id' AND status='Completed'"))['total'],
    'Cancelled' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id='$u_id' AND status='Cancelled'"))['total'],
];

// 2. Fetch bookings based on selected tab
$query = "SELECT * FROM bookings WHERE guide_id = '$u_id'";
if ($status_filter !== 'All') {
    $query .= " AND status = '$status_filter'";
}
$query .= " ORDER BY travel_date DESC";
$bookings_res = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link rel="stylesheet" href="sidebar.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="main-layout">
    <?php include('sidebar.php'); ?>

    <div class="content-area">
        <header class="page-header">
            <h1>My Bookings</h1>
            <p>Manage your tour bookings and assignments</p>
        </header>

        <!-- Status Tabs -->
        <div class="filter-tabs">
            <?php foreach ($counts as $label => $count): ?>
                <a href="?status=<?= $label ?>" class="tab <?= $status_filter == $label ? 'active' : '' ?>">
                    <?= $label ?> (<?= $count ?>)
                </a>
            <?php endforeach; ?>
        </div>

        <div class="booking-section">
            <h3>Booking Requests</h3>
            <p class="sub-text">View and manage your tour bookings</p>

            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Tourist Name</th>
                        <th>Destination</th>
                        <th>Date</th>
                        <th>People</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($bookings_res)): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['tourist_name']) ?></strong></td>
                        <td><?= htmlspecialchars($row['destination']) ?></td>
                        <td><?= date('M d, Y', strtotime($row['travel_date'])) ?></td>
                        <td><i class="fa-solid fa-users"></i> <?= $row['people_count'] ?></td>
                        <td>$<?= number_format($row['amount']) ?></td>
                        <td>
                            <span class="status-badge <?= strtolower($row['status']) ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="action-btns">
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="update_status.php?id=<?= $row['id'] ?>&s=Confirmed" class="btn-check"><i class="fa-solid fa-check"></i></a>
                                <a href="update_status.php?id=<?= $row['id'] ?>&s=Cancelled" class="btn-cancel"><i class="fa-solid fa-xmark"></i></a>
                            <?php elseif($row['status'] == 'Confirmed'): ?>
                                <button class="btn-complete">Mark Complete</button>
                            <?php endif; ?>
                            <a href="view_booking.php?id=<?= $row['id'] ?>" class="btn-view"><i class="fa-regular fa-eye"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
