<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discover Ethiopia</title>
  
  <link href="https://fonts.googleapis.com" rel="stylesheet">
  <link rel="stylesheet" href="https://cloudflare.com">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css""")/>>
  <link rel="stylesheet" href="welcome.css">
  <link rel="stylesheet" href="cards.css">
    <link rel="stylesheet" href="booking.css">
  <link rel="stylesheet" href="ai.css">
    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body>

  <nav id="navbar">
    <div class="brand">
      <div class="bt">BT</div>
      <div class="logo-text">
        <h1>Betora Travels</h1>
        <h2>Feel History. Live Culture. Explore Nature</h2>
      </div>
    </div>

    <div class="menu-toggle" id="mobile-menu-toggle">☰</div>

    <ul class="menu" id="menu-list">
      <div class="menu-close" id="menu-close">&times;</div>
   <li><a class="underline-text" href="index.php#Home">Home</a></li>
    <li><a class="underline-text" href="destination.php#Destinations">Destinations</a></li>
    <li><a class="underline-text" href="trip.php#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="bookings.php#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.php#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.php#Experience">Experience</a></li>
      <li><a class="underline-text" href="blog.php#Blog">Blog</a></li>
      <li><a class="underline-text" href="deals.php#Deals">Deals</a></li>
      <li><a class="underline-text" href="aboutus.php#About-Us">About Us</a></li>
      <li><a class="underline-text" href="contactus.php#Contact">Contact</a></li>
      
      <!-- Moved into a list item for valid HTML -->
      <li>
        <div class="header-actions">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <div class="login-pill">
            <i class="fa-regular fa-circle-user"></i>
            <span>Login/Sign up</span>
          </div>
        </div>
      </li>
    </ul>
  </nav>

  <script src="https://unpkg.com"></script>

<section id="Bookings">
  <!-- Top Banner -->
  <div class="booking-banner">
    <div class="banner-content">
      <h1>Book Your Journey</h1>
      <p>Find and book flights, hotels, car rentals, and tours all in one place</p>
    </div>
  </div>

  <!-- Booking Interface Container -->
  <div class="booking-container">
    <!-- Navigation Tabs -->
    <div class="tab-buttons">
      <button class="tab-btn active" onclick="openTab(event, 'flights')">
        <i data-lucide="plane"></i> Flights
      </button>
      <button class="tab-btn" onclick="openTab(event, 'hotels')">
        <i data-lucide="hotel"></i> Hotels
      </button>
      <button class="tab-btn" onclick="openTab(event, 'cars')">
        <i data-lucide="car"></i> Car Rentals
      </button>
      <button class="tab-btn" onclick="openTab(event, 'tours')">
        <i data-lucide="users"></i> Tours
      </button>

    </div>
    
      <hr class="separator-line">
<!-- Simple Horizontal Rule -->


    
    <!-- Content Sections -->
    <div class="booking-content-wrapper">
      <div id="flights" class="tab-content active">
      <div class="search-flights-form">
  <h2>Search Flights</h2>
  
  <div class="input-row">
    <div class="form-group">
      <label>From</label>
      <div class="input-wrapper">
        <i data-lucide="plane-takeoff" class="input-icon"></i>
        <input type="text" placeholder="Addis Abeba">
      </div>
    </div>

    <div class="form-group">
      <label>To</label>
      <div class="input-wrapper">
        <i data-lucide="map-pin" class="input-icon"></i>
        <input type="text" placeholder="Lalibela">
      </div>
    </div>

    <div class="form-group">
      <label>Date</label>
      <div class="input-wrapper">
        <i data-lucide="calendar" class="input-icon"></i>
        <input type="date" value="2026-03-31">
      </div>
    </div>
  </div>

  <div class="checkbox-row">
    <label class="checkbox-item">
      <input type="checkbox"> Flexible Dates (±3 days)
    </label>
    <label class="checkbox-item">
      <input type="checkbox"> Price Alert
    </label>
  </div>

  <button class="search-btn">
    <i data-lucide="search"></i> Search Flights
  </button>
</div>

<div class="results-header">
  <h2>Available Flights</h2>
  <button class="compare-btn">
    <i data-lucide="layers"></i> Compare Prices
  </button>
</div>

<div class="flight-list">
  <!-- Flight Item 1 -->
  <div class="flight-card">
    <div class="airline-info">
      <strong>Ethiopian Airlines</strong>
      <span>Economy</span>
      <span class="seats-left">12 seats left</span>
    </div>

    <div class="times-container">
      <div class="time-block">
      <div class="time">10:30 AM</div>
      <div class="airport">New York (JFK)</div>
    </div>
    </div>

    

    <div class="route-block">
      <div class="duration">14h 15m</div>
      <div class="path-line">
         
        <i data-lucide="plane" class="icon-yellow"></i>
      </div>
      <div class="stops">Non-stop</div>
    </div>

    <div class="time-block">
      <div class="time">7:45 AM <small>+1</small></div>
      <div class="airport">Addis Ababa (ADD)</div>
    </div>

    <div class="price-action">
      <div class="price">$850</div>
      <button class="select-btn">Select Flight</button>
    </div>
  </div>

  <div class="flight-card">
    <div class="airline-info">
      <strong>Ethiopian Airlines</strong>
      <span>Economy</span>
      <span class="seats-left">12 seats left</span>
    </div>
    
    <div class="times-container">
      <div class="time-block">
      <div class="time">10:30 AM</div>
      <div class="airport">New York (JFK)</div>
    </div>
    </div>

    

    <div class="route-block">
      <div class="duration">14h 15m</div>
      <div class="path-line">
        <i data-lucide="plane" class="icon-yellow"></i>
      </div>
      <div class="stops">Non-stop</div>
    </div>

    <div class="time-block">
      <div class="time">7:45 AM <small>+1</small></div>
      <div class="airport">Addis Ababa (ADD)</div>
    </div>

    <div class="price-action">
      <div class="price">$850</div>
      <button class="select-btn">Select Flight</button>
    </div>
  </div>

  <div class="flight-card">
    <div class="airline-info">
      <strong>Ethiopian Airlines</strong>
      <span>Economy</span>
      <span class="seats-left">12 seats left</span>
    </div>
    
    <div class="times-container">
      <div class="time-block">
      <div class="time">10:30 AM</div>
      <div class="airport">New York (JFK)</div>
    </div>
    </div>

    

    <div class="route-block">
      <div class="duration">14h 15m</div>
      <div class="path-line">
        <i data-lucide="plane" class="icon-yellow"></i>
      </div>
      <div class="stops">Non-stop</div>
    </div>

    <div class="time-block">
      <div class="time">7:45 AM <small>+1</small></div>
      <div class="airport">Addis Ababa (ADD)</div>
    </div>

    <div class="price-action">
      <div class="price">$850</div>
      <button class="select-btn">Select Flight</button>
    </div>
  </div>
  
  <!-- Repeat for Turkish and Emirates -->
</div>


      </div>


<div id="hotels" class="tab-content">
 <div class="filter-hotels-form">
  <h2>Filter Hotels</h2>
  
  <div class="filter-row">
    <!-- Price Range Slider -->
    <div class="filter-group" slider-group>
  <label>Price Range (per night)</label>
  <!-- The Slider -->
  <input type="range" min="0" max="1000" value="288" class="price-slider" id="priceRange">
  
  <!-- The dynamic labels -->
  <div class="range-labels">
    <span>$0</span>
    <span id="currentPrice"><strong>$288</strong></span>
  </div>
</div>


    <!-- Minimum Rating Select -->
    <div class="filter-group">
      <label>Minimum Rating</label>
      <select class="filter-select">
        <option>Any Rating</option>
        <option>3+ Stars</option>
        <option>4+ Stars</option>
        <option>5 Stars</option>
      </select>
    </div>

    <!-- Location Input -->
    <div class="filter-group">
      <label>Location</label>
      <div class="input-wrapper">
        <i data-lucide="map-pin" class="input-icon"></i>
        <input type="text" placeholder="City or area" class="filter-input">
      </div>
    </div>

    <!-- Amenities Checkboxes -->
    <div class="filter-group">
      <label>Amenities</label>
      <div class="checkbox-stack">
        <label class="check-item">
          <input type="checkbox" checked>
          <i data-lucide="wifi"></i> Free WiFi
        </label>
        <label class="check-item">
          <input type="checkbox" checked>
          <i data-lucide="waves"></i> Swimming Pool
        </label>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com"></script>

<div class="hotel-results-grid">
  <!-- Hotel Card 1 (Repeat this 3 times) -->
  <div class="hotel-card">
    <div class="card-image-wrapper">
      <img src="sheraton.jpg" class="zoom-img" alt="Sheraton Addis">
      <div class="rating-badge">
        <i data-lucide="star" class="star-icon"></i> 4.8
      </div>
    </div>

    <div class="card-content">
      <h3 class="hotel-name">Sheraton Addis</h3>
      <div class="location">
        <i data-lucide="map-pin"></i> City Center, Addis Ababa
      </div>

      <div class="amenities">
        <span class="amenity-tag">WiFi</span>
        <span class="amenity-tag">Pool</span>
        <span class="amenity-tag">Gym</span>
      </div>

      <div class="review-count">2,847 reviews</div>

      <hr class="card-divider">

      <div class="card-footer">
        <div class="price-box">
          <span class="per-night">Per night</span>
          <span class="price">$180</span>
        </div>
        <button class="book-btn">Book Now</button>
      </div>
    </div>
  </div>

  <div class="hotel-card">
    <div class="card-image-wrapper">
      <img src="images\adis3.jpg" class="zoom-img" alt="Sheraton Addis">
      <div class="rating-badge">
        <i data-lucide="star" class="star-icon"></i> 4.8
      </div>
    </div>

    <div class="card-content">
      <h3 class="hotel-name">Sheraton Addis</h3>
      <div class="location">
        <i data-lucide="map-pin"></i> City Center, Addis Ababa
      </div>

      <div class="amenities">
        <span class="amenity-tag">WiFi</span>
        <span class="amenity-tag">Pool</span>
        <span class="amenity-tag">Gym</span>
      </div>

      <div class="review-count">2,847 reviews</div>

      <hr class="card-divider">

      <div class="card-footer">
        <div class="price-box">
          <span class="per-night">Per night</span>
          <span class="price">$180</span>
        </div>
        <button class="book-btn">Book Now</button>
      </div>
    </div>
  </div>

  <div class="hotel-card">
    <div class="card-image-wrapper">
      <img src="sheraton.jpg" class="zoom-img" alt="Sheraton Addis">
      <div class="rating-badge">
        <i data-lucide="star" class="star-icon"></i> 4.8
      </div>
    </div>

    <div class="card-content">
      <h3 class="hotel-name">Sheraton Addis</h3>
      <div class="location">
        <i data-lucide="map-pin"></i> City Center, Addis Ababa
      </div>

      <div class="amenities">
        <span class="amenity-tag">WiFi</span>
        <span class="amenity-tag">Pool</span>
        <span class="amenity-tag">Gym</span>
      </div>

      <div class="review-count">2,847 reviews</div>

      <hr class="card-divider">

      <div class="card-footer">
        <div class="price-box">
          <span class="per-night">Per night</span>
          <span class="price">$180</span>
        </div>
        <button class="book-btn">Book Now</button>
      </div>
    </div>
  </div>
  
  <!-- Add Card 2 and 3 here... -->
</div>
 <div class="map-container">
    <h3>Explore Nearby Hotels</h3>
    <div class="map-wrapper">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3940.4756126130187!2d38.7600027!3d9.020303700000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85930b17ec2f%3A0x8a433f2230326db!2sSheraton%20Addis%2C%20a%20Luxury%20Collection%20Hotel%2C%20Addis%20Ababa!5e0!3m2!1sen!2set!4v1775477543065!5m2!1sen!2set" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      <iframe 
        src="https://google.com" 
        width="100%" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>


</div>

<script>lucide.createIcons();</script>




<div id="cars" class="tab-content" >
        <div class="search-cars-form">
  <h2>Find Your Car</h2>
  
  <div class="input-row">
    <div class="form-group">
      <label>Pickup Location</label>
      <div class="input-wrapper">
        <i data-lucide="map-pin" class="input-icon"></i>
        <input type="text" placeholder="Enter location">
      </div>
    </div>

    <div class="form-group">
      <label>Drop Location</label>
      <div class="input-wrapper">
        <i data-lucide="map-pin" class="input-icon"></i>
        <input type="text" placeholder="Enter location">
      </div>
    </div>

    <div class="form-group">
      <label>Car Type</label>
      <select class="filter-select">
        <option>All Types</option>
        <option>Economy</option>
        <option>SUV / 4x4</option>
        <option>Luxury</option>
      </select>
    </div>
  </div>

  <button class="search-btn">
    <i data-lucide="search"></i> Search Cars
  </button>
</div>

<!-- Car Results Grid -->
<div class="car-results-grid">
  <!-- Card 1 (Repeat for 2 and 3) -->
  <div class="car-card">
    <div class="card-image-wrapper">
      <img src="images\suv1.jpg" class="zoom-img" alt="4x4 Rental">
      <span class="car-badge">4x4 Specialist</span>
    </div>
    <div class="card-content">
      <h3 class="car-name">Toyota Land Cruiser</h3>
      <span class="per-day">Hertz</span>
      <div class="car-specs">
        <span><i data-lucide="users" class="icon-yellow"></i> 5 Seats</span>
        <span><i data-lucide="settings" class="icon-yellow"></i> Manual</span>
        <span><i data-lucide="fuel" class="icon-yellow"></i> Diesel</span>
        <span><i data-lucide="briefcase" class="icon-yellow"></i>4 Luggage</span>
      </div>
      <div class="feature-tags">
  <span class="tag-pill">4WD</span>
  <span class="tag-pill">GPS</span>
  <span class="tag-pill">AC</span>
</div>

      <div class="card-footer">
        <div class="price-box">
          <span class="per-day">Per day</span>
          <span class="price">$120</span>
        </div>
        <button class="book-btn">Rent Now</button>
      </div>
    </div>
  </div>

  

  <div class="car-card">
    <div class="card-image-wrapper">
      <img src="images\sedan.jpg" class="zoom-img" alt="4x4 Rental">
      <span class="car-badge">sedan</span>
    </div>
    <div class="card-content">
      <h3 class="car-name">Toyota Land Cruiser</h3>
      <span class="per-day">Enterprise</span>
      <div class="car-specs">
        <span><i data-lucide="users" class="icon-yellow"></i> 5 Seats</span>
        <span><i data-lucide="settings" class="icon-yellow"></i> Manual</span>
        <span><i data-lucide="fuel" class="icon-yellow"></i> Diesel</span>
        <span><i data-lucide="briefcase" class="icon-yellow"></i>4 Luggage</span>
      </div>
      <div class="feature-tags">
  <span class="tag-pill">Bluetooth</span>
  <span class="tag-pill">GPS</span>
  <span class="tag-pill">AC</span>
</div>

      <div class="card-footer">
        <div class="price-box">
          <span class="per-day">Per day</span>
          <span class="price">$45</span>
        </div>
        <button class="book-btn">Rent Now</button>
      </div>
    </div>
  </div>

  

  <div class="car-card">
    <div class="card-image-wrapper">
      <img src="images\compact.jpg" class="zoom-img" alt="4x4 Rental">
      <span class="car-badge">compact SUV</span>
    </div>
    <div class="card-content">
      <h3 class="car-name">Hyundai Tucson</h3>
      <span class="per-day">Budget</span>
      <div class="car-specs">
        <span><i data-lucide="users" class="icon-yellow"></i> 5 Seats</span>
        <span><i data-lucide="settings" class="icon-yellow"></i> Manual</span>
        <span><i data-lucide="fuel" class="icon-yellow"></i> Diesel</span>
        <span><i data-lucide="briefcase" class="icon-yellow"></i>3 Luggage</span>
      </div>
      <div class="feature-tags">
  <span class="tag-pill">4WD</span>
  <span class="tag-pill">GPS</span>
  <span class="tag-pill">AC</span>
</div>

      <div class="card-footer">
        <div class="price-box">
          <span class="per-day">Per day</span>
          <span class="price">$75</span>
        </div>
        <button class="book-btn">Rent Now</button>
      </div>
    </div>
  </div>
  <!-- ... Repeat for Card 2 and 3 ... -->
</div>





<div id="tours" class="tab-content">
  <div class="tours-sub-section">

    <div class="filter-container">
  <button class="filter-btn active" onclick="showContent('all', this)">All Tours</button>
  <button class="filter-btn" onclick="showContent('guided', this)">Guided Tours</button>
  <button class="filter-btn" onclick="showContent('group', this)">Group Travel</button>
  <button class="filter-btn" onclick="showContent('private', this)">Private Tours</button>
</div>

<div id="tour-content" class="content-display">
  Showing All Tours...
</div>

  
 </div>

</div>
</div>
</div>
</section>

<script>lucide.createIcons();</script>


 


<footer class="footer">
  <!-- Top Section: Logo, Contact, and Navigation -->
  <div class="footer-top">
    <div class="footer-brand">
      <div class="logo-wrapper">
        <span class="logo-icon">BT</span>
        <div class="logo-text">
          <strong>Betora Travels</strong>
          <span class="tagline">Feel History. Live Culture. Explore Nature</span>
        </div>
      </div>
      <p class="brand-desc">Your trusted partner for authentic Ethiopian travel experiences. Explore ancient wonders and rich culture with confidence.</p>
      
      <div class="contact-info">
        <div class="contact-item"><i data-lucide="phone"></i> +251 11 234 5678</div>
        <div class="contact-item"><i data-lucide="mail"></i> info@ethiopiatours.com</div>
        <div class="contact-item"><i data-lucide="map-pin"></i> Addis Ababa, Ethiopia</div>
      </div>
    </div>

    <div class="footer-links">
      <div class="link-column">
        <h4>Company</h4>
        <a href="#">About Us</a>
        <a href="#">Our Team</a>
        <a href="#">Careers</a>
      </div>
      <div class="link-column">
        <h4>Destinations</h4>
        <a href="#">Lalibela</a>
        <a href="#">Simien Mountains</a>
        <a href="#">Axum</a>
      </div>
      <div class="link-column">
        <h4>Resources</h4>
        <a href="#">Blog</a>
        <a href="#">Travel Guide</a>
        <a href="#">FAQs</a>
      </div>
      <div class="link-column">
        <h4>Legal</h4>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
      </div>
    </div>
  </div>

  <hr class="footer-divider">

  <!-- Middle Section: Socials & Payments -->
  <div class="footer-mid">
    <div class="socials">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="#"><i data-lucide="facebook"></i></a>
        <a href="#"><i data-lucide="instagram"></i></a>
        <a href="#"><i data-lucide="twitter"></i></a>
        <a href="#"><i data-lucide="youtube"></i></a>
      </div>
    </div>
    <div class="payments">
      <h4>Secure Payment</h4>
      <div class="payment-badges">
        <span>Visa</span>
        <span>Mastercard</span>
        <span>PayPal</span>
        <span>Stripe</span>
      </div>
    </div>
  </div>

  <!-- Bottom Section: Copyright & Badges -->
  <div class="footer-bottom">
    <div class="copyright">© 2026 Betota Travels. All rights reserved.</div>
    <div class="trust-badges">
      <span><span class="dot"></span> Licensed Operator</span>
      <span><span class="dot"></span> SSL Secured</span>
      <span><span class="dot"></span> 24/7 Support</span>
    </div>
  </div>
</footer>


<div id="auth-modal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>

    <?php if (isset($_SESSION['user_id'])): ?>
      <!-- LOGOUT VIEW: Shown only when logged in -->
      <div id="logout-section" style="text-align: center; padding: 20px;">
        <h2>Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>!</h2>
        <p>You are currently signed in.</p>
        <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
          <a href="logout.php" class="btn" style="background: #ff4b2b; text-decoration: none;">Logout</a>
          <button class="btn" style="background: #888;" onclick="document.getElementById('auth-modal').style.display='none'">Close</button>
        </div>
      </div>

    <?php else: ?>
      <!-- LOGIN FORM -->
      <form id="login-form" action="login.php" method="POST">
        <h2>Sign In</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
        <p>New here? <a href="#" id="go-to-signup">Create an account</a></p>
      </form>

      <!-- SIGN UP FORM -->
      <form id="signup-form" action="login.php" method="POST" style="display: none;">
        <h2>Sign Up</h2>
        <input type="text" name="fullname" placeholder="Full Name" required>
      <input type="text" name="username" id="signup-username" placeholder="Username" required>
      <div id="username-msg" style="font-size: 12px; margin-top: -10px; margin-bottom: 10px;"></div>
      <input type="email" name="email" placeholder="Email" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" id="signup-btn" class="btn">Register</button>
      <p>Already have an account? <a href="#" id="go-to-login">Sign In</a></p>
      </form>
    <?php endif; ?>
  </div>
</div>
  <script src="https://jsdelivr.net"></script>

  <script>
   function openTab(evt, tabId) {
  // Hide all content sections
  const contents = document.getElementsByClassName("tab-content");
  for (let content of contents) {
    content.classList.remove("active");
  }

  // Remove 'active' class from all buttons
  const buttons = document.getElementsByClassName("tab-btn");
  for (let btn of buttons) {
    btn.classList.remove("active");
  }

  // Show selected content and highlight button
  document.getElementById(tabId).classList.add("active");
  evt.currentTarget.classList.add("active");
}


function toggleFilters() {
  const form = document.getElementById('filterForm');
  const btnText = document.getElementById('toggleText');
  const btnIcon = document.getElementById('toggleIcon');

  // Toggle the visibility class
  form.classList.toggle('hidden-mobile');

  // Change text and icon based on visibility
  if (form.classList.contains('hidden-mobile')) {
    btnText.innerText = "Show Filters";
    btnIcon.setAttribute('data-lucide', 'filter');
  } else {
    btnText.innerText = "Hide Filters";
    btnIcon.setAttribute('data-lucide', 'x');
  }

  // Refresh Lucide icons to show the new 'X' or 'Filter' icon
  lucide.createIcons();
}


function openSubTab(evt, subTabId) {
  // 1. Hide all sub-content divs
  const subContents = document.getElementsByClassName("sub-content");
  for (let content of subContents) {
    content.classList.remove("active");
  }

  // 2. Remove active class from all sub-buttons
  const subButtons = document.getElementsByClassName("sub-btn");
  for (let btn of subButtons) {
    btn.classList.remove("active");
  }

  // 3. Show current sub-tab and set button to active
  document.getElementById(subTabId).classList.add("active");
  evt.currentTarget.classList.add("active");
}


function showContent(tourType, element) {
  // 1. Remove 'active' class from all buttons
  const buttons = document.querySelectorAll('.filter-btn');
  buttons.forEach(btn => btn.classList.remove('active'));

  // 2. Add 'active' class to the clicked button
  element.classList.add('active');

  // 3. Update the content area based on the click
  const display = document.getElementById('tour-content');
  
  const contentMap = {
    'all': 'Showing All Tours...',
    'guided': 'Explore our expert-led Guided Tours.',
    'group': 'Join a community with Group Travel.',
    'private': 'Enjoy a personalized Private Tour experience.'
  };

  display.innerText = contentMap[tourType];
}

const menuToggle = document.getElementById("mobile-menu-toggle");
const menu = document.getElementById("menu-list");
const menuClose=document.getElementById("menu-close");

menuToggle.addEventListener("click", () => {
  menu.classList.toggle("active");
});

menuClose.addEventListener("click",()=>{
  menu.classList.remove("active")
})

/* SWIPER */
var swiper = new Swiper(".mySwiper", {
  effect: "fade",
  speed: 1500,
  loop: true,
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
});

document.addEventListener("DOMContentLoaded", () => {
  // 1. NAVIGATION MENU LOGIC
  const menuToggle = document.getElementById("mobile-menu-toggle");
  const menuList = document.getElementById("menu-list");
  const menuClose = document.getElementById("menu-close");
  const menuLinks = document.querySelectorAll('.menu li a');

  if (menuToggle && menuList) {
    // Open/Toggle Menu
    menuToggle.addEventListener("click", (e) => {
      e.stopPropagation(); // Prevents immediate closing
      menuList.classList.toggle("active");
    });

    // Close Menu via 'X' button
    if (menuClose) {
      menuClose.addEventListener("click", () => {
        menuList.classList.remove("active");
      });
    }

    // Close menu when clicking any link
    menuLinks.forEach(link => {
      link.addEventListener('click', () => {
        menuList.classList.remove("active");
      });
    });

    // Optional: Close menu if clicking anywhere outside the menu
    document.addEventListener('click', (e) => {
      if (!menuList.contains(e.target) && !menuToggle.contains(e.target)) {
        menuList.classList.remove("active");
      }
    });
  }});

















  
document.addEventListener("DOMContentLoaded", () => {
    // 1. SELECT ELEMENTS (One time only)
    const authModal = document.getElementById("auth-modal");
    const loginPills = document.querySelectorAll(".login-pill"); 
    const closeAuthBtn = document.querySelector(".close-btn");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const goToSignup = document.getElementById("go-to-signup");
    const goToLogin = document.getElementById("go-to-login");
    
    // Safety check: if authModal doesn't exist on this page, stop script
    if (!authModal) return;

    // 2. OPEN/CLOSE LOGIC
    loginPills.forEach(pill => {
        pill.addEventListener("click", () => {
            authModal.style.display = "block";
            // If you have a mobile menuList variable, uncomment below:
            // if(typeof menuList !== 'undefined') menuList.classList.remove("active");
        });
    });

    if (closeAuthBtn) {
        closeAuthBtn.addEventListener("click", () => {
            authModal.style.display = "none";
        });
    }

    window.addEventListener("click", (event) => {
        if (event.target == authModal) {
            authModal.style.display = "none";
        }
    });

    // 3. TOGGLE FORMS
    if (goToSignup) {
        goToSignup.addEventListener("click", (e) => {
            e.preventDefault();
            loginForm.style.display = "none";
            signupForm.style.display = "block";
        });
    }

    if (goToLogin) {
        goToLogin.addEventListener("click", (e) => {
            e.preventDefault();
            signupForm.style.display = "none";
            loginForm.style.display = "block";
        });
    }

    // 4. AUTO-OPEN FROM URL (e.g. ?showLogin=true)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('showLogin') === 'true') {
        authModal.style.display = "block";
        loginForm.style.display = "block";
        signupForm.style.display = "none";
    }

    // 5. SIGNUP VALIDATION & USERNAME CHECK
    const usernameInput = signupForm.querySelector('input[name="username"]');
    const signupBtn = signupForm.querySelector('button[type="submit"]');

    if (usernameInput) {
        usernameInput.addEventListener('blur', async () => {
            const username = usernameInput.value.trim();
            if (username.length < 3) return;

            try {
                const response = await fetch('check_username.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `username=${encodeURIComponent(username)}`
                });
                const result = await response.json();
                if (result.exists) {
                    alert("This username is already taken. Please choose another.");
                    usernameInput.style.borderColor = "red";
                    signupBtn.disabled = true;
                } else {
                    usernameInput.style.borderColor = "green";
                    signupBtn.disabled = false;
                }
            } catch (error) {
                console.error("Error checking username:", error);
            }
        });
    }

    signupForm.onsubmit = (e) => {
        const phone = signupForm.querySelector('input[name="phone"]').value;
        const email = signupForm.querySelector('input[name="email"]').value;
        const pass = signupForm.querySelector('input[name="password"]').value;
        const confirmPass = signupForm.querySelector('input[name="confirm_password"]').value;

        if (!/^(09|07)\d{8}$/.test(phone)) {
            e.preventDefault();
            alert("Phone number must be 10 digits and start with 09 or 07.");
            return false;
        }

        if (!email.toLowerCase().endsWith("@gmail.com")) {
            e.preventDefault();
            alert("Please use a valid @gmail.com address.");
            return false;
        }

        if (!/^(?=.*\d).{5,}$/.test(pass)) {
            e.preventDefault();
            alert("Password must be at least 5 characters long and include at least one number.");
            return false;
        }

        if (pass !== confirmPass) {
            e.preventDefault();
            alert("Passwords do not match!");
            return false;
        }
    };

    // 6. CARD SLIDER LOGIC
    document.querySelectorAll('.card').forEach(card => {
        const slides = card.querySelectorAll('.slide');
        const dots = card.querySelectorAll('.dot');
        if (slides.length > 0) {
            function showSlide(i) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));
                slides[i].classList.add('active');
                if (dots[i]) dots[i].classList.add('active');
            }
            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => showSlide(i));
            });
        }
    });
});



  </script>
  
</body>
</html>
