<?php
session_start();
include "../db.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn->query("DELETE FROM users WHERE id=$id");
}

header("Location: dashboard.php");
exit();
?>