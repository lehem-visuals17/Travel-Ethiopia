<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "travel_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Optional username duplicate check
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = $_POST['username'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    echo json_encode(['exists' => $result->num_rows > 0]);
    exit();
}
?>