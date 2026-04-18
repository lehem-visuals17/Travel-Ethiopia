<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = $_GET['id'];

$conn->query("DELETE FROM guides WHERE id='$id'");

header("Location: guides.php");
exit();
?>