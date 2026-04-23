<?php
session_start();
include "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST['user_id']);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Check if new passwords match
    if ($new_password !== $confirm_password) {
        die("❌ New password and confirm password do not match.");
    }

    // 2. Get current password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("❌ User not found.");
    }

    // 3. Check old password correctness
    if (!password_verify($old_password, $user['password'])) {
        die("❌ Old password is incorrect.");
    }

    // 4. Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 5. Update password
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $hashed_password, $id);
    $update->execute();

    // 6. Redirect back
    header("Location: users.php?msg=password_updated");
    exit();
}
?>