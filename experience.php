<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Only fetch active experiences
$result = $conn->query("SELECT * FROM experiences WHERE status = 'Active' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Discover Ethiopia - Experiences</title>
  <link rel="stylesheet" href="experience.css">
  <link rel="stylesheet" href="welcome.css">
  <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
 
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
    <li><a class="underline-text" href="destination.php#Destinations">Destinations</a></li>
    <li><a class="underline-text" href="trip.php#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="bookings.php#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.php#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.php#Experience">Experience</a></li>
    <li><a class="underline-text" href="blog.php#Blog">Blog</a></li>
    <li><a class="underline-text" href="deals.php#Deals">Deals</a></li>
    <li><a class="underline-text" href="aboutus.php#About-Us">About Us</a></li>
    <li><a class="underline-text" href="contactus.php#Contact">Contact</a></li>
   
    <div class="header-actions">
     <i class="fa-solid fa-magnifying-glass search-icon"></i>
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
  
  <section class="experience-hero">
    <div class="hero-overlay">
      <div class="hero-content">
        <h2>Unforgettable Experiences</h2>
        <p>Discover authentic adventures and create lasting memories</p>
      </div>
    </div>
  </section>

  <section>
    <div class="experience-container">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="experience-card">
          <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
          <div class="card-content">
            <span class="category"><?php echo htmlspecialchars($row['category']); ?></span>
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><?php echo htmlspecialchars(substr($row['description'], 0, 150)) . '...'; ?></p>
            
            <div class="info-line">
              <span class="duration"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($row['duration']); ?></span>
              <span class="price">$<?php echo number_format($row['price']); ?></span>
            </div>
            
            <div class="rating">
              <i class="fa-solid fa-star"></i> <?php echo ($row['is_featured'] == 1) ? '4.9' : '4.5'; ?> (<?php echo rand(50, 400); ?>)
            </div>
            
            <!-- Link directly to details page passing ID -->
            <a href="experience_details.php?id=<?php echo $row['id']; ?>" style="text-decoration:none;">
              <button class="book-btn">View Experience</button>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

 
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

<script>
document.addEventListener("DOMContentLoaded", () => {
  // 1. NAVIGATION MENU LOGIC
  const menuToggle = document.getElementById("mobile-menu-toggle");
  const menuList = document.getElementById("menu-list");
  const menuClose = document.getElementById("menu-close");
  const menuLinks = document.querySelectorAll('.menu li a');

  if (menuToggle && menuList) {
    menuToggle.addEventListener("click", (e) => {
      e.stopPropagation();
      menuList.classList.toggle("active");
    });

    if (menuClose) {
      menuClose.addEventListener("click", () => {
        menuList.classList.remove("active");
      });
    }

    menuLinks.forEach(link => {
      link.addEventListener('click', () => {
        menuList.classList.remove("active");
      });
    });

    document.addEventListener('click', (e) => {
      if (!menuList.contains(e.target) && !menuToggle.contains(e.target)) {
        menuList.classList.remove("active");
      }
    });
  } });// <--- Added closing brace for "if (menuToggle && menuList)"



  

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
