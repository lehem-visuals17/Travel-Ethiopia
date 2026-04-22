<?php
session_start();
include "../db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $user = $conn->query("SELECT status FROM users WHERE id=$id")->fetch_assoc();

    $newStatus = ($user['status'] == 'active') ? 'suspended' : 'active';

    $stmt = $conn->prepare("UPDATE users SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>