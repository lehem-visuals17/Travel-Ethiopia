<?php
session_start();
include '../db.php'; // Path to your db connection

// Security Check: Only customers allowed
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['user_id'];

$current_date = date('Y-m-d');
$user_id = $_SESSION['user_id'];

// 1. Total Trips (All bookings)
$total_trips_query = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ?");
$total_trips_query->bind_param("i", $user_id);
$total_trips_query->execute();
$total_trips = $total_trips_query->get_result()->fetch_assoc()['total'] ?? 0;

// 2. Upcoming Trips (Bookings with a travel date in the future)
// Note: Ensure your 'bookings' table has a 'travel_date' or 'start_date' column
$upcoming_query = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE user_id = ? AND travel_date > ?");
$upcoming_query->bind_param("is", $user_id, $current_date);
$upcoming_query->execute();
$upcoming = $upcoming_query->get_result()->fetch_assoc()['total'] ?? 0;

// 3. Saved Destinations (Total from favorites table)
$favorites_query = $conn->prepare("SELECT COUNT(*) AS total FROM favorites WHERE user_id = ?");
$favorites_query->bind_param("i", $user_id);
$favorites_query->execute();
$favorites = $favorites_query->get_result()->fetch_assoc()['total'] ?? 0;

// 4. Reviews Given (Total from reviews table)
$reviews_query = $conn->prepare("SELECT COUNT(*) AS total FROM reviews WHERE user_id = ?");
$reviews_query->bind_param("i", $user_id);
$reviews_query->execute();
$reviews_count = $reviews_query->get_result()->fetch_assoc()['total'] ?? 0;

$user_id = $_SESSION['user_id'];

// 2. Fetch all user data from the database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// 3. Store the data in the $user_data variable
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    
    // Also update fullname in session just in case it changed
    $_SESSION['fullname'] = $user_data['fullname']; 
} else {
    die("User not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - Ethiopia Tours</title>
    <link rel="stylesheet" href="profile.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

    
<header class="profile-header">
    <div class="header-container">
        
        <p>Manage your account and bookings</p>
    </div>
</header>

<div class="main-container">
        <!-- Summary Stats Grid -->
        <div class="stats-grid">
    <div class="stat-card">
        <div class="icon-circle blue"><i class="fa-regular fa-calendar"></i></div>
        <div class="stat-info"><span>Total Trips</span><h3><?php echo $total_trips; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle light-blue"><i class="fa-regular fa-calendar-check"></i></div>
        <div class="stat-info"><span>Upcoming Trips</span><h3><?php echo $upcoming; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle purple"><i class="fa-regular fa-heart"></i></div>
        <div class="stat-info"><span>Saved Destinations</span><h3><?php echo $favorites; ?></h3></div>
    </div>
    <div class="stat-card">
        <div class="icon-circle dark-blue"><i class="fa-regular fa-star"></i></div>
        <div class="stat-info"><span>Reviews Given</span><h3><?php echo $reviews_count; ?></h3></div>
    </div>
</div>


        <!-- Scrolling Navigation -->
<div class="nav-scroll-container">
            <nav class="profile-nav">
  <a href="../index.php" style="color: #0061f2; font-weight: bold;">
        <i class="fa-solid fa-arrow-left"></i> Back to Site
    </a>
    <a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-house"></i> Overview
    </a>
    <a href="settings.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-gear"></i> Settings
    </a>
    <a href="history.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-book"></i> Booking History
    </a>
    
    <a href="payments.php" class="<?php echo ($current_page == 'payments.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-credit-card"></i> Upcoming Trips
    </a>
    <a href="notifications.php" class="<?php echo ($current_page == 'notifications.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-credit-card"></i> notifications
    </a>
    <a href="support.php" class="<?php echo ($current_page == 'support.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-credit-card"></i> support
    </a>
    <a href="rewards.php" class="<?php echo ($current_page == 'rewards.php') ? 'active' : ''; ?>">
        <i class="fa-solid fa-credit-card"></i> rewards
    </a>  

</nav>
        </div>


<div class="content-body">
    <div class="settings-container">
        <h2>Account Settings</h2>

        <!-- Edit Profile Trigger -->
        <div class="settings-card" onclick="openModal('modal-edit')">
            <div class="settings-icon blue-bg"><i class="fa-regular fa-pen-to-square"></i></div>
            <div class="settings-text"><h3>Edit Profile</h3><p>Update your personal information</p></div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- Change Password Trigger -->
        <div class="settings-card" onclick="openModal('modal-pass')">
            <div class="settings-icon green-bg"><i class="fa-solid fa-lock"></i></div>
            <div class="settings-text"><h3>Change Password</h3><p>Update your security credentials</p></div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- Upload Image Trigger -->
        <div class="settings-card" onclick="openModal('modal-photo')">
            <div class="settings-icon purple-bg"><i class="fa-solid fa-upload"></i></div>
            <div class="settings-text"><h3>Upload Profile Image</h3><p>Add or change your profile photo</p></div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>

        <!-- Delete Account Trigger -->
        <div class="settings-card danger-card" onclick="openModal('modal-delete')">
            <div class="settings-icon red-bg"><i class="fa-regular fa-trash-can"></i></div>
            <div class="settings-text"><h3 class="red-text">Delete Account</h3><p>Permanently remove your account</p></div>
            <i class="fa-solid fa-chevron-right arrow"></i>
        </div>
    </div>
</div>

<!-- --- MODALS --- -->

<!-- 1. Edit Profile Modal -->
<div id="modal-edit" class="settings-modal">
     <div class="modal-content">
        <span class="close" onclick="closeModal('modal-edit')">&times;</span>
        <h3>Edit Profile</h3>
        <form action="update_settings.php" method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user_data['fullname']) ?>" required>
            
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" required>
            
            <label>Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" required>
            
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user_data['phone']) ?>">
            
            <div style="display:flex; gap:10px;">
                <div style="flex:1;">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="Male" <?= $user_data['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $user_data['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div style="flex:1;">
                    <label>Nationality</label>
                    <input type="text" name="nationality" value="<?= htmlspecialchars($user_data['nationality']) ?>">
                </div>
            </div>
            
            <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
        </form>
    </div>
</div>

<!-- 2. Change Password Modal -->
<div id="modal-pass" class="settings-modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modal-pass')">&times;</span>
        <h3>Change Password</h3>
        <form action="update_settings.php" method="POST" onsubmit="return validatePasswords()">
            <label>Current Password</label>
            <input type="password" name="old_password" placeholder="Enter current password" required>
            
            <label>New Password</label>
            <input type="password" id="new_pass" name="new_password" placeholder="Enter new password" required>
            
            <label>Confirm New Password</label>
            <input type="password" id="confirm_new_pass" name="confirm_new_password" placeholder="Confirm new password" required>
            
            <button type="submit" name="change_password" class="btn-save">Update Password</button>
        </form>
    </div>
</div>

<!-- 3. Upload Photo Modal -->
<div id="modal-photo" class="settings-modal">
    <div class="modal-content" style="text-align: center;">
        <span class="close" onclick="closeModal('modal-photo')">&times;</span>
        <h3>Upload Profile Photo</h3>
        
        <!-- Existing Photo Display -->
        <div class="current-photo-preview" style="margin-bottom: 20px;">
            <?php if(!empty($user_data['profile_pic'])): ?>
                <img src="../admin/uploads/<?php echo $user_data['profile_pic']; ?>" id="img-preview" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #0061f2;">
            <?php else: ?>
                <div id="letter-preview" style="width: 120px; height: 120px; background: #d1e9ff; color: #0061f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold; margin: 0 auto;">
                    <?php echo strtoupper(substr($user_data['fullname'], 0, 1)); ?>
                </div>
                <img id="img-preview" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #0061f2; display: none;">
            <?php endif; ?>
        </div>

        <form action="update_settings.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_image" id="file-input" accept="image/*" required onchange="previewImage(this)">
            <button type="submit" name="upload_photo" class="btn-save">Update Photo</button>
        </form>
    </div>
</div>

<!-- 4. Delete Modal -->
<!-- 4. Delete Modal -->
<div id="modal-delete" class="settings-modal">
    <div class="modal-content danger-modal">
        <span class="close" onclick="closeModal('modal-delete')">&times;</span>
        <h3 style="color: #dc2626;">Delete Account</h3>
        <p>Warning: This is permanent. Please enter your <strong>current password</strong> to confirm.</p>
        
        <form action="update_settings.php" method="POST" onsubmit="return confirm('FINAL WARNING: This will permanently delete your data. Proceed?')">
            <label>Current Password</label>
            <input type="password" name="confirm_pass" placeholder="Enter your password" required>
            
            <button type="submit" name="delete_account" class="btn-delete" style="background: #dc2626; margin-top: 10px;">
                Confirm Permanent Deletion
            </button>
        </form>
    </div>
</div>


<script>
  function openModal(id) {
    document.getElementById(id).style.display = "block";
}

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

// Close if user clicks outside the modal
window.onclick = function(event) {
    if (event.target.className === 'settings-modal') {
        event.target.style.display = "none";
    }
}

function validatePasswords() {
    const newPass = document.getElementById('new_pass').value;
    const confirmPass = document.getElementById('confirm_new_pass').value;
    
    if (newPass !== confirmPass) {
        alert("New passwords do not match!");
        return false;
    }
    return true;
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('img-preview');
            const letter = document.getElementById('letter-preview');
            
            preview.src = e.target.result;
            preview.style.display = "block";
            if(letter) letter.style.display = "none";
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body></html>