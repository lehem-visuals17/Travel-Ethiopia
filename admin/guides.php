<?php
$conn = new mysqli("localhost","root","","travel_db");

if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// DEBUG 1: How many users have the role 'tour_guide'?
$debug_role_check = $conn->query("SELECT count(*) as total FROM users WHERE role = 'tour_guide'");
$role_count = $debug_role_check->fetch_assoc()['total'];

// DEBUG 2: How many rows exist in the guides table?
$debug_guide_check = $conn->query("SELECT count(*) as total FROM guides");
$guide_count = $debug_guide_check->fetch_assoc()['total'];

// Replace your existing IF ($role_count == 0) block with this:
if ($role_count == 0) {
    echo "<div style='background:#fff3cd; color:#856404; padding:15px; border:1px solid #ffeeba; margin:10px;'>
            <strong>Notice:</strong> No users found with the role 'tour_guide'. 
            Current Guide Profile Count: $guide_count.
          </div>";
}


$pageTitle = "Tour Guides";
$query = "SELECT u.id as user_id, u.username, u.fullname, u.phone as u_phone, 
                 g.id as guide_id, g.language, g.experience_years, g.rating, g.image, g.phone as g_phone,
                 d.name as destination_name,
                 av.status as current_status
          FROM users u
          LEFT JOIN guides g ON u.id = g.user_id
          LEFT JOIN destinations d ON g.destination_id = d.id
          LEFT JOIN guide_availability av ON g.id = av.guide_id AND av.available_date = CURDATE()
          WHERE u.role = 'tour_guide'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
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
    <a href="add_guide.php" class="btn-primary"><i class="fa-solid fa-plus"></i> Add Guide</a>
</div>

<div class="guide-grid">
<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <?php 
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
            
            // Handle image path
            $imagePath = !empty($row['image']) ? "../uploads/" . $row['image'] : "../assets/default-avatar.png";
        ?>

        <div class="guide-card">
            <div class="image-container" style="position: relative;">
                <img src="<?php echo $imagePath; ?>" class="guide-img" alt="Guide Photo">
                <span class="availability-tag <?php echo $class; ?>">
                    <?php echo $label; ?>
                </span>
            </div>
        
            <!-- Changed 'name' to 'fullname' to match the users table -->
            <h3><?php echo htmlspecialchars($row['fullname'] ?? $row['username']); ?></h3>
            <div class="guide-rating">⭐ <?php echo $row['rating'] ?? '0.0'; ?></div>

            <div class="guide-info">
                <p><strong><i class="fa fa-phone"></i> Contact</strong></p>
                <p><?php echo htmlspecialchars($row['u_phone'] ?? $row['g_phone'] ?? 'N/A'); ?></p>

                <p><strong><i class="fa fa-language"></i> Languages</strong></p>
                <span class="tag"><?php echo htmlspecialchars($row['language'] ?? 'Not Specified'); ?></span>

                <p><strong><i class="fa fa-briefcase"></i> Experience</strong></p>
                <p><?php echo $row['experience_years'] ?? '0'; ?> years</p>

                <p><strong><i class="fa fa-map-marker-alt"></i> Destination</strong></p>
                <p><?php echo htmlspecialchars($row['destination_name'] ?? 'Unassigned'); ?></p>
            </div>

            <div class="guide-actions">
                <!-- Use guide_id for editing/deleting -->
               <a href="edit_guide.php?id=<?php echo $row['guide_id']; ?>" class="btn-edit-small">Edit</a>

                <a href="delete_guide.php?id=<?php echo $row['guide_id']; ?>" class="btn-delete-small" onclick="return confirm('Delete this guide?');">Delete</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="no-data">
        <p>No guides found. Make sure guides are added and their role is set to 'tour_guide' in the users table.</p>
    </div>
<?php endif; ?>
</div>
</body>
</html>
