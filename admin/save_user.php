<?php
// REPLACE include "../db.php"; WITH THIS:
$conn = new mysqli("localhost", "root", "", "travel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? "";
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    if ($id == "") {
        // ADD USER
        $raw_password = $_POST['password'] ?? '123456'; 
        $password = password_hash($raw_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users (fullname, username, email, password, phone, role, status)
            VALUES (?, ?, ?, ?, ?, ?, 'active')
        ");
        $stmt->bind_param("ssssss", $fullname, $username, $email, $password, $phone, $role);
    } else {
        // UPDATE USER
        $status = $_POST['status'];

        $stmt = $conn->prepare("
            UPDATE users 
            SET fullname=?, username=?, email=?, phone=?, role=?, status=?
            WHERE id=?
        ");
        $stmt->bind_param("ssssssi", $fullname, $username, $email, $phone, $role, $status, $id);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: users.php");
    exit();
}
?>
