<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM packages WHERE id='$id'");
$row = $result->fetch_assoc();

if(isset($_POST['update_package'])){

    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $includes = $_POST['includes'];
    $rating = $_POST['rating'];
    $max_people = $_POST['max_people'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    $image = $row['image'];

    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "UPDATE packages SET
            title='$title',
            type='$type',
            description='$description',
            price='$price',
            duration='$duration',
            includes='$includes',
            rating='$rating',
            max_people='$max_people',
            featured='$featured',
            image='$image'
            WHERE id='$id'";

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
    <title>Edit Package</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<div class="modal-overlay">
<div class="modal-card">

<div class="modal-header">
    <h2>Edit Package</h2>
    <a href="packages.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

<div class="input-box">
    <label>Package Title</label>
    <input type="text" name="title" value="<?php echo $row['title']; ?>" required>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Package Type</label>
        <select name="type" required>
            <option value="honeymoon" <?php if($row['type']=="honeymoon") echo "selected"; ?>>Honeymoon</option>
            <option value="family" <?php if($row['type']=="family") echo "selected"; ?>>Family</option>
            <option value="adventure" <?php if($row['type']=="adventure") echo "selected"; ?>>Adventure</option>
            <option value="luxury" <?php if($row['type']=="luxury") echo "selected"; ?>>Luxury</option>
            <option value="budget" <?php if($row['type']=="budget") echo "selected"; ?>>Budget</option>
        </select>
    </div>

    <div class="input-box">
        <label>Price</label>
        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>">
    </div>
</div>

<div class="input-box">
    <label>Description</label>
    <textarea name="description"><?php echo $row['description']; ?></textarea>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Duration</label>
        <input type="text" name="duration" value="<?php echo $row['duration']; ?>">
    </div>

    <div class="input-box">
        <label>Max People</label>
        <input type="number" name="max_people" value="<?php echo $row['max_people']; ?>">
    </div>
</div>

<div class="input-box">
    <label>Includes</label>
    <textarea name="includes"><?php echo $row['includes']; ?></textarea>
</div>

<div class="input-row">
    <div class="input-box">
        <label>Rating</label>
        <input type="text" name="rating" value="<?php echo $row['rating']; ?>">
    </div>

    <div class="input-box">
        <label>Featured Package</label>
        <input type="checkbox" name="featured" value="1"
        <?php if($row['featured']==1) echo "checked"; ?>>
    </div>
</div>

<div class="input-box">
    <label>Package Image</label>
    <input type="file" name="image" accept="image/*">
</div>

<div class="modal-actions">
    <a href="packages.php" class="btn-secondary">Cancel</a>
    <button type="submit" name="update_package" class="btn-primary">Update Package</button>
</div>

</form>
</div>
</div>

</body>
</html>