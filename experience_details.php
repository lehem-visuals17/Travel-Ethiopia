<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('db.php'); 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $conn->query("SELECT * FROM experiences WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: experience.php");
    exit();
}
$row = $result->fetch_assoc();

// Decode simple lists safely
$included = !empty($row['whats_included']) ? explode("\n", $row['whats_included']) : [];
$not_included = !empty($row['not_included']) ? explode("\n", $row['not_included']) : [];

// Protect against null values to stop loop crashes
$itinerary = !empty($row['itinerary']) ? json_decode($row['itinerary'], true) : [];
if (!is_array($itinerary)) { 
    $itinerary = []; 
}

$gallery = !empty($row['gallery_images']) ? json_decode($row['gallery_images'], true) : [];
if (!is_array($gallery)) { 
    $gallery = []; 
}

// Fetch related experiences ("You might also like")
$related_result = $conn->query("SELECT * FROM experiences WHERE status = 'Active' AND id != $id ORDER BY RAND() LIMIT 3");

// --- POST TRANSACTION ENGINE ---
if (isset($_POST['execute_package_booking'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
    
    $active_user_id = $_SESSION['user_id'];
    $experience_record_id = intval($_POST['experience_id']);
    $assigned_guide_id = !empty($_POST['guide_id']) ? intval($_POST['guide_id']) : NULL;
    $travel_date_string = $_POST['travel_date'];
    $total_headcount = intval($_POST['people_count']);
    $aggregated_pricing = floatval($_POST['total_price']);
    
    // UI input extraction mapping
    $ui_payment_mode = $_POST['payment_mode']; // 'now' or 'later'
    $ui_gateway = $_POST['payment_gateway'];

    $booking_status = 'pending'; // matching your exact enum('pending','confirmed','cancelled')
    $payment_status = ($ui_payment_mode === 'now') ? 'paid' : 'unpaid'; // matching your exact enum('unpaid','paid','refunded')

    // FIXED: Corrected $final_payment_status to match defined $payment_status variable reference
    $booking_statement = $conn->prepare("INSERT INTO bookings (user_id, experience_id, guide_id, travel_date, people_count, status, payment_status, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $booking_statement->bind_param("iiisissd", $active_user_id, $experience_record_id, $assigned_guide_id, $travel_date_string, $total_headcount, $booking_status, $payment_status, $aggregated_pricing);

    if ($booking_statement->execute()) {
        $new_booking_id = $conn->insert_id;
        $booking_statement->close();

        // 3. MAP TO EXACT ENUM VALUES FOR PAYMENTS TABLE
        // payment_type: enum('normal','premium')
        $payment_type = 'normal'; 
        
        // method: enum('cash','bank','mobile','card')
        $payment_method = 'cash'; 
        if ($ui_gateway === 'telebirr' || $ui_gateway === 'cbe_birr') {
            $payment_method = 'mobile';
        } elseif ($ui_gateway === 'card') {
            $payment_method = 'card';
        }

        // status: enum('pending','completed','failed','rejected')
        $actual_pay_status = ($ui_payment_mode === 'now') ? 'completed' : 'pending';

        // 4. INSERT INTO PAYMENTS TABLE
        $payment_statement = $conn->prepare("INSERT INTO payments (booking_id, user_id, payment_type, method, amount, status) VALUES (?, ?, ?, ?, ?, ?)");
        $payment_statement->bind_param("iisdds", $new_booking_id, $active_user_id, $payment_type, $payment_method, $aggregated_pricing, $actual_pay_status);
        $payment_statement->execute();
        $payment_statement->close();

        echo "<script>
                alert('Successfully Booked! Your booking has been saved as pending approval, and your payment method was securely recorded.'); 
                window.location.href='users/profile.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Processing Error Encountered: " . $conn->escape_string($booking_statement->error) . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($row['name']); ?> - Betora Travels</title>
   <link rel="stylesheet" href="experience-details.css">
   <!-- Added public experience stylesheet here to make sure related cards look identical -->
   <link rel="stylesheet" href="experience.css"> 
   <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
  <div class="main-details-container">
    
    <nav class="back-nav">
      <a href="experience.php" class="back-btn">
        <i class="fa-solid fa-chevron-left"></i> Back to Experiences
      </a>
    </nav>

    <header class="detail-hero" style="background-image: url('admin/uploads/<?php echo htmlspecialchars($row['image']); ?>');">
      <div class="hero-tint">
        <div class="hero-content-wrapper">
          <span class="category-tag"><?php echo htmlspecialchars($row['category']); ?></span>
          <h1><?php echo htmlspecialchars($row['name']); ?></h1>
          <div class="hero-meta">
            <span class="rating"><i class="fa-solid fa-star"></i> 4.8</span>
            <span class="location"><i class="fa-solid fa-map-pin"></i> <?php echo htmlspecialchars($row['location']); ?></span>
          </div>
        </div>
      </div>
    </header>

    <div class="content-wrapper">
      <div class="main-info">
        
        <!-- Top Metrics Bar -->
        <div class="quick-stats">
          <div class="stat"><i class="fa-solid fa-clock"></i> <div><strong>Duration</strong><br><?php echo htmlspecialchars($row['duration']); ?></div></div>
          <div class="stat"><i class="fa-solid fa-users"></i> <div><strong>Group Size</strong><br>Up to <?php echo $row['capacity']; ?> people</div></div>
          <div class="stat"><i class="fa-solid fa-calendar"></i> <div><strong>Schedule</strong><br><?php echo htmlspecialchars($row['schedule']); ?></div></div>
          <div class="stat"><i class="fa-solid fa-language"></i> <div><strong>Languages</strong><br><?php echo htmlspecialchars($row['languages']); ?></div></div>
        </div>

        <section class="about-section">
          <h3>About This Experience</h3>
          <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        </section>

        <!-- Lists Grid -->
        <div class="lists-grid">
          <div class="list-box">
            <h4>What's Included</h4>
            <ul>
              <?php foreach($included as $inc): ?>
                <li><i class="fa-solid fa-check text-success"></i> <?php echo htmlspecialchars(trim($inc)); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="list-box">
            <h4>Not Included</h4>
            <ul class="not-included">
              <?php foreach($not_included as $not): ?>
                <li><i class="fa-solid fa-times text-danger"></i> <?php echo htmlspecialchars(trim($not)); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <!-- Itinerary Section -->
        <section class="itinerary-section">
          <h3>Itinerary</h3>
          <div class="itinerary-list">
            <?php $count = 1; foreach($itinerary as $step): ?>
              <div class="itinerary-item">
                <div class="circle"><?php echo $count++; ?></div>
                <div class="itinerary-content">
                  <strong><?php echo htmlspecialchars($step['title']); ?></strong> <span class="time"><?php echo htmlspecialchars($step['time']); ?></span>
                  <p><?php echo htmlspecialchars($step['desc']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Gallery Section -->
        <section class="gallery-section">
          <h3>Gallery</h3>
          <div class="gallery-grid">
            <?php foreach($gallery as $img): ?>
              <img src="uploads/<?php echo htmlspecialchars($img); ?>" alt="Gallery Image">
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Location Section -->
        <section class="location-section">
          <h3>Location</h3>
          <div class="map-placeholder">
            <i class="fa-solid fa-map-location-dot"></i>
            <h4><?php echo htmlspecialchars($row['location']); ?></h4>
            <p>Coordinates: <?php echo htmlspecialchars($row['coordinates']); ?></p>
          </div>
        </section>

        <!-- You Might Also Like (Using Public Experience Cards Structure) -->
        <section class="related-section">
          <h3>You Might Also Like</h3>
          <div class="experience-container">
            <?php while($rel = $related_result->fetch_assoc()): ?>
              <div class="experience-card">
                <img src="uploads/<?php echo htmlspecialchars($rel['image']); ?>" alt="<?php echo htmlspecialchars($rel['name']); ?>">
                <div class="card-content">
                  <span class="category"><?php echo htmlspecialchars($rel['category']); ?></span>
                  <h3><?php echo htmlspecialchars($rel['name']); ?></h3>
                  <p><?php echo htmlspecialchars(substr($rel['description'], 0, 150)) . '...'; ?></p>
                  
                  <div class="info-line">
                    <span class="duration"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($rel['duration']); ?></span>
                    <span class="price">$<?php echo number_format($rel['price']); ?></span>
                  </div>
                  
                  <div class="rating">
                    <i class="fa-solid fa-star"></i> <?php echo ($rel['is_featured'] == 1) ? '4.9' : '4.5'; ?> (<?php echo rand(50, 400); ?>)
                  </div>
                  
                  <a href="experience_details.php?id=<?php echo $rel['id']; ?>" style="text-decoration:none;">
                    <button class="book-btn">View Experience</button>
                  </a>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </section>
      </div>

      <!-- Right Column Sticky Sidebar (Updated Form) -->
     <aside class="sidebar">
    <form id="sidebarBookingForm" method="POST" class="booking-card">
        <!-- Hidden elements to handle values for PHP database submission -->
        <input type="hidden" name="package_id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="package_price" id="base_pkg_price" value="<?php echo $row['price']; ?>">
        <input type="hidden" name="total_price" id="final_calculated_total" value="<?php echo $row['price']; ?>">
        
        <div class="price-header">
            <span class="amount">$<?php echo number_format($row['price']); ?></span>
            <span class="per">per person</span>
        </div>
        
        <div class="booking-group">
            <label>Select Date</label>
            <input type="date" name="travel_date" class="booking-input" required min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="booking-group">
            <label>Number of Guests</label>
            <div class="guest-counter">
                <!-- Data attributes dynamically pass updates to calculation engine -->
                <button type="button" class="counter-btn" onclick="modifyGuests('down')">-</button>
                <input type="number" id="sidebar_people_count" name="people_count" value="1" min="1" max="<?php echo $row['max_people'] ?? 10; ?>" readonly>
                <button type="button" class="counter-btn" onclick="modifyGuests('up')">+</button>
            </div>
        </div>

        <!-- NEW: Dynamic Guide Selection Dropdown -->
        <div class="booking-group">
            <label>Choose a Tour Guide</label>
            <select name="guide_id" id="sidebar_guide_select" class="booking-input" onchange="runLiveCalculations()">
                <option value="" data-fee="0">No Guide Needed ($0)</option>
                <?php 
                // Fetches active registered guides from user table
                $guides_list = $conn->query("SELECT id, fullname FROM users WHERE role='tour_guide'");
                while($g = $guides_list->fetch_assoc()): ?>
                    <option value="<?php echo $g['id']; ?>" data-fee="40"><?php echo htmlspecialchars($g['fullname']); ?> (+$40 Guide Fee)</option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Real-time Cost breakdown layout -->
        <div class="booking-cost-breakdown" style="background: #fafafa; padding: 12px; border-radius: 6px; margin: 15px 0; font-size: 14px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Subtotal (<span id="txt_guest_count">1</span> travelers):</span>
                <span id="txt_subtotal">$<?php echo number_format($row['price']); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                <span>Guide Fee:</span>
                <span id="txt_guide_fee">$0</span>
            </div>
            <hr style="border: 0; border-top: 1px dashed #ddd; margin: 8px 0;">
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px; color: #ff9800;">
                <span>Total Amount:</span>
                <span id="txt_final_total">$<?php echo number_format($row['price']); ?></span>
            </div>
        </div>

        <!-- Demo Payment Gateway Selector -->
        <div class="booking-group payment-selection" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 8px;">Payment Options</label>
            <div style="display: flex; gap: 15px;">
                <label style="cursor:pointer;"><input type="radio" name="payment_mode" value="now" checked> Pay Now</label>
                <label style="cursor:pointer;"><input type="radio" name="payment_mode" value="later"> Pay Later</label>
            </div>
        </div>

        <!-- Keep your form setup exactly the same, just look at the button change below -->
<button type="button" id="sidebarBookBtn" class="book-now-btn" style="background:#ff9800; border:none; color:white; font-weight:bold; cursor:pointer; width:100%; padding:14px; border-radius:6px;">Book Now</button>

        <ul class="trust-list">
            <li><i class="fa-solid fa-check"></i> Free cancellation up to 24 hours</li>
            <li><i class="fa-solid fa-check"></i> Instant confirmation</li>
            <li><i class="fa-solid fa-check"></i> Mobile voucher accepted</li>
        </ul>
    </form>
</aside>


    </div>
  </div>

<!-- Enhanced Payment Modal Component Layer -->
<div id="paymentModalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:30px; width:95%; max-width:500px; border-radius:12px; box-shadow:0 15px 40px rgba(0,0,0,0.3); position:relative; font-family:sans-serif;">
        <span onclick="closePaymentModal()" style="position:absolute; top:15px; right:20px; font-size:26px; cursor:pointer; color:#888; font-weight:bold;">&times;</span>
        
        <h2 style="margin-top:0; color:#004d40; font-size:22px;">Select Payment Method</h2>
        <p style="color:#666; font-size:13px; margin-bottom:15px;">Choose your preferred payment gateway to finalize your reservation safely.</p>
        
        <div style="background:#fef9f3; padding:12px 15px; border-radius:6px; border:1px solid #ffe0b2; margin-bottom:20px; display:flex; justify-content:between; align-items:center;">
            <span style="font-weight:bold; color:#333;">Total Amount Due:</span>
            <span style="font-weight:800; color:#ff9800; font-size:20px;">$<span id="pay_modal_total">0.00</span></span>
        </div>

        <!-- Payment Options Accordion Grid -->
        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px; max-height:280px; overflow-y:auto; padding-right:5px;">
            
            <!-- Option 1: Telebirr / Chapa Gateway -->
            <label class="pay-option-wrapper" style="display:flex; align-items:center; gap:12px; padding:12px; border:2px solid #ff9800; border-radius:8px; cursor:pointer; background:#fff;" onclick="selectPaymentMethod('now', 'telebirr')">
                <input type="radio" name="payment_choice" value="telebirr" checked style="accent-color:#ff9800;">
                <div style="flex-grow:1;">
                    <strong style="display:block; font-size:14px; color:#111;">Telebirr / Mobile Wallet</strong>
                    <span style="font-size:12px; color:#666;">Pay instantly using local mobile account currency</span>
                </div>
            </label>

            <!-- Option 2: CBE Birr -->
            <label class="pay-option-wrapper" style="display:flex; align-items:center; gap:12px; padding:12px; border:1px solid #ddd; border-radius:8px; cursor:pointer; background:#fafafa;" onclick="selectPaymentMethod('now', 'cbe_birr')">
                <input type="radio" name="payment_choice" value="cbe_birr" style="accent-color:#ff9800;">
                <div style="flex-grow:1;">
                    <strong style="display:block; font-size:14px; color:#111;">CBE Birr / Direct Bank Transfer</strong>
                    <span style="font-size:12px; color:#666;">Process secure institutional digital banking transaction</span>
                </div>
            </label>

            <!-- Option 3: Card Systems -->
            <label class="pay-option-wrapper" style="display:flex; align-items:center; gap:12px; padding:12px; border:1px solid #ddd; border-radius:8px; cursor:pointer; background:#fafafa;" onclick="selectPaymentMethod('now', 'card')">
                <input type="radio" name="payment_choice" value="card" style="accent-color:#ff9800;">
                <div style="flex-grow:1;">
                    <strong style="display:block; font-size:14px; color:#111;">Credit or Debit Card</strong>
                    <span style="font-size:12px; color:#666;">Supports universal Visa, Mastercard, or AMEX options</span>
                </div>
            </label>

            <!-- Option 4: Pay Later -->
            <label class="pay-option-wrapper" style="display:flex; align-items:center; gap:12px; padding:12px; border:1px solid #ddd; border-radius:8px; cursor:pointer; background:#fafafa;" onclick="selectPaymentMethod('later', 'cash')">
                <input type="radio" name="payment_choice" value="later" style="accent-color:#ff9800;">
                <div style="flex-grow:1;">
                    <strong style="display:block; font-size:14px; color:#111;">Pay Later on Arrival</strong>
                    <span style="font-size:12px; color:#666;">Secure reservation status now; process cash settlement later</span>
                </div>
            </label>
        </div>

        <!-- Dynamic Simulated Input Fields Form Wrapper -->
        <div id="card_fields_container" style="display:none; margin-bottom:20px; background:#f5f5f5; padding:15px; border-radius:8px;">
            <label style="display:block; font-size:12px; font-weight:bold; margin-bottom:5px; color:#555;">Card Details</label>
            <input type="text" placeholder="1234 5678 1234 5678" maxlength="19" style="width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
            <div style="display:flex; gap:10px;">
                <input type="text" placeholder="MM/YY" maxlength="5" style="width:50%; padding:10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
                <input type="password" placeholder="CVV" maxlength="3" style="width:50%; padding:10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;">
            </div>
        </div>

        <button type="button" onclick="submitFinalBookingForm();" style="background:#ff9800; color:white; border:none; padding:14px; width:100%; border-radius:6px; font-weight:bold; font-size:16px; cursor:pointer; box-shadow:0 4px 10px rgba(255,152,0,0.3); transition:background 0.2s;">Complete Checkout</button>
    </div>
</div>

  <script>
   // 1. Session & State Variables
const isUserLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

// Run calculation immediately on page load to set correct initial prices
document.addEventListener("DOMContentLoaded", function() {
    runLiveCalculations();
});

// 2. Guest Counter Adjuster Logic
function modifyGuests(direction) {
    const inputElement = document.getElementById('sidebar_people_count');
    let currentVal = parseInt(inputElement.value) || 1;
    const minVal = parseInt(inputElement.min) || 1;
    const maxVal = parseInt(inputElement.max) || 10;

    if (direction === 'up' && currentVal < maxVal) {
        inputElement.value = currentVal + 1;
    } else if (direction === 'down' && currentVal > minVal) {
        inputElement.value = currentVal - 1;
    }
    runLiveCalculations();
}

// 3. Mathematical Live Calculation Engine
function runLiveCalculations() {
    // Fetches base_exp_price to correctly map experience rates
    const basePriceElement = document.getElementById('base_exp_price');
    if (!basePriceElement) return;

    const baseRate = parseFloat(basePriceElement.value) || 0;
    const volumes = parseInt(document.getElementById('sidebar_people_count').value) || 1;
    
    const guideSelect = document.getElementById('sidebar_guide_select');
    let guideFee = 0;
    if (guideSelect && guideSelect.selectedIndex !== -1) {
        const selectedOption = guideSelect.options[guideSelect.selectedIndex];
        guideFee = parseFloat(selectedOption.getAttribute('data-fee')) || 0;
    }

    const subtotal = baseRate * volumes;
    const finalTotal = subtotal + guideFee;

    // Mutate View Layer Indicators safely if elements exist
    if(document.getElementById('txt_guest_count')) document.getElementById('txt_guest_count').innerText = volumes;
    if(document.getElementById('txt_subtotal')) document.getElementById('txt_subtotal').innerText = '$' + subtotal.toFixed(0);
    if(document.getElementById('txt_guide_fee')) document.getElementById('txt_guide_fee').innerText = '$' + guideFee.toFixed(0);
    if(document.getElementById('txt_final_total')) document.getElementById('txt_final_total').innerText = '$' + finalTotal.toFixed(0);
    
    // Bind to structural hidden inputs for form serialization
    if(document.getElementById('final_calculated_total')) document.getElementById('final_calculated_total').value = finalTotal.toFixed(2);
    
    // Bind results directly to payment display modal elements
    if(document.getElementById('pay_modal_total')) document.getElementById('pay_modal_total').innerText = finalTotal.toFixed(2);
}

// 4. Booking Interceptor & Modal Manager
const bookBtn = document.getElementById('sidebarBookBtn');
if (bookBtn) {
    bookBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Flow 1: If User is Not Logged In, intercept and display login modal wrapper
        if (!isUserLoggedIn) {
            const authModal = document.getElementById('auth-modal');
            if (authModal) {
                authModal.style.display = 'block';
                alert('Authentication Required! Sign in or register to proceed with your booking.');
            } else {
                // Fallback redirect passing current URL pathing as return parameter
                window.location.href = 'index.php?redirect=' + encodeURIComponent(window.location.href);
            }
            return;
        }

        // Flow 2: If Logged In, run UI validation rules before opening payment wrapper
        const travelDateInput = document.querySelector('input[name="travel_date"]');
        const travelDate = travelDateInput ? travelDateInput.value : '';
        if(!travelDate) {
            alert('Please select a valid travel date first.');
            return;
        }
        
        // Show payment modal layout overlay safely
        const payModal = document.getElementById('paymentModalOverlay');
        if (payModal) {
            payModal.style.display = 'flex';
        }
    });
}

// 5. Modal Closer Utilities
function closePaymentModal() {
    if(document.getElementById('paymentModalOverlay')) {
        document.getElementById('paymentModalOverlay').style.display = 'none';
    }
}
function selectPaymentMethod(mode, gateway) {
    // mode input sets 'now' or 'later' targets
    document.getElementById('actual_payment_mode').value = mode;
    // gateway variable tracks specific frontend labels ('telebirr', 'cbe_birr', 'card', 'cash')
    document.getElementById('actual_payment_gateway').value = gateway;

    const wrappers = document.querySelectorAll('.pay-option-wrapper');
    wrappers.forEach(item => {
        item.style.border = '1px solid #ddd';
        item.style.background = '#fafafa';
    });

    window.event.currentTarget.style.border = '2px solid #ff9800';
    window.event.currentTarget.style.background = '#fff';
    
    const walletContainer = document.getElementById('wallet_fields_container');
    const cardContainer = document.getElementById('card_fields_container');
    const walletLabel = document.getElementById('wallet_input_label');

    if (gateway === 'telebirr') {
        walletContainer.style.display = 'block';
        cardContainer.style.display = 'none';
        walletLabel.innerText = "Telebirr Phone Number";
    } else if (gateway === 'cbe_birr') {
        walletContainer.style.display = 'block';
        cardContainer.style.display = 'none';
        walletLabel.innerText = "CBE Account Registered Phone Number";
    } else if (gateway === 'card') {
        walletContainer.style.display = 'none';
        cardContainer.style.display = 'block';
    } else {
        walletContainer.style.display = 'none';
        cardContainer.style.display = 'none';
    }
}

function validateAndSubmitBooking() {
    const gateway = document.getElementById('actual_payment_gateway').value;
    
    if (gateway === 'telebirr' || gateway === 'cbe_birr') {
        const phone = document.getElementById('modal_payment_phone').value.trim();
        if (phone === "") { alert("Please provide your mobile wallet phone number for payment processing validation."); return; }
    } else if (gateway === 'card') {
        const cardNum = document.getElementById('modal_card_num').value.trim();
        const cardExpiry = document.getElementById('modal_card_expiry').value.trim();
        const cardCvv = document.getElementById('modal_card_cvv').value.trim();
        if (cardNum === "" || cardExpiry === "" || cardCvv === "") { alert("Please populate all required credit card parameters."); return; }
    }

    // Submit the structural HTML hidden element layout
    document.getElementById('sidebarBookingForm').submit();
}


function submitFinalBookingForm() {
    const parentTargetForm = document.getElementById('sidebarBookingForm');
    if (parentTargetForm) {
        // Run standard native execution pipeline handling values transfer to top controller scripts
        parentTargetForm.submit();
    }
}

  </script>
</body>
</html>
