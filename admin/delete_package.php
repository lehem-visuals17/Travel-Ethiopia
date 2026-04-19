<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = $_GET['id'];

$get = $conn->query("SELECT image FROM packages WHERE id='$id'");
$data = $get->fetch_assoc();

if(!empty($data['image']) && file_exists("../uploads/".$data['image'])){
    unlink("../uploads/".$data['image']);
}

$conn->query("DELETE FROM packages WHERE id='$id'");

header("Location: packages.php");
exit();
?>