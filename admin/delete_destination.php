<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = $_GET['id'];

$result = $conn->query("SELECT image FROM destinations WHERE id='$id'");
$row = $result->fetch_assoc();

$image_path = "../uploads/" . $row['image'];

if(file_exists($image_path)){
    unlink($image_path);
}

$conn->query("DELETE FROM destinations WHERE id='$id'");

header("Location: destinations.php");
exit();
?>