<?php
$conn = new mysqli("localhost","root","","travel_db");

if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get the Guide ID from the URL
$guide_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$guide_id) {
    die("Error: No Guide ID provided.");
}

// 1. Fetch the guide AND their specific user account details
$stmt = $conn->prepare("SELECT g.*, u.username, u.role, u.id as linked_user_id 
                        FROM guides g 
                        JOIN users u ON g.user_id = u.id 
                        WHERE g.id = ?");
$stmt->bind_param("i", $guide_id);
$stmt->execute();
$guide = $stmt->get_result()->fetch_assoc();

if (!$guide) {
    die("Error: Guide not found.");
}

if(isset($_POST['update_guide'])){
    $target_user_id = $guide['linked_user_id'];
    $destination_id = $_POST['destination_id'];
    $name           = $_POST['name'];
    $phone          = $_POST['phone'];
    $username       = $_POST['username'];
    $language       = $_POST['language'];
    $experience     = $_POST['experience_years'];
    $rating         = $_POST['rating'];
    $role           = 'tour_guide';

    // Handle Image
    $image = $guide['image'];
    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
    }

    $conn->begin_transaction();
    try {
        // 2. Update the USER table
        if (!empty($_POST['password'])) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $upd_u = $conn->prepare("UPDATE users SET username=?, password=?, phone=?, role=?, fullname=? WHERE id=?");
            $upd_u->bind_param("sssssi", $username, $pass, $phone, $role, $name, $target_user_id);
        } else {
            $upd_u = $conn->prepare("UPDATE users SET username=?, phone=?, role=?, fullname=? WHERE id=?");
            $upd_u->bind_param("ssssi", $username, $phone, $role, $name, $target_user_id);
        }
        $upd_u->execute();

        // 3. Update the GUIDE table (Added missing language, experience, and rating)
        $upd_g = $conn->prepare("UPDATE guides SET destination_id=?, name=?, phone=?, language=?, experience_years=?, rating=?, image=? WHERE id=?");
        $upd_g->bind_param("isssidsi", $destination_id, $name, $phone, $language, $experience, $rating, $image, $guide_id);
        $upd_g->execute();

        $conn->commit();
        echo "<script>alert('Guide updated successfully!'); window.location.href='guides.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error updating: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Guide</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<div class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Edit Guide</h2>
            <a href="guides.php" class="close-x">&times;</a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="modal-form">
            <div class="input-box">
                <label>Destination</label>
                <select name="destination_id" required>
                    <option value="">Select Destination</option>
                    <?php
                    $destinations = $conn->query("SELECT id,name FROM destinations ORDER BY name ASC");
                    while($dest = $destinations->fetch_assoc()):
                    ?>
                    <option value="<?php echo $dest['id']; ?>" <?php if($guide['destination_id']==$dest['id']) echo "selected"; ?>>
                        <?php echo htmlspecialchars($dest['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input-box">
                <label>Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($guide['name']); ?>" required>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Login Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($guide['username']); ?>" required>
                </div>
                <div class="input-box">
                    <label>New Password (Leave blank to keep)</label>
                    <input type="password" name="password" placeholder="********">
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($guide['phone']); ?>">
                </div>
                <div class="input-box">
                    <label>Languages</label>
                    <input type="text" name="language" value="<?php echo htmlspecialchars($guide['language']); ?>">
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Experience (Years)</label>
                    <input type="number" name="experience_years" value="<?php echo $guide['experience_years']; ?>">
                </div>
                <div class="input-box">
                    <label>Rating</label>
                    <input type="text" name="rating" value="<?php echo $guide['rating']; ?>">
                </div>
            </div>

            <div class="input-box">
                <label>Change Profile Photo</label>
                <input type="file" name="image">
                <small>Current: <?php echo $guide['image']; ?></small>
            </div>

            <div class="modal-actions">
                <a href="guides.php" class="btn-secondary">Cancel</a>
                <button type="submit" name="update_guide" class="btn-primary">Update Guide</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
