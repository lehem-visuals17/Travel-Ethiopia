<?php
$conn = new mysqli("localhost", "root", "", "travle_db");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Delete the image file from the folder first
    $res = $conn->query("SELECT image FROM blog_posts WHERE id = $id");
    $row = $res->fetch_assoc();
    if ($row['image'] && file_exists($row['image'])) {
        unlink($row['image']); 
    }

    // 2. Delete the record from the database
    $conn->query("DELETE FROM blog_posts WHERE id = $id");
}

header("Location: blog.php");
exit();
?>
