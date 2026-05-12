<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Destinations";

$query = "SELECT * FROM destinations ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Destinations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="modal.css">
    <?php include "layout.php"; ?>
</head>
<body>

<div class="dest-container">

    <div class="dest-header">
        <div>
            <h1>Ethiopian Destinations</h1>
            <p>Manage your tourism catalog</p>
        </div>

        <a href="add_destination.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add Destination
        </a>
    </div>

    <div class="dest-grid">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="dest-card">

            <div class="card-img-area">
                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="">
                <span class="type-badge"><?php echo ucfirst($row['type']); ?></span>
            </div>

            <div class="card-body">
                <div class="title-row">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <span class="rating-pill">
                        <i class="fa-solid fa-star"></i>
                        <?php echo $row['rating']; ?>
                    </span>
                </div>

                <p class="tagline-text"><?php echo htmlspecialchars($row['tagline']); ?></p>

                <p class="loc-text">
                    <i class="fa-solid fa-location-dot"></i>
                    <?php echo htmlspecialchars($row['region']); ?>
                </p>

                <p class="best-time">
                    <i class="fa-solid fa-calendar"></i>
                    Best time: <?php echo htmlspecialchars($row['best_time']); ?>
                </p>

                <div class="price-box">
                    <div class="price-item">
                        <span>Budget</span>
                        <strong>$<?php echo $row['budget_cost']; ?></strong>
                    </div>
                    <div class="price-item">
                        <span>Standard</span>
                        <strong>$<?php echo $row['standard_cost']; ?></strong>
                    </div>
                    <div class="price-item luxury">
                        <span>Luxury</span>
                        <strong>$<?php echo $row['luxury_cost']; ?></strong>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="view_destination.php?id=<?php echo $row['id']; ?>" class="btn-view-small">
                        <i class="fa-solid fa-eye"></i> Details
                    </a>
                    <a href="edit_destination.php?id=<?php echo $row['id']; ?>" class="btn-edit-small">
                        <i class="fa-regular fa-pen-to-square"></i> Edit
                    </a>
                    <a href="delete_destination.php?id=<?php echo $row['id']; ?>" 
                       class="btn-delete-small" 
                       onclick="return confirm('Delete this destination?');">
                        <i class="fa-regular fa-trash-can"></i> Delete
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
