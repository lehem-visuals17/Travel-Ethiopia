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

    if ($id == "") {
        // --- INSERT MODE ---
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        
        $stmt = $conn->prepare("INSERT INTO packages (title, type, description, price, duration, rating, reviews_count, max_people, featured, badge_text, includes_list, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // s = string, d = double, i = integer
        $stmt->bind_param("sssdssdiisss", $title, $type, $description, $price, $duration, $rating, $reviews_count, $max_people, $featured, $badge_text, $includes_list, $image);
    } else {
        // --- UPDATE MODE ---
        if (!empty($_FILES["image"]["name"])) {
            // Update including NEW image
            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
            
            $sql = "UPDATE packages SET title=?, type=?, description=?, price=?, duration=?, rating=?, reviews_count=?, max_people=?, featured=?, badge_text=?, includes_list=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssdiisssi", $title, $type, $description, $price, $duration, $rating, $reviews_count, $max_people, $featured, $badge_text, $includes_list, $image, $id);
        } else {
            // Update KEEPING OLD image
            $sql = "UPDATE packages SET title=?, type=?, description=?, price=?, duration=?, rating=?, reviews_count=?, max_people=?, featured=?, badge_text=?, includes_list=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssdssdiissi", $title, $type, $description, $price, $duration, $rating, $reviews_count, $max_people, $featured, $badge_text, $includes_list, $id);
        }
    }

    if ($stmt->execute()) {
        header("Location: packages.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
