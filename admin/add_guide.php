<?php
$conn = new mysqli("localhost","root","","travel_db");

if(isset($_POST['add_guide'])){

    $destination_id = $_POST['destination_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $language = $_POST['language'];
    $experience = $_POST['experience_years'];
    $rating = $_POST['rating'];

   $image = "";

if(!empty($_FILES['image']['name'])){
    $image = time() . "_" . basename($_FILES['image']['name']);
    $target = "../uploads/" . $image;

    move_uploaded_file($_FILES['image']['tmp_name'], $target);
}

   $sql = "INSERT INTO guides
(destination_id, name, phone, language, experience_years, rating, image)
VALUES
('$destination_id', '$name', '$phone', '$language', '$experience', '$rating', '$image')";
    if($conn->query($sql)){
        header("Location: guides.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ethiopian Destinations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="modal.css">
    
</head>
<body>
  <div class="modal-overlay">
<div class="modal-card">

<div class="modal-header">
    <h2>Add New Guide</h2>
    <a href="guides.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

<div class="input-box">
    <label>Destination</label>
    <select name="destination_id" required>
        <option value="">Select Destination</option>

        <?php
        $dest_query = "SELECT id, name FROM destinations ORDER BY name ASC";
        $dest_result = $conn->query($dest_query);

        while($dest = $dest_result->fetch_assoc()){
        ?>
            <option value="<?php echo $dest['id']; ?>">
                <?php echo $dest['name']; ?>
            </option>
        <?php } ?>

    </select>
</div>

<div class="input-box">
<label>Guide Name</label>
<input type="text" name="name" required>
</div>

<div class="input-box">
<label>Phone</label>
<input type="text" name="phone">
</div>

<div class="input-box">
<label>Languages</label>
<input type="text" name="language" placeholder="Amharic, English">
</div>

<div class="input-row">
<div class="input-box">
<label>Experience Years</label>
<input type="number" name="experience_years">
</div>

<div class="input-box">
<label>Rating</label>
<input type="text" name="rating">
</div>
</div>

<div class="input-box">
<label>Guide Photo</label>
<input type="file" name="image">
</div>

<div class="modal-actions">
<a href="guides.php" class="btn-secondary">Cancel</a>
<button type="submit" name="add_guide" class="btn-primary">Add Guide</button>
</div>

</form>
</div>
</div>

</body></html>
