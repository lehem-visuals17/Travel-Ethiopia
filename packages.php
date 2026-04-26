<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all packages
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Ethiopia - Travel Packages</title>
    <link rel="stylesheet" href="packages.css">
    <link rel="stylesheet" href="ai.css">
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
    <!-- Navbar remains same as your original -->
    <nav id="navbar">
  <div class="brand">
    <div class="bt">BT</div>
    <div class="logo-text">
      <h1>  Betora Travels</h1>
    <h2>Feel History. Live Culture. Explore Nature</h2>

    </div></div>
    


  <div class="menu-toggle" id="mobile-menu-toggle">&#9776;</div>

  <!-- ✅ Added ID here -->
  <ul class="menu" id="menu-list">
    <div class="menu-close" id="menu-close">&times;</div>
    <li><a class="underline-text" href="index.php#Home">Home</a></li>
    <li><a class="underline-text" href="destination.php">Destinations</a></li>
    <li><a class="underline-text" href="trip.html#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="booking.html#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.php#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.html#Experience">Experience</a></li>
    <li><a class="underline-text" href="blog.html#Blog">Blog</a></li>
    <li><a class="underline-text" href="deals.html#Deals">Deals</a></li>
    <li><a class="underline-text" href="aboutus.html#About-Us">About Us</a></li>
    <li><a class="underline-text" href="contactus.html#Contact">Contact</a></li>
   
    <div class="header-actions">
     <a href="profile.php">
            <i class="fa-solid fa-user"></i>
        </a>
  <div class="login-pill">
    <i class="fa-regular fa-circle-user"></i>
   <span>  <?php 
      if (isset($_SESSION['username'])) {
          echo "Hi, " . htmlspecialchars($_SESSION['username']); 
      } else {
          echo "Login/Sign up";
      }
    ?></span>


  
  </div>
  </div>
  </ul>
  </nav>

    <section id="packages" class="hero">
        <h1>Travel Packages</h1>
        <p>Curated experiences for every traveler</p>
    </section>

    <section class="packages">
        <div class="package-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <!-- Dynamic Slider (If you store multiple images, you'd loop here) -->
                    <div class="fade-slider">
                        <img src="../uploads/<?php echo $row['image']; ?>" class="fade-img" alt="Package Image">
                        <!-- Placeholder for extra images if applicable -->
                        <img src="../uploads/<?php echo $row['image']; ?>" class="fade-img" style="animation-delay: 6s;">
                    </div>
                    
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="main-para"><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <div class="card-meta">
                            <span class="days"><i class="fa-regular fa-clock"></i> <?php echo $row['duration']; ?></span>
                            <span class="rating"><i class="fa-solid fa-star"></i> <?php echo ($row['featured'] == 1) ? '5.0' : '4.8'; ?></span>
                        </div>

                        <div class="budget">
                            <span class="usd">$<?php echo number_format($row['price']); ?></span> | 
                            <span class="etb"><?php echo number_format($row['price'] * 120); ?> ETB</span>
                        </div>

                        <a href="details.php?id=<?php echo $row['id']; ?>" class="details-btn">View Details</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Footer remains same as your original -->
    
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
</body>
</html>
