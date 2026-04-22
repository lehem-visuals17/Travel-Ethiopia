<?php
session_start();
include "../db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $newPassword = password_hash("12345678", PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $newPassword, $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>