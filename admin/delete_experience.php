<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = intval($_GET['id']);

$conn->query("DELETE FROM experiences WHERE id=$id");

header("Location: experience.php");
exit();
?>