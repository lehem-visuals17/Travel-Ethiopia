<?php
$conn = new mysqli("localhost","root","","travel_db");
$pageTitle = "Tour Guides";

$query = "SELECT g.*, d.name AS destination_name
          FROM guides g
          LEFT JOIN destinations d ON g.destination_id = d.id
          ORDER BY g.id DESC";

$result = $conn->query($query);
?>
<doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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
        <!-- In destinations.php -->
<a href="add_guide.php" class="btn-primary">
    <i class="fa-solid fa-plus"></i>Add Guide
</a>
</div>
<div class="guide-grid">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="guide-card">
        <img src="../uploads/<?php echo $row['image']; ?>" class="guide-img">

        <h3><?php echo $row['name']; ?></h3>

        <div class="guide-rating">
            ⭐ <?php echo $row['rating']; ?>
        </div>

        <p><strong>Contact</strong></p>
        <p><?php echo $row['phone']; ?></p>

        <p><strong>Languages</strong></p>
        <span class="tag"><?php echo $row['language']; ?></span>

        <p><strong>Experience</strong></p>
        <p><?php echo $row['experience_years']; ?> years</p>

        <p><strong>Destination</strong></p>
        <p><?php echo $row['destination_name']; ?></p>

        <div class="guide-actions">
             <a href="edit_guide.php?id=<?php echo $row['id']; ?>" class="btn-edit-small">
        <i class="fa-regular fa-pen-to-square"></i> Edit
    </a>
    <a href="delete_guide.php?id=<?php echo $row['id']; ?>"
       class="btn-delete-small"
       onclick="return confirm('Are you sure you want to delete this destination?');">
        <i class="fa-regular fa-trash-can"></i> Delete
    </a>
        </div>
    </div>
<?php endwhile; ?>
</div></body></html>