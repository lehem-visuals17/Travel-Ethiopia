<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('../db.php');

$u_id = $_SESSION['user_id'];

// 1. Fetch current data including language
$query = "SELECT u.*, g.experience_years, g.language FROM users u 
          LEFT JOIN guides g ON u.id = g.user_id WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$guideData = $stmt->get_result()->fetch_assoc();

// --- UPDATE PROFILE LOGIC ---
if (isset($_POST['update_prof'])) {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $exp      = (int)$_POST['experience'];
    $lang     = trim($_POST['language']);

    // THE KEY FIX: Check if username/email belongs to ANOTHER user (id != $u_id)
    $check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $check->bind_param("ssi", $username, $email, $u_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // This only triggers if someone ELSE has your chosen username/email
        echo "<script>alert('Error: This username or email is already in use by someone else.'); window.location.href='settings.php';</script>";
        exit();
    }

    $conn->begin_transaction();
    try {
        // Update Users Table
        $stmt_u = $conn->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=? WHERE id=?");
        $stmt_u->bind_param("ssssi", $fullname, $username, $email, $phone, $u_id);
        $stmt_u->execute();

        // Update Guides Table (Experience and Language)
        $stmt_g = $conn->prepare("UPDATE guides SET experience_years=?, language=? WHERE user_id=?");
        $stmt_g->bind_param("isi", $exp, $lang, $u_id);
        $stmt_g->execute();

        $conn->commit();
        echo "<script>alert('Profile updated successfully!'); window.location.href='settings.php';</script>";
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>alert('Update failed: " . $e->getMessage() . "');</script>";
    }
}


// --- 3. HANDLE PASSWORD CHANGE ---
if (isset($_POST['change_pass'])) {
    $old_pass  = $_POST['old_password'];
    $new_pass  = $_POST['new_password'];
    $conf_pass = $_POST['confirm_password'];

    if (password_verify($old_pass, $guideData['password'])) {
        if ($new_pass === $conf_pass) {
            if (strlen($new_pass) >= 6) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param("si", $hashed, $u_id);
                $upd->execute();
                echo "<script>alert('Password updated successfully!'); window.location.href='settings.php';</script>";
                exit();
            } else {
                echo "<script>alert('New password must be at least 6 characters!');</script>";
            }
        } else {
            echo "<script>alert('New passwords do not match!');</script>";
        }
    } else {
        echo "<script>alert('Incorrect current password!');</script>";
    }
}

// --- 4. HANDLE DELETE ACCOUNT ---
if (isset($_POST['delete_account'])) {
    $confirm_pass = $_POST['delete_confirm_password'];

    if (password_verify($confirm_pass, $guideData['password'])) {
        $conn->begin_transaction();
        try {
            $conn->query("DELETE FROM guide_availability WHERE guide_id = (SELECT id FROM guides WHERE user_id = '$u_id')");
            $conn->query("DELETE FROM guides WHERE user_id = '$u_id'");
            $conn->query("DELETE FROM users WHERE id = '$u_id'");

            $conn->commit();
            session_destroy();
            echo "<script>alert('Account deleted.'); window.location.href='../index.php';</script>";
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Incorrect password!');</script>";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="sidebar.css">
    
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
<div class="main-layout">
    <?php include('sidebar.php'); ?>

    <div class="content-area">
        <h1>Account Settings</h1>

        <!-- Settings Menu (Matches Image) -->
        <div class="settings-container">
            
            <!-- Edit Profile Item -->
            <div class="settings-card" onclick="toggleForm('edit-form')">
                <div class="icon-box blue-bg"><i class="fa-solid fa-user-pen"></i></div>
                <div class="card-info">
                    <h3>Edit Profile</h3>
                    <p>Update your personal and professional information</p>
                </div>
                <i class="fa-solid fa-chevron-right arrow"></i>
            </div>

  <div id="edit-form" class="hidden-form" style="display: block;">
    <form method="POST">
        <div class="form-grid">
            <div class="input-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($guideData['fullname']) ?>" required>
            </div>
            
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($guideData['username']) ?>" required>
            </div>

            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($guideData['email']) ?>" required>
            </div>

            <div class="input-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($guideData['phone'] ?? '') ?>">
            </div>

            <div class="input-group">
                <label for="experience">Experience (Years)</label>
                <input type="number" id="experience" name="experience" value="<?= $guideData['experience_years'] ?? 0 ?>">
            </div>

            <div class="input-group">
                <label for="language">Languages</label>
                <input type="text" id="language" name="language" value="<?= htmlspecialchars($guideData['language'] ?? '') ?>" placeholder="e.g. Amharic, English">
            </div>
        </div>
        <!-- Gradient Button: #ffb347 to #ff4b2b -->
        <button type="submit" name="update_prof" class="btn-gradient">Save All Changes</button>
    </form>
</div>



            <!-- Change Password Item -->
            <div class="settings-card" onclick="toggleForm('pass-form')">
                <div class="icon-box green-bg"><i class="fa-solid fa-lock"></i></div>
                <div class="card-info">
                    <h3>Change Password</h3>
                    <p>Update your security credentials</p>
                </div>
                <i class="fa-solid fa-chevron-right arrow"></i>
            </div>

            <div id="pass-form" class="hidden-form">
                <form method="POST">
                    <input type="password" name="old_password" placeholder="Current Password" required>
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                    <button type="submit" name="change_pass" class="btn-gradient">Update Password</button>
                </form>
            </div>

            <!-- Delete Account Item -->
            <!-- Delete Account Item -->
<div class="settings-card border-red" onclick="toggleForm('delete-form')">
    <div class="icon-box red-bg"><i class="fa-solid fa-trash-can"></i></div>
    <div class="card-info">
        <h3 class="text-red">Delete Account</h3>
        <p>Permanently remove your account and all data</p>
    </div>
    <i class="fa-solid fa-chevron-right arrow"></i>
</div>

<div id="delete-form" class="hidden-form warning-box">
    <form method="POST" onsubmit="return confirm('WARNING: This action is permanent. Are you absolutely sure?')">
        <p class="warning-text">To confirm deletion, please enter your password below.</p>
        <input type="password" name="delete_confirm_password" placeholder="Confirm Password to Delete" required>
        <button type="submit" name="delete_account" class="btn-danger">Permanently Delete My Account</button>
    </form>
</div>


        </div>
    </div>
</div>

<script>
function toggleForm(id) {
    var form = document.getElementById(id);
    form.style.display = (form.style.display === "block") ? "none" : "block";
}
</script>
</body>
</html>
