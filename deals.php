<?php
// 1. Database Connection
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 2. Fetch only active deals
$sql = "SELECT * FROM deals WHERE status = 'active' ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Special Deals & Offers | Betora Travels</title>
  
  <!-- FontAwesome & Swiper -->
  <link rel="stylesheet" href="https://cloudflare.com">
  <link rel="stylesheet" href="https://jsdelivr.net"/>
  <link rel="stylesheet" href="deals.css">
  <link rel="stylesheet" href="ai.css">
  <link rel="stylesheet" href="welcome.css">
  
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
    <li><a class="underline-text" href="destinations.php">Destinations</a></li>
    <li><a class="underline-text" href="trip.html#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="booking.html#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.php#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.php#Experience">Experience</a></li>
    <li><a class="underline-text" href="blog.php#Blog">Blog</a></li>
    <li><a class="underline-text" href="deals.php#Deals">Deals</a></li>
    <li><a class="underline-text" href="aboutus.html#About-Us">About Us</a></li>
    <li><a class="underline-text" href="contactus.html#Contact">Contact</a></li>
   
    <div class="header-actions">
     <i class="fa-solid fa-magnifying-glass search-icon"></i>
  <div class="login-pill">
    <i class="fa-regular fa-circle-user"></i>
   <span>Login/Sign up</span>
  
  </div>
  </div>
  </ul>
   
</nav>

<div id="Deals">
    <!-- HERO SECTION -->
    <section class="deals-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Special Deals & Offers</h1>
            <p>Limited time discounts on travel packages</p>
        </div>
    </section>

    <!-- DEALS GRID SECTION -->
    <section class="deals-grid-section">
        <div class="deals-container">
            
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    $end_date_iso = date('Y-m-d\TH:i:s', strtotime($row['end_datetime']));
                ?>
                    <div class="deal-card" data-end="<?php echo $end_date_iso; ?>">
                        <div class="card-image">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <span class="discount-badge"><?php echo htmlspecialchars($row['discount_badge']); ?></span>
                            <div class="sold-out-overlay"><span>OFFER EXPIRED</span></div>
                            <div class="urgency-badge"><i class="fa-solid fa-fire"></i> Ending Soon!</div>
                        </div>
                        <div class="card-content">
                            <div class="deal-note"><i class="fa-regular fa-note-sticky"></i> <?php echo htmlspecialchars($row['deal_note']); ?></div>
                            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="card-footer">
                                <div class="price-side">
                                    <span class="old-price">$<?php echo number_format($row['old_price'], 0); ?></span>
                                    <span class="new-price">$<?php echo number_format($row['new_price'], 0); ?></span>
                                </div>
                                <div class="date-side">
                                    <span class="valid-note"><i class="fa-regular fa-clock"></i> Ends in:</span>
                                    <div class="timer-display">
                                        <span class="days">00</span>d <span class="hours">00</span>h <span class="minutes">00</span>m <span class="seconds">00</span>s
                                    </div>
                                </div>
                            </div>
                            <button class="grab-btn">Grab This Deal</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align: center; color: #666; grid-column: 1 / -1;">No active deals at the moment. Check back later!</p>
            <?php endif; ?>

        </div>
    </section>

    <!-- EMAIL MODAL -->
    <div id="emailModal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Unlock Your Discount!</h2>
            <p>Enter your email to receive your custom code for <strong id="modal-package-name"></strong>.</p>
            <form id="dealForm">
                <input type="email" id="userEmail" placeholder="Enter your email address" required>
                <button type="submit" class="submit-btn">Get Code & Book Now</button>
            </form>
            <p style="font-size: 12px; color: #aaa; margin-top: 10px;">We value your privacy. No spam, ever.</p>
        </div>
    </div>
</div>

   
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
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("emailModal");
    const dealForm = document.getElementById("dealForm");
    const closeBtn = document.querySelector(".close-modal");
    const modalPackageText = document.getElementById("modal-package-name");
    let currentPackage = "";

    // --- 1. TIMER LOGIC ---
    function updateTimers() {
        const now = new Date().getTime();
        const cards = document.querySelectorAll('.deal-card');

        cards.forEach(card => {
            const endTime = new Date(card.getAttribute('data-end')).getTime();
            const diff = endTime - now;
            const timerDiv = card.querySelector('.timer-display');

            if (diff <= 0) {
                card.classList.add('is-expired');
                timerDiv.innerHTML = "EXPIRED";
                card.querySelector('.grab-btn').innerText = "Unavailable";
                return;
            }

            // Urgency: Less than 24 hours left
            if (diff < (1000 * 60 * 60 * 24)) {
                card.classList.add('is-urgent');
            }

            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            timerDiv.querySelector('.days').innerText = d.toString().padStart(2, '0');
            timerDiv.querySelector('.hours').innerText = h.toString().padStart(2, '0');
            timerDiv.querySelector('.minutes').innerText = m.toString().padStart(2, '0');
            timerDiv.querySelector('.seconds').innerText = s.toString().padStart(2, '0');
        });
    }

    setInterval(updateTimers, 1000);
    updateTimers();

    // --- 2. MODAL LOGIC ---
    document.querySelectorAll(".grab-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const card = this.closest('.deal-card');
            if (card.classList.contains('is-expired')) return;

            currentPackage = card.querySelector('h3').innerText;
            modalPackageText.innerText = currentPackage;
            modal.style.display = "flex";
        });
    });

    closeBtn.onclick = () => modal.style.display = "none";
    window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

    dealForm.onsubmit = (e) => {
        e.preventDefault();
        window.location.href = `booking-page.html?package=${encodeURIComponent(currentPackage)}`;
    };
});







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
