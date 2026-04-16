<?php
session_start();
include "db.php";

// check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// get user data
$id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>

<h2>User Profile</h2>

<div style="border:1px solid #ccc; padding:20px; width:300px;">
    <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>

    <a href="logout.php">Logout</a>
</div>

</body>
</html>