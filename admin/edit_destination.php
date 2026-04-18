<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$id = $_GET['id'];

$result = $conn->query("SELECT * FROM destinations WHERE id='$id'");
$row = $result->fetch_assoc();

if(isset($_POST['update_dest'])){

    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $region = $_POST['region'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $budget = $_POST['budget_cost'];
    $standard = $_POST['standard_cost'];
    $luxury = $_POST['luxury_cost'];
    $best_time = $_POST['best_time'];
    $weather = $_POST['weather_info'];

    $image_name = $row['image'];

    if(!empty($_FILES['image']['name'])){
        $image_name = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image_name);
    }

    $sql = "UPDATE destinations SET
        name='$name',
        tagline='$tagline',
        region='$region',
        type='$type',
        description='$description',
        budget_cost='$budget',
        standard_cost='$standard',
        luxury_cost='$luxury',
        best_time='$best_time',
        weather_info='$weather',
        image='$image_name'
        WHERE id='$id'";

    if($conn->query($sql)){
        header("Location: destinations.php");
        exit();
    }else{
        echo $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="modal.css">
</head>
<body>

<div class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <div class="header-text">
                <h2>Edit Destination</h2>
                <p>Update destination details</p>
            </div>
            <a href="destinations.php" class="close-x">&times;</a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="modal-form">
    
    <div class="input-box">
        <label>Destination Name</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
    </div>

    <div class="input-box">
        <label>Tagline</label>
        <input type="text" name="tagline" value="<?php echo $row['tagline']; ?>">
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Region</label>
            <select name="region" required>
                <option value="">Select region</option>
                <option value="Amhara" <?php if($row['region']=="Amhara") echo "selected"; ?>>Amhara</option>
                <option value="Tigray" <?php if($row['region']=="Tigray") echo "selected"; ?>>Tigray</option>
                <option value="Oromia" <?php if($row['region']=="Oromia") echo "selected"; ?>>Oromia</option>
                <option value="Southern" <?php if($row['region']=="Southern") echo "selected"; ?>>Southern</option>
            </select>
        </div>

        <div class="input-box">
            <label>Type</label>
            <select name="type" required>
                <option value="">Select type</option>
                <option value="adventure" <?php if($row['type']=="adventure") echo "selected"; ?>>Adventure</option>
                <option value="cultural" <?php if($row['type']=="cultural") echo "selected"; ?>>Cultural</option>
                <option value="religious" <?php if($row['type']=="religious") echo "selected"; ?>>Religious</option>
            </select>
        </div>
    </div>

    <div class="input-box">
        <label>Description</label>
        <textarea name="description"><?php echo $row['description']; ?></textarea>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Budget Cost ($)</label>
            <input type="text" name="budget_cost" value="<?php echo $row['budget_cost']; ?>">
        </div>

        <div class="input-box">
            <label>Standard Cost ($)</label>
            <input type="text" name="standard_cost" value="<?php echo $row['standard_cost']; ?>">
        </div>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Luxury Cost ($)</label>
            <input type="text" name="luxury_cost" value="<?php echo $row['luxury_cost']; ?>">
        </div>

        <div class="input-box">
            <label>Best Time to Visit</label>
            <input type="text" name="best_time" value="<?php echo $row['best_time']; ?>">
        </div>
    </div>

    <div class="input-box">
        <label>Weather Info</label>
        <input type="text" name="weather_info" value="<?php echo $row['weather_info']; ?>">
    </div>

    <div class="input-box">
        <label>Current Image</label><br>
        <img src="../uploads/<?php echo $row['image']; ?>" width="120">
    </div>

    <div class="input-box">
        <label>Change Image</label>
        <input type="file" name="image" class="file-input">
    </div>

    <div class="modal-actions">
        <a href="destinations.php" class="btn-secondary" style="text-decoration:none; text-align:center;">Cancel</a>
        <button type="submit" name="update_dest" class="btn-primary">Update Destination</button>
    </div>

</form>
    </div>
</div>

</body>
</html>
