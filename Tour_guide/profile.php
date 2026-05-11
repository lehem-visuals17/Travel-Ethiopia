<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// --- 1. HANDLE PROFILE UPDATES ---
if (isset($_POST['update_profile'])) {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $phone    = $_POST['phone'];
    $exp      = $_POST['experience'];

    $conn->begin_transaction();
    try {
        // Update Users Table
        $st1 = $conn->prepare("UPDATE users SET fullname=?, email=?, username=?, phone=? WHERE id=?");
        $st1->bind_param("ssssi", $fullname, $email, $username, $phone, $u_id);
        $st1->execute();

        // Update Guides Table (Experience)
        $st2 = $conn->prepare("UPDATE guides SET experience_years=? WHERE user_id=?");
        $st2->bind_param("ii", $exp, $u_id);
        $st2->execute();

        $conn->commit();
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Error updating profile: " . $e->getMessage() . "');</script>";
    }
}

// --- 2. HANDLE PHOTO UPLOAD ---
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $upload_dir = '../uploads/';
    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }

    $file_name = time() . "_" . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        mysqli_query($conn, "UPDATE users SET profile_pic = '$file_name' WHERE id = '$u_id'");
        mysqli_query($conn, "UPDATE guides SET image = '$file_name' WHERE user_id = '$u_id'");
        echo "<script>alert('Photo updated!'); window.location.href='profile.php';</script>";
        exit();
    }
}

// --- 3. FETCH FULL GUIDE DATA ---
// Fetch joined data including the language field
$query = "SELECT u.*, g.experience_years, g.language, g.rating, g.image as guide_image, d.name as destination_name 
          FROM users u 
          LEFT JOIN guides g ON u.id = g.user_id 
          LEFT JOIN destinations d ON g.destination_id = d.id
          WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$guideData = $stmt->get_result()->fetch_assoc();


// Determine Image Path
$display_img = "../assets/default-avatar.png"; 
if (!empty($guideData['guide_image'])) { $display_img = "../uploads/" . $guideData['guide_image']; }
elseif (!empty($guideData['profile_pic'])) { $display_img = "../uploads/" . $guideData['profile_pic']; }
?>




<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="sidebar.css">
  
</head>
<body>
<div class="main-layout">
    <?php include('sidebar.php'); ?>

    <div class="content-area">
    <header class="page-header">
        <h1>Profile Management</h1>
        <p>Update your personal information and preferences</p>
    </header>

    <!-- SECTION 1: Photo Section (Matches your image) -->
    <div class="profile-card">
    <h3>Profile Photo</h3>
    <div class="photo-flex">
        <!-- Previous Photo Display -->
        <img src="<?= $display_img ?>" class="current-avatar" onerror="this.src='../assets/default-avatar.png'">
        
        <div class="upload-controls">
            <!-- Form MUST have enctype="multipart/form-data" -->
            <form method="POST" enctype="multipart/form-data">
                <label class="btn-upload">
                    <i class="fa fa-camera"></i> Change Photo
                    <input type="file" name="image" onchange="this.form.submit()" style="display:none;">
                </label>
            </form>
            <p class="file-hint">Current: <?= $guideData['profile_pic'] ?? 'None' ?></p>
        </div>
    </div>
</div>


    <!-- SECTION 2: Basic Information List -->
    <div class="profile-card">
    <div class="card-header-flex">
        <h3>Full Information List</h3>
        <span class="badge-role"><?= strtoupper($guideData['role']) ?></span>
    </div>
    <div class="profile-card info-list-card">
    <h3>Full Information List</h3>
    <p class="sub-text">Everything currently registered in our system</p>
    
    <div class="detail-list">
        <!-- Full Name -->
        <div class="detail-item">
            <span class="label">Full Name</span>
            <span class="value"><?= htmlspecialchars($guideData['fullname']) ?></span>
        </div>

        <!-- Username -->
        <div class="detail-item">
            <span class="label">Username</span>
            <span class="value">@<?= htmlspecialchars($guideData['username']) ?></span>
        </div>

        <div class="detail-item">
                    <span class="label">Email Address</span>
                    <span class="value"><?= htmlspecialchars($guideData['email']) ?></span>
                </div>

        <!-- Phone Number -->
        <div class="detail-item">
            <span class="label">Phone Number</span>
            <span class="value"><?= htmlspecialchars($guideData['phone'] ?? 'Not Set') ?></span>
        </div>

        <!-- Assigned Destination -->
        <div class="detail-item">
            <span class="label">Assigned Destination</span>
            <span class="value"><?= htmlspecialchars($guideData['destination_name'] ?? 'Not Assigned') ?></span>
        </div>

        <!-- Experience -->
        <div class="detail-item">
        <span class="label">Experience</span>
        <!-- Accessing the joined column directly -->
        <span class="value"><?= isset($guideData['experience_years']) ? $guideData['experience_years'] : '0' ?> Years</span>
    </div>
    <div class="detail-item">
    <span class="label">Languages</span>
    <span class="value"><?= htmlspecialchars($guideData['language'] ?? 'Not Set') ?></span>
</div>

    </div>
</div>

</div>

</div>

</div>

</div>
</body>
</html>
