<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('../db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// --- 1. FETCH CURRENT DATA ---
// We fetch this at the start so we can verify the old password and fill the form
$query = "SELECT u.*, g.experience_years FROM users u 
          LEFT JOIN guides g ON u.id = g.user_id WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $u_id);
$stmt->execute();
$guideData = $stmt->get_result()->fetch_assoc();

// --- 2. HANDLE PROFILE UPDATES (Name, Username, Email, Exp) ---
if (isset($_POST['update_prof'])) {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $exp      = (int)$_POST['experience'];

    // Check if username is taken by someone else
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $check->bind_param("si", $username, $u_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo "<script>alert('Error: Username already taken!'); window.history.back();</script>";
        exit();
    }

    $conn->begin_transaction();
    try {
        // Update Users Table
        $stmt_u = $conn->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=? WHERE id=?");
        $stmt_u->bind_param("ssssi", $fullname, $username, $email, $phone, $u_id);
        $stmt_u->execute();

        // Update Guides Table
        $stmt_g = $conn->prepare("UPDATE guides SET experience_years=? WHERE user_id=?");
        $stmt_g->bind_param("ii", $exp, $u_id);
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

    // Verify current password
    if (password_verify($old_pass, $guideData['password'])) {
        if ($new_pass === $conf_pass) {
            if (strlen($new_pass) >= 6) { // Basic length check
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
        echo "<script>alert('Incorrect current password! No changes allowed.');</script>";
    }
}

// --- 4. HANDLE DELETE ACCOUNT ---
if (isset($_POST['delete_account'])) {
    $confirm_pass = $_POST['delete_confirm_password'];

    // 1. Verify password before allowing deletion
    if (password_verify($confirm_pass, $guideData['password'])) {
        $conn->begin_transaction();
        try {
            // Delete from all linked tables
            // Note: If you have foreign key constraints with ON DELETE CASCADE, 
            // deleting from 'users' might be enough, but this is safer:
            $conn->query("DELETE FROM guide_availability WHERE guide_id = '$u_id'");
            $conn->query("DELETE FROM guides WHERE user_id = '$u_id'");
            $conn->query("DELETE FROM users WHERE id = '$u_id'");

            $conn->commit();

            // Destroy session and redirect to home/login
            session_destroy();
            echo "<script>alert('Your account has been permanently deleted.'); window.location.href='../index.php';</script>";
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "<script>alert('Error: Could not delete account. " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Incorrect password! Account deletion cancelled.');</script>";
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

            <div id="edit-form" class="hidden-form">
                <form method="POST">
                    <div class="form-grid">
                        <input type="text" name="fullname" value="<?= $guideData['fullname'] ?>" placeholder="Full Name">
                        <input type="text" name="username" value="<?= $guideData['username'] ?>" placeholder="Username">
                        <input type="email" name="email" value="<?= $guideData['email'] ?>" placeholder="Email">
                        <input type="text" name="phone" value="<?= $guideData['phone'] ?>" placeholder="Phone">
                        <input type="number" name="experience" value="<?= $guideData['experience_years'] ?>" placeholder="Exp Years">
                    </div>
                    <button type="submit" name="update_prof" class="btn-gradient">Save Profile</button>
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
