<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

if (isset($_POST['submit'])) {
    // 1. Collect form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $difficulty = $_POST['difficulty'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    
    // 2. Handle Image Upload
    $imageName = $_FILES['image']['name'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($imageName);

    if (!empty($imageName)) {
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    } else {
        $imageName = "default.jpg"; // Fallback if no image uploaded
    }

    // 3. Insert into Database
  $status = $_POST['status'];
$is_featured = isset($_POST['is_featured']) ? 1 : 0;

$sql = "INSERT INTO experiences 
(name, category, capacity, difficulty, status, price, is_featured, location, description, image)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssissdisss",
    $name,
    $category,
    $capacity,
    $difficulty,
    $status,
    $price,
    $is_featured,
    $location,
    $description,
    $imageName
);
    if ($stmt->execute()) {
        // Redirect back to the table page with success
        header("Location: experience.php?status=success");
    } else {
        echo "Error: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
