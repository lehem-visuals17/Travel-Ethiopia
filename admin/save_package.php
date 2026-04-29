<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? "";
    
    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $reviews_count = $_POST['reviews_count'];
    $max_people = $_POST['max_people'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $badge_text = $_POST['badge_text'];
    $includes_list = $_POST['includes_list'];

    // Handle File Upload if an image exists
    $image_sql = "";
    if (!empty($_FILES["image"]["name"])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
        $image_sql = ", image='$image'";
    }

    if ($id == "") {
        // INSERT MODE
        $image = $_FILES['image']['name']; // Image is required here
        $stmt = $conn->prepare("INSERT INTO packages (title, type, description, price, duration, rating, reviews_count, max_people, featured, badge_text, includes_list, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdiisss", $title, $type, $description, $price, $duration, $rating, $reviews_count, $max_people, $featured, $badge_text, $includes_list, $image);
    } else {
        // UPDATE MODE
        $sql = "UPDATE packages SET title=?, type=?, description=?, price=?, duration=?, rating=?, reviews_count=?, max_people=?, featured=?, badge_text=?, includes_list=? $image_sql WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssdiissi", $title, $type, $description, $price, $duration, $rating, $reviews_count, $max_people, $featured, $badge_text, $includes_list, $id);
    }

    if ($stmt->execute()) {
        header("Location: packages.php");
        exit();
    }
}
?>
