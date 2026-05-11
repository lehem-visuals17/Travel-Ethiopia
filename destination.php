<?php
include 'db.php';
$is_search = isset($_GET['search_name']) || isset($_GET['region']) || isset($_GET['city']) || isset($_GET['type']);
// 1. Initialize variables from the search form (assuming GET method is used for better bookmarking)
$search_name = isset($_GET['search_name']) ? mysqli_real_escape_string($conn, $_GET['search_name']) : '';
$region = isset($_GET['region']) ? mysqli_real_escape_string($conn, $_GET['region']) : '';
$city = isset($_GET['city']) ? mysqli_real_escape_string($conn, $_GET['city']) : '';
$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : '';

// 2. Start with a base query
$sql = "SELECT * FROM destinations WHERE 1=1";

// 3. Dynamically append conditions if the user provided input
if (!empty($search_name)) {
    $sql .= " AND name LIKE '%$search_name%'";
}
if (!empty($region) && $region != 'All Regions') {
    $sql .= " AND region = '$region'";
}
if (!empty($city) && $city != 'All Cities') {
    $sql .= " AND (region = '$city' OR name LIKE '%$city%')"; // Adjust based on your 'cities' data structure
}
if (!empty($type) && $type != 'All Types') {
    $sql .= " AND type = '$type'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://jsdelivr.net"/>
    <link rel="stylesheet" href="welcome.css">
    <link rel="stylesheet" href="cards.css">
    <link rel="stylesheet" href="ai.css">
    <link rel="stylesheet" href="https://cloudflare.com">
    <title>Discover Ethiopia - Destinations</title>
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
    <li><a class="underline-text" href="destinations.php">Destinations</a></li>
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
        <span>Login/Sign up</span>
      </div>
    </div>
  </ul>  
</nav>

<section id="Destinations" class="destination-hero">
    <video autoplay muted loop playsinline class="bg-video">
        <source src="ethiopia-video.mp4" type="video/mp4">
    </video>
    <div class="overlay"></div>
    <div class="content">
        <h1>Discover <span>Ethiopia</span></h1>
        <p>Explore breathtaking destinations across the land of origins</p>

        <!-- SEARCH BOX -->
        <form action="destination.php" method="GET" class="search-box">
    <div class="field">
        <label>Search</label>
        <div class="gradient-wrapper">
            <input type="text" name="search_name" placeholder="Search destinations..." value="<?php echo htmlspecialchars($search_name); ?>" />
        </div>
    </div>

    <div class="field">
        <label>Regions</label>
        <div class="gradient-wrapper">
            <select name="region">
                <option>All Regions</option>
                <option <?php if($region == 'Amhara') echo 'selected'; ?>>Amhara</option>
                <option <?php if($region == 'Oromia') echo 'selected'; ?>>Oromia</option>
                <!-- Add other regions... -->
            </select>
        </div>
    </div>

    <div class="field">
        <label>Cities</label>
        <div class="gradient-wrapper">
            <select name="city">
                <option>All Cities</option>
                <option <?php if($city == 'Addis Ababa') echo 'selected'; ?>>Addis Ababa</option>
                <option <?php if($city == 'Lalibela') echo 'selected'; ?>>Lalibela</option>
                <!-- Add other cities... -->
            </select>
        </div>
    </div>

    <div class="field">
        <label>Travel Type</label>
        <div class="gradient-wrapper">
            <select name="type">
                <option>All Types</option>
                <option value="cultural" <?php if($type == 'cultural') echo 'selected'; ?>>Cultural</option>
                <option value="adventure" <?php if($type == 'adventure') echo 'selected'; ?>>Adventure</option>
                <option value="relaxation" <?php if($type == 'relaxation') echo 'selected'; ?>>Relaxation</option>
            </select>
        </div>
    </div>

    <div class="search-btn-container">
        <button type="submit">Search</button>
    </div>
</form>

    </div>
</section>

<section class="filter-section">
    <div class="filter-container">
        <button class="filter-btn active"><span>🌍</span> All Destinations</button>
        <button class="filter-btn"><span>🔥</span> Trending</button>
        <button class="filter-btn"><span>💎</span> Hidden Gems</button>
        <button class="filter-btn"><span>🏨</span> Luxury</button>
        <button class="filter-btn"><span>💰</span> Budget</button>
    </div>
</section>

<section class="cards-section">
    <h2 class="results-title">All Destinations</h2>
    <div class="cards-container">

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="card" data-category="<?php echo strtolower($row['type']); ?>">
    <div class="card-slider">
        <!-- Main Image -->
        <img src="admin/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="active">
        
        <?php if(!empty($row['image2'])): ?>
            <img src="admin/uploads/<?php echo htmlspecialchars($row['image2']); ?>">
        <?php endif; ?>

        <!-- Destination Name and Region Overlay -->
        <div class="image-text">
            <h3 style="display: block !important; opacity: 1 !important; color: white;">
                <?php echo htmlspecialchars($row['name']); ?>
            </h3>
            <p><?php echo htmlspecialchars($row['region']); ?></p>
        </div>

        <div class="rating">
            <i class="fas fa-star" style="color: rgb(255, 208, 0);"></i>
            <?php echo $row['rating']; ?>
        </div>
    </div>

    <div class="card-inner">
        <div class="card-content">
            <!-- Destination Name (Repeated here in case CSS hides the overlay) -->
            <h3 class="card-title-main"><?php echo htmlspecialchars($row['name']); ?></h3>
            
            <p class="desc"><?php echo htmlspecialchars($row['tagline']); ?></p>

            <div class="tags">
                <button><?php echo ucfirst($row['type']); ?></button>
                <button><?php echo htmlspecialchars($row['best_time']); ?></button>
            </div>

            <div class="card-footer">
                <span class="price">
                    <i class="fas fa-dollar-sign" style="color:  rgb(255, 208, 0);"></i>
                    <?php echo $row['budget_cost']; ?>/day
                </span>
                <span class="reviews"><?php echo $row['reviews']; ?> reviews</span>
            </div>
            
            <!-- Link with the 'source=public' flag for the back button fix -->
            <a href="destination-details.php?id=<?php echo $row['id']; ?>&source=public" class="details-btn">
                View Details
            </a> 
        </div>
    </div>
</div>

    <?php } ?>

</div>

</section>


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
    
<?php if ($is_search): ?>
    <?php if ($count > 0): ?>
        alert("Found <?php echo $count; ?> destinations matching your search!");
    <?php else: ?>
        alert("Sorry, destination not found.");
    <?php endif; ?>
<?php endif; ?>


document.addEventListener("DOMContentLoaded", () => {
    // Navigation Logic
    const menuToggle = document.getElementById("mobile-menu-toggle");
    const menuList = document.getElementById("menu-list");
    const menuClose = document.getElementById("menu-close");

    menuToggle.addEventListener("click", () => menuList.classList.toggle("active"));
    if (menuClose) menuClose.addEventListener("click", () => menuList.classList.remove("active"));

    // Image Slider Logic
    document.querySelectorAll('.card-slider').forEach(slider => {
        let images = slider.querySelectorAll('img');
        if (images.length <= 1) return; 
        let index = 0;
        setInterval(() => {
            images[index].classList.remove('active');
            index = (index + 1) % images.length;
            images[index].classList.add('active');
        }, 3000);
    });

    // Filter Logic
    const buttons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.card');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const category = btn.textContent.trim().toLowerCase();
            cards.forEach(card => {
                if (category.includes("all destinations") || card.dataset.category.includes(category)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
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
