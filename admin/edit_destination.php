<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$id = $_GET['id'];

// fetch data
$result = $conn->query("SELECT * FROM destinations WHERE id='$id'");
$row = $result->fetch_assoc();

// helper
function clean($conn, $value) {
    return mysqli_real_escape_string($conn, $value);
}

if (isset($_POST['update_dest'])) {

    $name = clean($conn, $_POST['name']);
    $tagline = clean($conn, $_POST['tagline']);
    $rating = clean($conn, $_POST['rating']);
    $region = clean($conn, $_POST['region']);
    $type = clean($conn, $_POST['type']);
    $description = clean($conn, $_POST['description']);
    $highlights = clean($conn, $_POST['highlights']);

    $best_time = clean($conn, $_POST['best_time']);
    $dry_season = clean($conn, $_POST['dry_season']);
    $rainy_season = clean($conn, $_POST['rainy_season']);

    $spring_weather = clean($conn, $_POST['spring_weather']);
    $summer_weather = clean($conn, $_POST['summer_weather']);
    $autumn_weather = clean($conn, $_POST['autumn_weather']);
    $winter_weather = clean($conn, $_POST['winter_weather']);

    $budget_cost = clean($conn, $_POST['budget_cost']);
    $standard_cost = clean($conn, $_POST['standard_cost']);
    $luxury_cost = clean($conn, $_POST['luxury_cost']);
    $accommodation = clean($conn, $_POST['accommodation']);

    $distance_info = clean($conn, $_POST['distance_info']);
    $map_location = clean($conn, $_POST['map_location']);
    $video_url = clean($conn, $_POST['video_url']);

    // keep old images
    $image1 = $row['image'];
    $image2 = $row['image2'];
    $image3 = $row['image3'];
    $image4 = $row['image4'];

    // uploads
    if (!empty($_FILES['image']['name'])) {
        $image1 = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image1);
    }
    if (!empty($_FILES['image2']['name'])) {
        $image2 = time() . '_' . $_FILES['image2']['name'];
        move_uploaded_file($_FILES['image2']['tmp_name'], "uploads/" . $image2);
    }
    if (!empty($_FILES['image3']['name'])) {
        $image3 = time() . '_' . $_FILES['image3']['name'];
        move_uploaded_file($_FILES['image3']['tmp_name'], "uploads/" . $image3);
    }
    if (!empty($_FILES['image4']['name'])) {
        $image4 = time() . '_' . $_FILES['image4']['name'];
        move_uploaded_file($_FILES['image4']['tmp_name'], "uploads/" . $image4);
    }

    $sql = "UPDATE destinations SET 
        name='$name',
        tagline='$tagline',
        rating='$rating',
        region='$region',
        type='$type',
        description='$description',
        highlights='$highlights',
        best_time='$best_time',
        dry_season='$dry_season',
        rainy_season='$rainy_season',
        spring_weather='$spring_weather',
        summer_weather='$summer_weather',
        autumn_weather='$autumn_weather',
        winter_weather='$winter_weather',
        budget_cost='$budget_cost',
        standard_cost='$standard_cost',
        luxury_cost='$luxury_cost',
        accommodation='$accommodation',
        distance_info='$distance_info',
        map_location='$map_location',
        video_url='$video_url',
        image='$image1',
        image2='$image2',
        image3='$image3',
        image4='$image4'
        WHERE id='$id'";

    if ($conn->query($sql)) {
        header("Location: destinations.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
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
<div class="modal-card large-modal">

<div class="modal-header">
    <div class="header-text">
        <h2>Edit Destination</h2>
        <p>Update complete destination details</p>
    </div>
    <a href="destinations.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

    <div class="input-box">
        <label>Destination Name</label>
        <input type="text" name="name" value="<?php echo $row['name']; ?>">
    </div>

    <div class="input-box">
        <label>Tagline</label>
        <input type="text" name="tagline" value="<?php echo $row['tagline']; ?>">
    </div>

    <div class="input-box">
    <label>Rating (0.0 - 5.0)</label>
    <!-- Use "number" type with min/max and step for decimal support -->
    <input type="number" name="rating" step="0.1" min="0" max="5" 
           value="<?php echo isset($row['rating']) ? $row['rating'] : '0.0'; ?>" 
           placeholder="e.g. 4.5">
</div>


    <div class="input-row">
        <div class="input-box">
            <label>Region</label>
            <input type="text" name="region" value="<?php echo $row['region']; ?>">
        </div>

        <div class="input-box">
            <label>Type</label>
            <select name="type">
                <option value="adventure" <?php if($row['type']=="adventure") echo "selected"; ?>>Adventure</option>
                <option value="cultural" <?php if($row['type']=="cultural") echo "selected"; ?>>Cultural</option>
                <option value="religious" <?php if($row['type']=="religious") echo "selected"; ?>>Religious</option>
                <option value="historical" <?php if($row['type']=="historical") echo "selected"; ?>>Historical</option>
                <option value="nature" <?php if($row['type']=="nature") echo "selected"; ?>>Nature</option>
            </select>
        </div>
    </div>

    <div class="input-box">
        <label>Description</label>
        <textarea name="description"><?php echo $row['description']; ?></textarea>
    </div>

    <div class="input-box">
        <label>Highlights (comma separated)</label>
        <textarea name="highlights"><?php echo $row['highlights']; ?></textarea>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Best Time</label>
            <input type="text" name="best_time" value="<?php echo $row['best_time']; ?>">
        </div>
        <div class="input-box">
            <label>Accommodation</label>
            <input type="text" name="accommodation" value="<?php echo $row['accommodation']; ?>">
        </div>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Dry Season</label>
            <input type="text" name="dry_season" value="<?php echo $row['dry_season']; ?>">
        </div>
        <div class="input-box">
            <label>Rainy Season</label>
            <input type="text" name="rainy_season" value="<?php echo $row['rainy_season']; ?>">
        </div>
    </div>

    <h3>Seasonal Weather</h3>
    <div class="input-row">
        <input type="text" name="spring_weather" value="<?php echo $row['spring_weather']; ?>" placeholder="Spring">
        <input type="text" name="summer_weather" value="<?php echo $row['summer_weather']; ?>" placeholder="Summer">
    </div>
    <div class="input-row">
        <input type="text" name="autumn_weather" value="<?php echo $row['autumn_weather']; ?>" placeholder="Autumn">
        <input type="text" name="winter_weather" value="<?php echo $row['winter_weather']; ?>" placeholder="Winter">
    </div>

    <h3>Travel Cost</h3>
    <div class="input-row">
        <input type="text" name="budget_cost" value="<?php echo $row['budget_cost']; ?>" placeholder="Budget">
        <input type="text" name="standard_cost" value="<?php echo $row['standard_cost']; ?>" placeholder="Standard">
        <input type="text" name="luxury_cost" value="<?php echo $row['luxury_cost']; ?>" placeholder="Luxury">
    </div>

    

    <div class="input-box">
        <label>Distance Info</label>
        <input type="text" name="distance_info" value="<?php echo $row['distance_info']; ?>">
    </div>

    <div class="input-box">
        <label>Google Map Embed Link</label>
        <textarea name="map_location"><?php echo $row['map_location']; ?></textarea>
    </div>

    <div class="input-box">
        <label>YouTube Video URL</label>
        <input type="text" name="video_url" value="<?php echo $row['video_url']; ?>">
    </div>

    <h3>Gallery Images</h3>

    <div class="input-row">
        <input type="file" name="image">
        <input type="file" name="image2">
    </div>

    <div class="input-row">
        <input type="file" name="image3">
        <input type="file" name="image4">
    </div>

    <div class="modal-actions">
        <a href="destinations.php" class="btn-secondary">Cancel</a>
        <button type="submit" name="update_dest" class="btn-primary">Update Destination</button>
    </div>

</form>

</div>
</div>

</body>
</html>