<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $map_link = $_POST['map_link'];
    $price = floatval($_POST['price']); 
    $duration = $_POST['duration'];
    $schedule = $_POST['schedule'];
    $languages = $_POST['languages'];
    $capacity = intval($_POST['capacity']);
    $difficulty = $_POST['difficulty'];
    $status = $_POST['status'];
    $is_featured = intval($_POST['is_featured']);
    $description = $_POST['description'];
    $whats_included = $_POST['whats_included'];
    $not_included = $_POST['not_included'];
    $itinerary = $_POST['itinerary'];
    $gallery = $_POST['gallery'];
    $rating = floatval($_POST['rating']);

    // Handle Image Upload
    $image_name = $_FILES['image']['name'];
    $target_file = "uploads/" . basename($image_name);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        
        $stmt = $conn->prepare("INSERT INTO experiences (name, category, location, map_link, price, duration, schedule, languages, capacity, difficulty, status, is_featured, description, whats_included, not_included, itinerary, image, gallery, rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssdsssisssssssssd", 
            $name, $category, $location, $map_link, $price, $duration, $schedule, $languages, $capacity, 
            $difficulty, $status, $is_featured, $description, $whats_included, 
            $not_included, $itinerary, $image_name, $gallery, $rating
        );

        if ($stmt->execute()) {
            header("Location: experience.php?success=1");
            exit();
        } else {
            echo "SQL Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>
