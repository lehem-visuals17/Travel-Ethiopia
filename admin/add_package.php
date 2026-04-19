<?php
$conn = new mysqli("localhost","root","","travel_db");

if(isset($_POST['create_package'])){

    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $includes = $_POST['includes'];
    $rating = $_POST['rating'];
    $max_people = $_POST['max_people'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    $image = "";

    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO packages
            (title, type, description, price, duration, includes, rating, image, max_people, featured)
            VALUES
            ('$title','$type','$description','$price','$duration','$includes','$rating','$image','$max_people','$featured')";

    if($conn->query($sql)){
        header("Location: packages.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Package</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<div class="modal-overlay">
<div class="modal-card">

<div class="modal-header">
    <h2>Create Package</h2>
    <a href="packages.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

<div class="input-box">
    <label>Package Title</label>
    <input type="text" name="title" required>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Package Type</label>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="honeymoon">Honeymoon</option>
            <option value="family">Family</option>
            <option value="adventure">Adventure</option>
            <option value="luxury">Luxury</option>
            <option value="budget">Budget</option>
        </select>
    </div>

    <div class="input-box">
        <label>Price</label>
        <input type="number" step="0.01" name="price">
    </div>
</div>

<div class="input-box">
    <label>Description</label>
    <textarea name="description"></textarea>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Duration</label>
        <input type="text" name="duration" placeholder="7 Days / 6 Nights">
    </div>

    <div class="input-box">
        <label>Max People</label>
        <input type="number" name="max_people">
    </div>
</div>

<div class="input-box">
    <label>Includes</label>
    <textarea name="includes" placeholder="Hotel, transport, meals..."></textarea>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Rating</label>
        <input type="text" name="rating">
    </div>

    <div class="featured-row">
    <span>Featured</span>
    <label class="switch">
        <input type="checkbox" name="featured" value="1">
        <span class="slider"></span>
    </label>
    </div>
</div>

<div class="input-box">
    <label>Package Image</label>
    <input type="file" name="image" accept="image/*">
</div>

<div class="modal-actions">
    <a href="packages.php" class="btn-secondary">Cancel</a>
    <button type="submit" name="create_package" class="btn-primary">Create Package</button>
</div>

</form>
</div>
</div>

</body>
</html>