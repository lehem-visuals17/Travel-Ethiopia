<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$id = intval($_GET['id']);
$conn->query("DELETE FROM deals WHERE id = $id");
header("Location: admin_deals.php");
exit();
?>
