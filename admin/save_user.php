<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$id = $_POST['id'];
$fullname = $_POST['fullname'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$role = $_POST['role'];

if ($id == "") {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (fullname, username, email, password, phone, role)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $fullname, $username, $email, $password, $phone, $role);

} else {
    $stmt = $conn->prepare("
        UPDATE users
        SET fullname=?, username=?, email=?, phone=?, role=?
        WHERE id=?
    ");
    $stmt->bind_param("sssssi", $fullname, $username, $email, $phone, $role, $id);
}

$stmt->execute();

header("Location: user.php");
exit();
?>