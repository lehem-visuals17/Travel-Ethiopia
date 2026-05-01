<?php
session_start();
include '../db.php'; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];

// --- HANDLE PROFILE UPDATE ---
if (isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $gender   = $_POST['gender'];
    $nationality = trim($_POST['nationality']);

    // 1. Check for duplicates (Standard PHP check, NO JSON)
    $check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $check->bind_param("ssi", $username, $email, $user_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "<script>alert('Error: Username or Email is already taken.'); window.history.back();</script>";
        exit();
    }

    // 2. Update Database
    $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, email=?, phone=?, gender=?, nationality=? WHERE id=?");
    $stmt->bind_param("ssssssi", $fullname, $username, $email, $phone, $gender, $nationality, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; // Update session
        echo "<script>alert('Profile updated successfully!'); window.location.href='settings.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// --- HANDLE PASSWORD CHANGE ---
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_new_password'];

    // 1. Basic match check
    if ($new_pass !== $confirm_pass) {
        echo "<script>alert('New passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // 2. Fetch the current password from DB to verify
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // 3. Verify if the entered "Old Password" matches what's in the DB
    if (password_verify($old_pass, $user['password'])) {
        // Correct! Now hash the new password and update
        $hashed_new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_new_pass, $user_id);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Password updated successfully!'); window.location.href='settings.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
        $update_stmt->close();
    } else {
        // Incorrect current password
        echo "<script>alert('Error: The current password you entered is incorrect.'); window.history.back();</script>";
    }
    $stmt->close();
}

// --- HANDLE PHOTO UPLOAD ---
if (isset($_POST['upload_photo'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $target_dir = "../admin/uploads/";
        
        // Ensure directory exists
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_extension = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        // Allow only certain formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                
                // Update the database
                $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $new_filename, $user_id);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Photo updated successfully!'); window.location.href='settings.php';</script>";
                } else {
                    echo "Database Error: " . $conn->error;
                }
            } else {
                echo "<script>alert('Failed to move uploaded file.'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF allowed.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Please select a valid image.'); window.history.back();</script>";
    }
}


// --- 4. HANDLE ACCOUNT DELETION ---
if (isset($_POST['delete_account'])) {
    $entered_pass = $_POST['confirm_pass'];
    $user_id = $_SESSION['user_id'];

    // 1. Fetch current password hash from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // 2. Verify password
    if ($user && password_verify($entered_pass, $user['password'])) {
        
        // 3. Delete the user
        // Note: This will work best if your 'bookings' table uses ON DELETE CASCADE
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_stmt->bind_param("i", $user_id);
        
        if ($delete_stmt->execute()) {
            // 4. Clear session and redirect to home
            session_unset();
            session_destroy();
            echo "<script>alert('Your account has been permanently deleted.'); window.location.href='../index.php';</script>";
            exit();
        } else {
            echo "Error deleting account: " . $conn->error;
        }
    } else {
        // Incorrect password
        echo "<script>alert('Error: Incorrect password. Deletion cancelled.'); window.history.back();</script>";
    }
    $stmt->close();
}

?>
