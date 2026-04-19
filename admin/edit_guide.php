<?php
$conn = new mysqli("localhost","root","","travel_db");

$id = $_GET['id'];

$guide = $conn->query("SELECT * FROM guides WHERE id='$id'")->fetch_assoc();

if(isset($_POST['update_guide'])){

    $destination_id = $_POST['destination_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $language = $_POST['language'];
    $experience = $_POST['experience_years'];
    $rating = $_POST['rating'];

    $image = $guide['image'];

    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "UPDATE guides SET
            destination_id='$destination_id',
            name='$name',
            phone='$phone',
            language='$language',
            experience_years='$experience',
            rating='$rating',
            image='$image'
            WHERE id='$id'";

    if($conn->query($sql)){
        header("Location: guides.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Guide</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<div class="modal-overlay">
<div class="modal-card">

<div class="modal-header">
    <h2>Edit Guide</h2>
    <a href="guides.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

<div class="input-box">
<label>Destination</label>
<select name="destination_id" required>
<option value="">Select Destination</option>

<?php
$destinations = $conn->query("SELECT id,name FROM destinations ORDER BY name ASC");
while($dest = $destinations->fetch_assoc()):
?>
<option value="<?php echo $dest['id']; ?>"
<?php if($guide['destination_id']==$dest['id']) echo "selected"; ?>>
<?php echo $dest['name']; ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="input-box">
<label>Guide Name</label>
<input type="text" name="name" value="<?php echo $guide['name']; ?>" required>
</div>

<div class="input-box">
<label>Phone</label>
<input type="text" name="phone" value="<?php echo $guide['phone']; ?>">
</div>

<div class="input-box">
<label>Languages</label>
<input type="text" name="language" value="<?php echo $guide['language']; ?>">
</div>

<div class="input-row">
<div class="input-box">
<label>Experience Years</label>
<input type="number" name="experience_years" value="<?php echo $guide['experience_years']; ?>">
</div>

<div class="input-box">
<label>Rating</label>
<input type="text" name="rating" value="<?php echo $guide['rating']; ?>">
</div>
</div>

<div class="input-box">
<label>Guide Photo</label>
<input type="file" name="image">
</div>

<div class="modal-actions">
<a href="guides.php" class="btn-secondary">Cancel</a>
<button type="submit" name="update_guide" class="btn-primary">Update Guide</button>
</div>

</form>
</div>
</div>

</body>
</html>