<?php
include 'config/db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM destinations WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Existing destination query...
$row = mysqli_fetch_assoc($result);
// Fetch guides for this specific destination
$guide_sql = "SELECT g.*, 
             (SELECT status FROM guide_availability 
              WHERE guide_id = g.id AND available_date = CURDATE() 
              LIMIT 1) AS current_status
             FROM guides g 
             WHERE g.destination_id = $id";

$guide_result = mysqli_query($conn, $guide_sql);

// DEBUGGING: Remove this once fixed
if (!$guide_result) {
    echo "SQL Error: " . mysqli_error($conn);
} elseif (mysqli_num_rows($guide_result) == 0) {
    echo "<!-- No guides found in database for ID: $id -->";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $row['name']; ?> - <?php echo $row['tagline']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="lalibela.css">
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
                <!-- Replace your existing <a> tag with this -->
<button class="book-btn" onclick="openBookingModal()">Book Now</button>

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

<div class="guides-container" style="margin-top: 20px;">
    <h3><i class="fa-solid fa-person-hiking"></i> Destination Guides</h3>
    <div class="guide-grid" style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 15px;">
        <?php 
        if (mysqli_num_rows($guide_result) > 0): 
            while($guide = mysqli_fetch_assoc($guide_result)): 
                // Handle availability status
                $status = $guide['current_status'] ?? 'available';
                $class = ($status == 'available') ? 'status-available' : 'status-booked';
        ?>
            <div class="guide-mini-card" style="background:#f4f4f4; padding:10px; border-radius:8px; display:flex; align-items:center; gap:10px; min-width:200px;">
                <img src="uploads/<?php echo $guide['image']; ?>" 
                     style="width:50px; height:50px; border-radius:50%; object-fit:cover;" 
                     onerror="this.src='images/default-guide.jpg'">
                <div>
                    <h4 style="margin:0; font-size:14px;"><?php echo htmlspecialchars($guide['name']); ?></h4>
                    <span class="badge <?php echo $class; ?>" style="font-size:10px; padding:2px 5px; color:#fff; border-radius:3px; background:<?php echo ($status=='available'?'#28a745':'#dc3545'); ?>;">
                        <?php echo ucfirst($status); ?>
                    </span>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <p style="color:#888; font-style:italic;">No guides assigned to this destination yet.</p>
        <?php endif; ?>
    </div>
</div>




    </div>
</section>

<!-- Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBookingModal()">&times;</span>
        <h2>Book Your Trip to <?php echo $row['name']; ?></h2>
        <p>Fill in your details and choose an available guide.</p>
        <hr>
        
        <form action="process_booking.php" method="POST">
            <input type="hidden" name="destination_id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label>Travel Date</label>
                <!-- min set to today so past dates can't be picked -->
                <input type="date" name="travel_date" id="travel_date" 
                       min="<?php echo date('Y-m-d'); ?>" required 
                       onchange="fetchAvailableGuides()">
            </div>

            <div class="form-group">
                <label>Number of People</label>
                <input type="number" name="people_count" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label>Choose a Guide</label>
                <select name="guide_id" id="guide_dropdown" required>
                    <option value="">Select a date first...</option>
                </select>
                <small id="guide_msg"></small>
            </div>

            <button type="submit" class="confirm-btn">Confirm Booking</button>
        </form>
    </div>
</div>

<script>
    function openBookingModal() {
    document.getElementById("bookingModal").style.display = "block";
}

function closeBookingModal() {
    document.getElementById("bookingModal").style.display = "none";
}

// Close modal if user clicks outside of the white box
window.onclick = function(event) {
    let modal = document.getElementById("bookingModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function fetchAvailableGuides() {
    const date = document.getElementById('travel_date').value;
    const destId = <?php echo $id; ?>;
    const dropdown = document.getElementById('guide_dropdown');
    const msg = document.getElementById('guide_msg');

    if(!date) return;

    // We fetch from the helper file we discussed earlier
    fetch(`get_available_guides.php?date=${date}&dest_id=${destId}`)
        .then(response => response.json())
        .then(data => {
            dropdown.innerHTML = '<option value="">-- Select Guide --</option>';
            if(data.length === 0) {
                msg.innerHTML = "❌ No guides available for this date.";
                msg.style.color = "red";
            } else {
                msg.innerHTML = "✅ Guides available!";
                msg.style.color = "green";
                data.forEach(guide => {
                    dropdown.innerHTML += `<option value="${guide.id}">${guide.name}</option>`;
                });
            }
        });
}
 
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