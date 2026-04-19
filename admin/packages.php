<?php
$conn = new mysqli("localhost","root","","travel_db");
$pageTitle = "packages";
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>


<doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="package.css">
    <link rel="stylesheet" href="modal.css">
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dest-header">
        <div>
            <h1>Tour packages</h1>
            <p>Manage travel packages and deals</p>
        </div>
        <!-- In destinations.php -->
<a href="add_package.php" class="btn-primary">
    <i class="fa-solid fa-plus"></i> Create Package
</a>
</div>
  <div class="package-grid">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="package-card">

        <div class="package-image">
            <img src="../uploads/<?php echo $row['image']; ?>">

            <?php if($row['featured']==1): ?>
<span class="featured-badge">
    <i class="fa-solid fa-star"></i> Featured
</span>
<?php endif; ?>

            <span class="type-badge">
                <?php echo ucfirst($row['type']); ?>
            </span>
        </div>

        <div class="package-content">
            <h3><?php echo $row['title']; ?></h3>

            <p class="desc">
                <?php echo $row['description']; ?>
            </p>

            <div class="package-meta">
                <span><i class="fa-regular fa-clock"></i> <?php echo $row['duration']; ?></span>
                <span><i class="fa-solid fa-users"></i> Max <?php echo $row['max_people']; ?></span>
            </div>

            <div class="price">
                $<?php echo number_format($row['price']); ?>
                <small>per person</small>
            </div>

            <hr class="package-line">

<div class="featured-row">
    <span>Featured</span>

    <label class="switch">
        <input type="checkbox" <?php if($row['featured']==1) echo "checked"; ?> disabled>
        <span class="slider"></span>
    </label>
</div>

          
             <div class="package-actions">
             <a href="edit_packages.php?id=<?php echo $row['id']; ?>" class="btn-edit-small">
        <i class="fa-regular fa-pen-to-square"></i> Edit
    </a>
    <a href="delete_package.php?id=<?php echo $row['id']; ?>"
       class="btn-delete-small"
       onclick="return confirm('Are you sure you want to delete this package?');">
        <i class="fa-regular fa-trash-can"></i> Delete
    </a>
        </div>
        </div>

    </div>
<?php endwhile; ?>
</div>

</body></html>