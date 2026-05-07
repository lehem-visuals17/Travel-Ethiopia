<?php
// 1. Enable error reporting to see hidden PHP errors
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php?msg=Please login to book a trip");
    exit();
}
$user_id = $_SESSION['user_id'];
// 2. Validate the Connection (Crucial)
if (!$conn) {
    die("CRITICAL ERROR: Connection to database failed: " . mysqli_connect_error());
}

// 3. Get and validate the ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // 4. Fetch destination details
    $sql = "SELECT * FROM destinations WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    
    // Check if the query returned anything
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        // This runs if the ID exists in the URL but not in your database table
        die("DATABASE ERROR: No destination found with ID: " . $id . ". Check your 'destinations' table in phpMyAdmin.");
    }

    // 5. Fetch guides for this specific destination
    $guide_sql = "SELECT g.*, 
             (SELECT status FROM guide_availability 
              WHERE guide_id = g.id AND available_date = CURDATE() 
              LIMIT 1) AS current_status
             FROM guides g 
             WHERE g.destination_id = $id";


    $guide_result = mysqli_query($conn, $guide_sql);
} else {
    // This runs if you visit the page without ?id=1 in the URL
    die("URL ERROR: No valid ID detected. Please ensure your URL ends with ?id=1 (or another number).");
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

<a href="destination.php" class="back-btn">
    <i class="fas fa-arrow-left"></i><b> Back to Destinations</b>
</a>

<section class="image-header">
    <div class="image-container">
        <img src="images/<?php echo $row['image']; ?>" class="main-img" onclick="openModal()">

        <div class="top-icons">
            <span id="favorite" class="fa-regular fa-heart" onclick="toggleActive(this)"></span>
            <span id="share" class="fa-solid fa-share-nodes" onclick="toggleActive(this)"></span>
        </div>

        <div class="text-overlay">
    <?php if ($row): ?>
        <div class="location">
            <i class="fas fa-map-marker-alt"></i>
            <?php echo htmlspecialchars($row['region']); ?>, Ethiopia
        </div>
        <h2 class="place-name"><?php echo htmlspecialchars($row['name']); ?></h2>
        <p class="tagline"><?php echo htmlspecialchars($row['tagline']); ?></p>
    <?php else: ?>
        <p>Destination details could not be found.</p>
    <?php endif; ?>
</div>

    </div>
</section>

<section class="gallery-section">
    <div class="gallery-container">
        <p><b>Gallery</b></p>

        <div class="gallery-item active" onclick="changeImage(this,'images/<?php echo $row['image']; ?>')">
            <img src="images/<?php echo $row['image']; ?>">
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
                <h2><output>Overview</output></h2>
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
<button class="book-btn" onclick="openBookingModal(event)">Book Now</button>


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

<!-- Main Booking Modal -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBookingModal()">&times;</span>
        <h2>Book Your Trip</h2>
        <form id="bookingForm">
            <input type="hidden" name="destination_id" value="<?php echo $id; ?>">
            <input type="hidden" id="dest_name" value="<?php echo $row['name']; ?>">
            <!-- Inside <form id="bookingForm"> -->
<input type="hidden" id="dest_price" value="<?php echo $row['average_cost']; ?>">

            <div class="form-group">
                <label>Travel Date</label>
                <input type="date" name="travel_date" id="travel_date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Number of People</label>
                <input type="number" name="people_count" id="people_count" min="1" value="1" required>
            </div>

            <div class="form-group">
    <label>Choose a Guide</label>
    <select name="guide_id" id="guide_dropdown" required>
        <option value="">-- Select a Guide --</option>
        <?php 
        // CRITICAL: Move the pointer back to the first guide
        if ($guide_result && mysqli_num_rows($guide_result) > 0) {
            mysqli_data_seek($guide_result, 0); 
            
            while($guide = mysqli_fetch_assoc($guide_result)) {
                $status = $guide['current_status'] ?? 'available';
                $disabled = ($status != 'available') ? 'disabled' : '';
                $suffix = ($status != 'available') ? ' (Booked)' : '';
                
                echo '<option value="'.$guide['id'].'" '.$disabled.'>'
                     .htmlspecialchars($guide['name']).$suffix.
                     '</option>';
            }
        } else {
            echo '<option value="">No guides found for this destination</option>';
        }
        ?>
    </select>
</div>


            <!-- New Pay Button -->
            <button type="button" class="confirm-btn" onclick="openPaymentModal()">Proceed to Pay</button>
        </form>
    </div>
</div>


<!-- Payment Modal -->
<div id="paymentModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closePaymentModal()">&times;</span>
        <h2>Checkout & Payment</h2>
        <hr>
        
        <div id="paymentSummary" style="background: #f9f9f9; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
            <!-- Summary will be populated by JavaScript -->
        </div>

        <form action="process_booking.php" method="POST" id="finalPaymentForm">
            <!-- Hidden inputs to send to PHP -->
            <input type="hidden" name="destination_id" id="final_dest_id">
            <input type="hidden" name="travel_date" id="final_travel_date">
            <input type="hidden" name="people_count" id="final_people_count">
            <input type="hidden" name="guide_id" id="final_guide_id">
            <input type="hidden" name="total_amount" id="final_total_amount">

            <div class="form-group">
                <label>Payment Method</label>
                <select name="method" id="payment_method" onchange="togglePaymentInputs()" required>
                    <option value="card">Credit Card</option>
                    <option value="mobile">Telebirr / CBE Birr / M-Pesa</option>
                    <option value="cash">Cash on Arrival</option>
                </select>
            </div>

            <!-- Fake Inputs Container -->
            <div id="card_inputs">
                <input type="text" placeholder="Card Holder Name" class="form-control">
                <input type="text" placeholder="Card Number (XXXX-XXXX-XXXX-XXXX)" class="form-control">
            </div>
            
            <div id="mobile_inputs" style="display:none;">
                <input type="text" placeholder="Phone Number (09...)" class="form-control">
            </div>

            <div class="form-group" style="margin-top:15px; border: 1px solid #ffcccc; padding: 10px; border-radius: 5px;">
                <label style="color: #d9534f;"><strong>Confirm Account Password to Pay</strong></label>
                <input type="password" name="confirm_password" placeholder="Enter your password" required class="form-control">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <input type="checkbox" id="terms_check" required>
                <label for="terms_check">I agree with the terms and cancellation policy</label>
            </div>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="button" class="cancel-btn" onclick="closePaymentModal()" style="background:#888;">Cancel</button>
                <button type="submit" class="confirm-btn">Confirm & Pay</button>
            </div>
        </form>
    </div>
</div>



<script>
   function openBookingModal(event) {
    // 1. Prevent the page from refreshing or navigating
    if (event) event.preventDefault(); 
    
    // 2. Open the modal
    var modal = document.getElementById("bookingModal");
    if (modal) {
        modal.style.display = "block";
    }
}


function closeBookingModal() {
    var modal = document.getElementById("bookingModal");
    if (modal) {
        modal.style.display = "none";
    }
}

// Optional: Close modal if user clicks outside of the box
window.onclick = function(event) {
    var modal = document.getElementById("bookingModal");
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
function openPaymentModal() {
    console.log("Attempting to open Payment Modal...");

    try {
        // 1. Get references to inputs
        const destNameEl = document.getElementById('dest_name');
        const priceEl = document.getElementById('dest_price');
        const peopleEl = document.getElementById('people_count');
        const guideEl = document.getElementById('guide_dropdown');
        const dateEl = document.getElementById('travel_date');

        // 2. Debugging logs (Check these in F12 Console if it fails)
        console.log("Price from DB:", priceEl ? priceEl.value : "NOT FOUND");
        console.log("People Count:", peopleEl ? peopleEl.value : "NOT FOUND");

        // 3. Validation
        if (!dateEl.value) { alert("Please select a date."); return; }
        if (!guideEl.value) { alert("Please select a guide."); return; }

        // 4. Calculations
        const people = parseInt(peopleEl.value) || 1;
        const price = parseFloat(priceEl.value) || 0;
        const total = (people * price).toFixed(2);
        
        const guideName = guideEl.options[guideEl.selectedIndex].text;

        // 5. Update Payment UI
        document.getElementById('paymentSummary').innerHTML = `
            <p><strong>Destination:</strong> ${destNameEl.value}</p>
            <p><strong>Guide:</strong> ${guideName}</p>
            <p><strong>Date:</strong> ${dateEl.value}</p>
            <p><strong>Travelers:</strong> ${people}</p>
            <p style="font-size: 1.4em; color: #f08d3b; border-top: 2px solid #eee; margin-top:10px; padding-top:10px;">
                <strong>Total Amount: $${total}</strong>
            </p>
        `;

        // 6. Set values for the final form
        document.getElementById('final_dest_id').value = document.getElementsByName('destination_id')[0].value;
        document.getElementById('final_travel_date').value = dateEl.value;
        document.getElementById('final_people_count').value = people;
        document.getElementById('final_guide_id').value = guideEl.value;
        document.getElementById('final_total_amount').value = total;

        // 7. Show/Hide Modals
        document.getElementById('bookingModal').style.display = 'none';
        document.getElementById('paymentModal').style.display = 'block';

    } catch (err) {
        console.error("Payment Modal Error: ", err.message);
        alert("An error occurred. Check the browser console (F12) for details.");
    }
}




function togglePaymentInputs() {
    const method = document.getElementById('payment_method').value;
    document.getElementById('card_inputs').style.display = (method === 'card') ? 'block' : 'none';
    document.getElementById('mobile_inputs').style.display = (method === 'mobile') ? 'block' : 'none';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

</script>

<div id="imageModal" class="modal" onclick="closeModal()">
    <img id="modalImg">
</div>

</body>
</html>