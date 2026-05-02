<?php
$conn = new mysqli("localhost","root","","travel_db");
$pageTitle = "Tour Guides";

$query = "SELECT g.*, d.name AS destination_name, 
          (SELECT status FROM guide_availability 
           WHERE guide_id = g.id AND available_date = CURDATE() 
           LIMIT 1) AS current_status
          FROM guides g
          LEFT JOIN destinations d ON g.destination_id = d.id
          ORDER BY g.id DESC";

$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="guide.css">
    <link rel="stylesheet" href="modal.css">
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dest-header">
    <div>
        <h1>Tour Guide Management</h1>
        <p>Manage tour guides and their assignments</p>
    </div>
    <a href="add_guide.php" class="btn-primary"><i class="fa-solid fa-plus"></i>Add Guide</a>
</div>

<div class="guide-grid">
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <?php 
            // MOVE LOGIC INSIDE THE LOOP
            $status = $row['current_status'] ?? null; 
            $label = "Schedule Not Set";
            $class = "status-none";

            if ($status === 'available') {
                $label = "Available Today";
                $class = "status-available";
            } elseif ($status === 'booked') {
                $label = "Booked";
                $class = "status-booked";
            }
        ?>

        <div class="guide-card">
            <div class="image-container" style="position: relative;">
                <img src="../uploads/<?php echo $row['image']; ?>" class="guide-img">
                <span class="availability-tag <?php echo $class; ?>">
                    <?php echo $label; ?>
                </span>
            </div>
        
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <div class="guide-rating">⭐ <?php echo $row['rating']; ?></div>

            <p><strong>Contact</strong></p>
            <p><?php echo htmlspecialchars($row['phone']); ?></p>

            <p><strong>Languages</strong></p>
            <span class="tag"><?php echo htmlspecialchars($row['language']); ?></span>

            <p><strong>Experience</strong></p>
            <p><?php echo $row['experience_years']; ?> years</p>

            <p><strong>Destination</strong></p>
            <p><?php echo htmlspecialchars($row['destination_name']); ?></p>

            <div class="guide-actions">
                <a href="edit_guide.php?id=<?php echo $row['id']; ?>" class="btn-edit-small">Edit</a>
                <a href="delete_guide.php?id=<?php echo $row['id']; ?>" class="btn-delete-small" onclick="return confirm('Delete this guide?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No guides found.</p>
<?php endif; ?>
</div>
</body>
</html>
