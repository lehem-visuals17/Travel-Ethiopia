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
    <title>Ethiopian Destinations</title>
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
        <!-- In destinations.php -->
<a href="add_destination.php" class="btn-primary">
    <i class="fa-solid fa-plus"></i> Add Destination
</a>
</div>

    <div class="dest-grid">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="dest-card">
            <div class="card-img-area">
                <img src="uploads/<?php echo $row['image']; ?>">
                <span class="type-badge"><?php echo $row['type']; ?></span>
            </div>
            
            <div class="card-body">
                <div class="title-row">
                    <h3><?php echo $row['name']; ?></h3>
                    <span class="rating-pill"><i class="fa-solid fa-star"></i> <?php echo $row['rating']; ?></span>
                </div>
                <p class="tagline-text"><?php echo $row['tagline']; ?></p>
                <p class="loc-text"><i class="fa-solid fa-location-dot"></i> <?php echo $row['region']; ?></p>
                
                <div class="price-box">
                    <div class="price-item"><span>Budget</span> <strong>$<?php echo $row['budget_cost']; ?></strong></div>
                    <div class="price-item"><span>Standard</span> <strong>$<?php echo $row['standard_cost']; ?></strong></div>
                    <div class="price-item luxury"><span>Luxury</span> <strong>$<?php echo $row['luxury_cost']; ?></strong></div>
                </div>

                <div class="card-footer">
    <a href="edit_destination.php?id=<?php echo $row['id']; ?>" class="btn-edit-small">
        <i class="fa-regular fa-pen-to-square"></i> Edit
    </a>

    <a href="delete_destination.php?id=<?php echo $row['id']; ?>"
       class="btn-delete-small"
       onclick="return confirm('Are you sure you want to delete this destination?');">
        <i class="fa-regular fa-trash-can"></i> Delete
    </a>
</div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>


<script src="https://jsdelivr.net"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_destination.php?id=' + id;
        }
    })
}
</script>

</body>
</html>
