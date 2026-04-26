<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM destinations WHERE id = $id";
$result = $conn->query($sql);

if($result->num_rows == 0){
    die("Destination not found");
}

$row = $result->fetch_assoc();

/* helpers */
$highlights = !empty($row['highlights']) ? explode(",", $row['highlights']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $row['name']; ?></title>
    <link rel="stylesheet" href="lalibela.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php
// Existing logic in admin/view_destination.php
$source = isset($_GET['source']) ? $_GET['source'] : 'admin';

if ($source == 'home') {
    $back_url = "../index.php";        // Back to Home
} elseif ($source == 'public') {
    $back_url = "../destination.php"; // Back to Destinations list
} else {
    $back_url = "destinations.php";    // Back to Admin list
}
?>

<!-- Your existing back button HTML -->
<a href="<?php echo $back_url; ?>" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back
</a>


<!-- ================= HERO IMAGE ================= -->
<section class="image-header">
    <div class="image-container">
        <img class="main-img" src="../uploads/<?php echo $row['image']; ?>">
        <div class="top-icons">
            <span class="fa-regular fa-heart"></span>
            <span class="fa-solid fa-share-nodes"></span>
        </div>
        <div class="text-overlay">
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo $row['region']; ?>
            </div>
            <h2 class="place-name"><?php echo $row['name']; ?></h2>
            <p class="tagline"><?php echo $row['tagline']; ?></p>
        </div>
    </div>
</section>

<!-- ================= GALLERY ================= -->
<section class="gallery-section">
    <div class="gallery-container">
        <?php 
        $images = array_filter([$row['image'], $row['image2'], $row['image3'], $row['image4']]);
        foreach($images as $img): 
        ?>
            <div class="gallery-item">
                <img src="../uploads/<?php echo $img; ?>">
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ================= MAIN DETAILS ================= -->
<section class="details-section">

<div class="left-side">

    <!-- OVERVIEW -->
    <div class="overview">
        <div class="overview-header">
            <i class="fa-solid fa-book-open"></i>
            <h2>Overview</h2>
        </div>
        <div class="overview-card">
            <p><?php echo $row['description']; ?></p>
        </div>
    </div>

    <!-- HIGHLIGHTS -->
    <div class="highlights-section">
        <div class="highlights-title">Highlights</div>
        <div class="highlights-grid">
            <?php foreach($highlights as $h): ?>
            <div class="highlight-card">
                <i class="fas fa-check-circle"></i>
                <span><?php echo trim($h); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- BEST TIME -->
    <div class="best-time-section">
        <div class="best-time-header">
            <i class="fa-solid fa-sun"></i>
            <h2>Best Time to Visit</h2>
        </div>
        <div class="best-time-card">
            <h3><?php echo $row['best_time']; ?></h3>
            <div class="sub-cards-grid">
                <div class="sub-card">
                    <h4>Dry Season</h4>
                    <p><?php echo $row['dry_season']; ?></p>
                </div>
                <div class="sub-card">
                    <h4>Rainy Season</h4>
                    <p><?php echo $row['rainy_season']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- WEATHER -->
    <div class="weather-section">
        <div class="weather-header">
            <i class="fa-solid fa-cloud"></i>
            <h2>Weather Information</h2>
        </div>
        <div class="weather-grid">
            <div class="weather-card"><h3>Spring</h3><p><?php echo $row['spring_weather']; ?></p></div>
            <div class="weather-card"><h3>Summer</h3><p><?php echo $row['summer_weather']; ?></p></div>
            <div class="weather-card"><h3>Autumn</h3><p><?php echo $row['autumn_weather']; ?></p></div>
            <div class="weather-card"><h3>Winter</h3><p><?php echo $row['winter_weather']; ?></p></div>
        </div>
    </div>

    <!-- MAP -->
    <div class="location-map-section">
        <div class="location-map-header">
            <i class="fa-solid fa-location-dot"></i>
            <h2>Location</h2>
        </div>
        <div class="map-container">
            <?php echo $row['map_location']; ?>
        </div>
    </div>

    <!-- VIDEO -->
    <div class="video-gallery-section">
        <div class="video-gallery-header">
            <i class="fa-solid fa-video"></i>
            <h2>Video</h2>
        </div>
        <iframe width="100%" height="315" src="<?php echo $row['video_url']; ?>" allowfullscreen></iframe>
    </div>

</div>

<!-- ================= RIGHT SIDE ================= -->
<div class="right-side">

    <div class="rating-card">
        <div class="rating-top">
            <i class="fa-solid fa-star"></i>
            <span class="rating-number"><?php echo $row['rating']; ?></span>
        </div>
        <p class="reviews"><?php echo $row['reviews']; ?> reviews</p>
        <button class="book-btn">Book Now</button>
    </div>

    <div class="travel-cost-card">
        <h3>Estimated Cost</h3>
        <div class="plan"><h4>Budget</h4><p><?php echo $row['budget_cost']; ?></p></div>
        <div class="plan"><h4>Standard</h4><p><?php echo $row['standard_cost']; ?></p></div>
        <div class="plan"><h4>Luxury</h4><p><?php echo $row['luxury_cost']; ?></p></div>
    </div>

    <div class="quick-info-card">
        <h3>Quick Info</h3>
        <p><b>Region:</b> <?php echo $row['region']; ?></p>
        <p><b>Type:</b> <?php echo $row['type']; ?></p>
        <p><b>Accommodation:</b> <?php echo $row['accommodation']; ?></p>
        <p><b>Distance:</b> <?php echo $row['distance_info']; ?></p>
    </div>

</div>
</section>

</body>
</html>
