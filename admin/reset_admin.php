<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$newUsername = "admin";
$newPassword = password_hash("123456", PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET username=?, password=?, role='admin' WHERE id=1");
$stmt->bind_param("ss", $newUsername, $newPassword);

if ($stmt->execute()) {
    echo "Admin updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>