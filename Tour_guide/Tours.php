<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$guide_id = $_SESSION['user_id'];

// Query adjusted for your specific column names: region, type, average_cost
$query = "SELECT b.id as booking_id, d.name as tour_name, d.region, d.description, 
                 d.type as category, d.average_cost as price
          FROM bookings b
          JOIN destinations d ON b.destination_id = d.id
          WHERE b.guide_id = ? AND b.status != 'Cancelled'
          GROUP BY b.destination_id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tours - Guide Dashboard</title>
    <link rel="stylesheet" href="sidebar.css">
    
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>

<div class="main-layout">
    <?php include('sidebar.php'); ?>

    <div class="content-area">
        <header class="page-header">
            <div class="header-text">
                <h1>My Tours</h1>
                <p>Destinations and tours you are assigned to</p>
            </div>
        </header>

        <div class="tours-grid">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="tour-card">
                <div class="card-top">
                    <div class="title-section">
                        <h3><?= htmlspecialchars($row['tour_name']) ?></h3>
                        <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($row['region'] ?? 'Ethiopia') ?></span>
                    </div>
                    <!-- 'type' column from your DB used as the category tag -->
                    <span class="category-tag"><?= htmlspecialchars($row['category'] ?? 'General') ?></span>
                </div>

                <p class="description">
                    <?= htmlspecialchars(substr($row['description'] ?? 'No description available.', 0, 100)) ?>...
                </p>

                <div class="tour-meta">
                    <!-- Defaulting to 2 days as duration isn't in your destinations table -->
                    <span><i class="fa-regular fa-clock"></i> 2 days</span> 
                    <span><i class="fa-solid fa-dollar-sign"></i> <?= number_format($row['price'] ?? 0) ?></span>
                </div>

                <div class="assignment-info">
                    <small>Assigned by: <strong>Admin</strong></small>
                </div>

                <div class="card-actions">
                    <a href="view_tour.php?id=<?= $row['booking_id'] ?>" class="btn-outline">View Details</a>
                    <a href="edit_tour_notes.php?id=<?= $row['booking_id'] ?>" class="btn-outline">Edit</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-data">
            <p>You currently have no assigned tours.</p>
        </div>
    <?php endif; ?>
</div>
    </div>
</div>

</body>
</html>