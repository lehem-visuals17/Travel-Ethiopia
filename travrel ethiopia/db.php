<?php
// Database connection details
$conn = new mysqli("localhost", "root", "password", "your_db_name");

$username = $_POST['username'] ?? '';
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['exists' => $result->num_rows > 0]);
?>
