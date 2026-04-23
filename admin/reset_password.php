<?php
session_start();
include "../db.php"; // FIXED PATH

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $newPassword = password_hash("12345678", PASSWORD_DEFAULT);

    if ($conn) {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Database connection failed");
    }
}

header("Location: users.php?msg=reset_success");
exit();
?>