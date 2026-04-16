<?php
include 'config/db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM destinations WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['name']; ?> - <?php echo $row['tagline']; ?></title>
    <link rel="stylesheet" href="lalibela.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<a href="destinations.php" class="back-btn">
    <i class="fas fa-arrow-left"></i><b> Back to Destinations</b>
</a>

<section class="image-header">
    <div class="image-container">
        <img src="images/<?php echo $row['image1']; ?>" class="main-img" onclick="openModal()">

        <div class="top-icons">
            <span id="favorite" class="fa-regular fa-heart" onclick="toggleActive(this)"></span>
            <span id="share" class="fa-solid fa-share-nodes" onclick="toggleActive(this)"></span>
        </div>

        <div class="text-overlay">
            <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo $row['region']; ?>, Ethiopia
            </div>
            <h2 class="place-name"><?php echo $row['name']; ?></h2>
            <p class="tagline"><?php echo $row['tagline']; ?></p>
        </div>
    </div>
</section>

<section class="gallery-section">
    <div class="gallery-container">
        <p><b>Gallery</b></p>

        <div class="gallery-item active" onclick="changeImage(this,'images/<?php echo $row['image1']; ?>')">
            <img src="images/<?php echo $row['image1']; ?>">
        </div>

        <div class="gallery-item" onclick="changeImage(this,'images/<?php echo $row['image2']; ?>')">
            <img src="images/<?php echo $row['image2']; ?>">
        </div>

        <div class="gallery-item" onclick="changeImage(this,'images/<?php echo $row['image3']; ?>')">
            <img src="images/<?php echo $row['image3']; ?>">
        </div>

        <div class="gallery-item" onclick="changeImage(this,'images/<?php echo $row['image4']; ?>')">
            <img src="images/<?php echo $row['image4']; ?>">
        </div>
    </div>
</section>

<section class="details-section">
    <div class="left-side">

        <div class="overview">
            <div class="overview-header">
                <i class="fa-solid fa-book-open"></i>
                <h2>Overview</h2>
            </div>

            <div class="overview-card">
                <p><?php echo nl2br($row['description']); ?></p>
            </div>
        </div>

        <div class="best-time-section">
            <div class="best-time-header">
                <i class="fa-solid fa-sun"></i>
                <h2>Best Time to Visit</h2>
            </div>

            <div class="best-time-card">
                <h3><?php echo $row['best_time']; ?></h3>
                <p>Best travel season</p>
            </div>
        </div>

    </div>

    <div class="right-side">
        <div class="rating-card">
            <div class="rating-top">
                <i class="fa-solid fa-star"></i>
                <span class="rating-number"><?php echo $row['rating']; ?></span>
            </div>

            <p class="reviews"><?php echo $row['reviews']; ?> reviews</p>

            <a href="booking.php?destination_id=<?php echo $row['id']; ?>">
                <button class="book-btn">Book Now</button>
            </a>
        </div>

        <div class="travel-cost-card">
            <div class="travel-header">
                <i class="fas fa-dollar-sign"></i>
                <h3>Estimated Travel Cost</h3>
            </div>

            <div class="plans">
                <div class="plan">
                    <h4>Budget</h4>
                    <p class="price"><?php echo $row['budget_cost']; ?></p>
                </div>

                <div class="plan">
                    <h4>Standard</h4>
                    <p class="price"><?php echo $row['standard_cost']; ?></p>
                </div>

                <div class="plan">
                    <h4>Luxury</h4>
                    <p class="price"><?php echo $row['luxury_cost']; ?></p>
                </div>
            </div>
        </div>

        <div class="quick-info-card">
            <div class="quick-header">
                <i class="fa-solid fa-circle-info"></i>
                <h3>Quick Info</h3>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-location-dot"></i>
                <div>
                    <h4>Location</h4>
                    <p><?php echo $row['region']; ?></p>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-calendar"></i>
                <div>
                    <h4>Best Time</h4>
                    <p><?php echo $row['best_time']; ?></p>
                </div>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-hotel"></i>
                <div>
                    <h4>Accommodation</h4>
                    <p><?php echo $row['accommodation']; ?></p>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
function toggleActive(el) {
    el.classList.toggle('active');
    if(el.id === 'favorite') {
        el.classList.toggle('fa-regular');
        el.classList.toggle('fa-solid');
    }
}

function changeImage(el, newSrc) {
    const mainImg = document.querySelector(".main-img");
    mainImg.src = newSrc;

    document.querySelectorAll(".gallery-item").forEach(item => {
        item.classList.remove("active");
    });

    el.classList.add("active");
}

function openModal() {
    document.getElementById("imageModal").style.display = "flex";
    document.getElementById("modalImg").src = document.querySelector(".main-img").src;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>

<div id="imageModal" class="modal" onclick="closeModal()">
    <img id="modalImg">
</div>

</body>
</html>