<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discover Ethiopia</title>
  
  <link href="https://fonts.googleapis.com" rel="stylesheet">
  <link rel="stylesheet" href="https://cloudflare.com">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>


    

  <link rel="stylesheet" href="blog.css">
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
    <li><a class="underline-text" href="destination.php">Destinations</a></li>
    <li><a class="underline-text" href="trip.html#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="booking.html#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.php#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.php#Experience">Experience</a></li>
      <li><a class="underline-text" href="blog.php#Blog">Blog</a></li>
      <li><a class="underline-text" href="deals.php#Deals">Deals</a></li>
      <li><a class="underline-text" href="aboutus.html#About-Us">About Us</a></li>
      <li><a class="underline-text" href="contactus.html#Contact">Contact</a></li>
      
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
<body>
  <div id="Blog">
    <section class="blog-hero">
  <div class="hero-content">
    <h1>Travel Blog & Guides</h1>
    <p>Travel tips, inspiration, and destination guides</p>
  </div>
</section>
<?php
// 1. Establish Database Connection
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 2. Fetch only published posts from latest to oldest
$sql = "SELECT * FROM blog_posts WHERE status = 'published' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- Blog Section -->
<section class="blog-grid-section">
  <div class="container">
    <div class="blog-grid">

    <?php if ($result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): 
        
        // Split the stored image URL string into an array for the JS slider
        $image_string = $row['slider_images'] ?? '';
        if (!empty($image_string)) {
            $images_array = array_map('trim', explode(',', $image_string));
        } else {
            // Fallback to cover image if no slider images are provided
            $images_array = [$row['cover_image']];
        }
        
        // Convert to safe JSON for the JavaScript inline onclick event
        $js_images_json = htmlspecialchars(json_encode($images_array), ENT_QUOTES, 'UTF-8');
        $clean_title = htmlspecialchars($row['title'], ENT_QUOTES);
        
        // Content might contain HTML tags or quotes, let's keep it safe
        $clean_content = htmlspecialchars($row['content'], ENT_QUOTES);
      ?>
      
      <!-- Dynamic Database Card -->
      <div class="blog-card">
        <img src="<?php echo htmlspecialchars($row['cover_image']); ?>" alt="<?php echo $clean_title; ?>">
        <div class="card-content">
          <span class="category"><?php echo htmlspecialchars($row['category']); ?></span>
          <h3><?php echo htmlspecialchars($row['title']); ?></h3>
          <p class="meta"><?php echo htmlspecialchars($row['author_name']); ?> • <?php echo htmlspecialchars($row['read_time']); ?></p>
          <p class="description"><?php echo htmlspecialchars($row['summary']); ?></p>
          
          <!-- Dynamic Click Trigger passing JSON array and strings -->
          <a href="#" class="read-more" onclick="event.preventDefault(); openArticle(<?php echo $js_images_json; ?>, '<?php echo $clean_title; ?>', '<?php echo $clean_content; ?>')">
            Read More <span class="arrow">→</span>
          </a>
        </div>
      </div>

      <?php endwhile; ?>
    <?php else: ?>
      <p style="grid-column: 1 / -1; text-align: center; color: #666;">No articles found. Check back later!</p>
    <?php endif; ?>

    </div>
  </div>
</section>


<!-- PLACE THE MODAL OUTSIDE HERE -->
<div id="articleModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8);">
  <div class="modal-content" style="background:#fff; margin:5% auto; padding:20px; width:80%; max-width:800px; border-radius:15px; position:relative;">
    <span class="close-btn" onclick="closeArticle()" style="position:absolute; right:20px; top:10px; font-size:30px; cursor:pointer;">&times;</span>
    <div id="modalBody"></div>
  </div>
</div>

</div>
<script>
/**
 * THE ULTIMATE TRAVEL BLOG SCRIPT
 * Features: Infinite Cross-Fade Slider, Dynamic Article Loading, 
 * and Modal Control.
 */

let slideInterval; // Global variable to manage the slider timer

function openArticle(imgInput, title, content) {
    const modal = document.getElementById("articleModal");
    const body = document.getElementById("modalBody");
    
    // 1. Clear any previous slider timers to prevent glitches
    clearInterval(slideInterval);
    
    let imageSection = '';

    // 2. CHECK: If input is a list (Array), build the Fading Slider
    if (Array.isArray(imgInput)) {
        imageSection = `
            <div class="modal-slider">
                ${imgInput.map((url, index) => 
                    `<img src="${url}" class="slider-img ${index === 0 ? 'active' : ''}">`
                ).join('')}
            </div>`;
        
        // Fading Logic: Switches the "active" class every 4 seconds
        let currentSlide = 0;
        slideInterval = setInterval(() => {
            const imgs = document.querySelectorAll('.slider-img');
            if (imgs.length > 0) {
                // Fade out current image
                imgs[currentSlide].classList.remove('active');
                
                // Move to next index (loops back to 0 at the end)
                currentSlide = (currentSlide + 1) % imgs.length;
                
                // Fade in the new image
                imgs[currentSlide].classList.add('active');
            }
        }, 4000); 
    } else {
        // 3. SINGLE IMAGE: If only one URL is provided, show a fixed banner
        imageSection = `
            <div class="modal-slider">
                <img src="${imgInput}" class="slider-img active" style="position:relative; opacity:1;">
            </div>`;
    }

    // 4. INJECT CONTENT: Combine the Image/Slider with the Article Text
    body.innerHTML = `
        ${imageSection}
        <div class="article-container">
            <h2 class="article-title">${title}</h2>
            <div class="article-content">${content.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&#039;/g, "'")}</div>
        </div>`;
    
    // 5. SHOW MODAL: Use "block" to allow proper scrolling
    modal.style.display = "block";
    document.body.style.overflow = "hidden"; // Disable background scrolling
}

/**
 * CLOSING LOGIC
 */
function closeArticle() {
    const modal = document.getElementById("articleModal");
    modal.style.display = "none";
    document.body.style.overflow = "auto"; // Re-enable background scrolling
    clearInterval(slideInterval); // Stop the slider when modal is closed
}

/**
 * CLICK OUTSIDE TO CLOSE
 */
window.onclick = function(event) {
    const modal = document.getElementById("articleModal");
    if (event.target == modal) {
        closeArticle();
    }
}


</script>


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

<script>lucide.createIcons();</script>


<script>lucide.createIcons();</script>



<script>
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

</script>






<script src="https://unpkg.com/lucide@latest"></script>

<script>
  lucide.createIcons();
</script>



</body>
</html>