<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    // Store simple inclusions and standard tick list arrays
    $includes = $_POST['includes'];
    $includes_list = $_POST['includes_list']; 

    // File Upload Handler
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO packages (title, type, description, price, duration, includes, rating, reviews_count, image, max_people, featured, badge_text, includes_list) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssdisiiss", $title, $type, $description, $price, $duration, $includes, $rating, $reviews_count, $image, $max_people, $featured, $badge_text, $includes_list);

    if ($stmt->execute()) {
        header("Location: admin_packages.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Package</title>
    <link rel="stylesheet" href="admin_deals.css"> <!-- Reusing your modal design layout files -->
</head>
<body style="background:#fafafa; font-family:sans-serif; padding: 20px;">

<div style="background:#fff; margin:20px auto; padding:30px; width:60%; max-width:700px; border-radius:10px; border:1px solid #eef0f2;">
    <h2>Create New Tour Package</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:2;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Package Title</label>
                <input type="text" name="title" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" placeholder="e.g. Romantic Honeymoon" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Category Type</label>
                <select name="type" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    <option value="honeymoon">Honeymoon</option>
                    <option value="family">Family</option>
                    <option value="adventure">Adventure</option>
                    <option value="luxury">Luxury</option>
                    <option value="budget">Budget</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Price ($)</label>
                <input type="number" step="0.01" name="price" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Duration</label>
                <input type="text" name="duration" placeholder="e.g. 7 Days" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Max People</label>
                <input type="number" name="max_people" value="1" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Star Rating</label>
                <input type="number" step="0.1" name="rating" placeholder="4.9" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Reviews Count</label>
                <input type="number" name="reviews_count" placeholder="342" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Card Badge</label>
                <input type="text" name="badge_text" placeholder="e.g. Popular" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">Welcome Page Checklist Features (Separated by commas)</label>
            <input type="text" name="includes_list" placeholder="e.g. Luxury accommodations, Private guided tours, Spa treatments" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:2;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Cover Image</label>
                <input type="file" name="image" style="width:100%;" required>
            </div>
            <div style="flex:1; align-items:center; display:flex;">
                <label style="font-weight:bold;"><input type="checkbox" name="featured" value="1"> Feature on Home</label>
            </div>
        </div>

        <button type="submit" style="background:#ff9326; color:white; padding:12px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer; width:100%;">Save Package</button>
        <div style="text-align:center; margin-top:15px;">
            <a href="admin_packages.php" style="color:#777; text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
