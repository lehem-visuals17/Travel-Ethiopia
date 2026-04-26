<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Packages";
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Using the Public Package CSS for the Admin cards -->
    <link rel="stylesheet" href="package.css"> 
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        /* Admin-specific layout adjustments */
        .admin-controls { display: flex; justify-content: space-between; padding: 20px 40px; margin-top: 130px; }
        .package-grid { margin-top: 20px; } /* Override margin for admin */
        .admin-actions { padding: 15px; display: flex; gap: 10px; background: #f4f4f4; border-top: 1px solid #ddd; }
        .btn-edit { background: #4CAF50; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
        .btn-delete { background: #f44336; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="admin-controls">
        <h1>Manage Tour Packages</h1>
        <a href="add_package.php" class="login-pill" style="text-decoration:none;">+ Create New</a>
    </div>

    <section class="packages">
        <div class="package-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div class="fade-slider" style="height:180px;">
                        <img src="../uploads/<?php echo $row['image']; ?>" class="fade-img">
                    </div>
                    <div class="card-content">
                        <h3><?php echo $row['title']; ?></h3>
                        <div class="budget">$<?php echo number_format($row['price']); ?></div>
                        
                        <div class="admin-actions">
                            <a href="edit_packages.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen"></i></a>
                            <a href="delete_package.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>