<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

function clean($conn, $data){
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}

if(isset($_POST['create_dest'])){

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

    $images = ['', '', '', ''];
    $file_keys = ['image', 'image2', 'image3', 'image4'];

    foreach($file_keys as $index => $key) {
        if(!empty($_FILES[$key]['name'])){
            $filename = time().'_'.$_FILES[$key]['name'];
            if(move_uploaded_file($_FILES[$key]['tmp_name'], "uploads/".$filename)) {
                $images[$index] = $filename;
            }
        }
    }

    $sql = "INSERT INTO destinations (
        name, tagline,rating, region, type, description, highlights,
        best_time, dry_season, rainy_season,
        spring_weather, summer_weather, autumn_weather, winter_weather,
        budget_cost, standard_cost, luxury_cost, accommodation,
        distance_info, map_location, video_url,
        image, image2, image3, image4
    ) VALUES (
        '$name','$tagline',$rating,'$region','$type','$description','$highlights',
        '$best_time','$dry_season','$rainy_season',
        '$spring_weather','$summer_weather','$autumn_weather','$winter_weather',
        '$budget_cost','$standard_cost','$luxury_cost','$accommodation',
        '$distance_info','$map_location','$video_url',
        '{$images[0]}','{$images[1]}','{$images[2]}','{$images[3]}'
    )";

    if($conn->query($sql)){
        header("Location: destinations.php");
        exit();
    } else {
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
<div class="modal-card large-modal">

<div class="modal-header">
    <div class="header-text">
        <h2>Add New Destination</h2>
        <p>Enter complete destination details</p>
    </div>
    <a href="destinations.php" class="close-x">&times;</a>
</div>

<form method="POST" enctype="multipart/form-data" class="modal-form">

    <div class="input-box">
        <label>Destination Name</label>
        <input type="text" name="name" placeholder="e.g. Lalibela">
    </div>

    <div class="input-box">
        <label>Tagline</label>
        <input type="text" name="tagline" placeholder="e.g. The New Jerusalem of Africa">
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
            <input type="text" name="region" placeholder="e.g. Amhara Region">
        </div>

        <div class="input-box">
            <label>Type</label>
            <select name="type">
                <option value="adventure">Adventure</option>
                <option value="cultural">Cultural</option>
                <option value="religious">Religious</option>
                <option value="historical">Historical</option>
                <option value="nature">Nature</option>
            </select>
        </div>
    </div>

    <div class="input-box">
        <label>Description</label>
        <textarea name="description" placeholder="Write a full overview..."></textarea>
    </div>

    <div class="input-box">
        <label>Highlights (comma separated)</label>
        <textarea name="highlights" placeholder="Example: UNESCO World Heritage Site, Rock-Hewn Churches"></textarea>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Best Time</label>
            <input type="text" name="best_time" placeholder="e.g. October to March">
        </div>
        <div class="input-box">
            <label>Accommodation</label>
            <input type="text" name="accommodation" placeholder="e.g. Hotels, lodges">
        </div>
    </div>

    <div class="input-row">
        <div class="input-box">
            <label>Dry Season</label>
            <input type="text" name="dry_season" placeholder="e.g. October to May">
        </div>
        <div class="input-box">
            <label>Rainy Season</label>
            <input type="text" name="rainy_season" placeholder="e.g. June to September">
        </div>
    </div>

    <h3>Seasonal Weather</h3>
    <div class="input-row">
        <input type="text" name="spring_weather" placeholder="Spring weather">
        <input type="text" name="summer_weather" placeholder="Summer weather">
    </div>
    <div class="input-row">
        <input type="text" name="autumn_weather" placeholder="Autumn weather">
        <input type="text" name="winter_weather" placeholder="Winter weather">
    </div>

    <h3>Travel Cost</h3>
    <div class="input-row">
        <input type="text" name="budget_cost" placeholder="Budget cost" >
        <input type="text" name="standard_cost" placeholder="Standard cost">
        <input type="text" name="luxury_cost" placeholder="Luxury cost">
    </div>

    <div class="input-box">
        <label>Distance Info</label>
        <input type="text" name="distance_info" placeholder="e.g. 642 km (8–10 hours by road from Addis Ababa)">
    </div>

    <div class="input-box">
        <label>Google Map Embed Link</label>
        <textarea name="map_location" placeholder="Paste Google Maps embed iframe link here"></textarea>
    </div>

    <div class="input-box">
        <label>YouTube Video URL</label>
        <input type="text" name="video_url" placeholder="e.g. https://www.youtube.com/embed/example">
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
        <button type="submit" name="create_dest" class="btn-primary">Create Destination</button>
    </div>

</form>
</div>
</div>

</body>
</html>