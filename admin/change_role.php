<?php
session_start();
include "../db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $user = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();

    $newRole = ($user['role'] == 'admin') ? 'customer' : 'admin';

    $stmt = $conn->prepare("UPDATE users SET role=? WHERE id=?");
    $stmt->bind_param("si", $newRole, $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>