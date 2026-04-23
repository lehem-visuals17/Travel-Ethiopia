<?php
include 'db.php';

$sql = "SELECT * FROM destinations";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://googleapis.com" rel="stylesheet">
  <link rel="stylesheet" href="https://cloudflare.com">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <link rel="stylesheet" href="welcome.css">
  <link rel="stylesheet" href="cards.css">
  <link rel="stylesheet" href="ai.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Discover Ethiopia-destinations</title>
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
    <li><a class="underline-text" href="index.html#Destinations">Destinations</a></li>
    <li><a class="underline-text" href="trip.html#trip">Trip Planner</a></li>
    <li><a class="underline-text" href="booking.html#Bookings">Bookings</a></li>
    <li><a class="underline-text" href="packages.html#packages">Packages</a></li>
    <li><a class="underline-text" href="experience.html#Experience">Experience</a></li>
    <li><a class="underline-text" href="blog.html#Blog">Blog</a></li>
    <li><a class="underline-text" href="deals.html#Deals">Deals</a></li>
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

   <section id="Destinations" class="destination-hero">

    <video autoplay muted loop playsinline class="bg-video">
        <source src="ethiopia-video.mp4" type="video/mp4">
    </video>

    <div class="overlay"></div>

    <div class="content">
        <h1>
            Discover <span>Ethiopia</span>
        </h1>
        <p>
            Explore breathtaking destinations across the land of origins
        </p>

        <!-- SEARCH BOX -->
        <div class="search-box">

            <div class="field">
                <label>Search</label>
             <div class="gradient-wrapper">
        
                <input type="text" placeholder="Search destinations..." />
            </div>
             </div>



            <div class="field">
                <label>Regions</label>
         <div class="gradient-wrapper">
                <select>
                    <option>All Regions</option>
                    <option>Amhara</option>
                    <option>Oromia</option>
                    <option>Tigray</option>
                    <option>Afar</option>
                    <option>Somali</option>
                    <option>SNNP</option>
                </select>
            </div>
        </div>

            <div class="field">
                <label>Cities</label>

            <div class="gradient-wrapper">
                <select>
                    <option>All Cities</option>
                    <option>Addis Ababa</option>
                    <option>Lalibela</option>
                    <option>Axum</option>
                    <option>Gondar</option>
                    <option>Bahir Dar</option>
                    <option>Hawassa</option>
                </select>
            </div>
            </div>

   <div class="field">
    <label>Travel Type</label>

    <div class="gradient-wrapper">
        <select>
            <option>All Types</option>
            <option>Cultural</option>
            <option>Adventure</option>
            <option>Relaxation</option>
        </select>
    </div>

</div>

            <!-- BUTTON BELOW -->
            <div class="search-btn-container">
                <button>Search</button>
            </div>

        </div>
    </div>

</section>


<!-- destination section -->
 <section class="filter-section">

    <div class="filter-container">

        <button class="filter-btn active">
            <span>🌍</span> All Destinations
        </button>

        <button class="filter-btn">
            <span>🔥</span> Trending
        </button>

        <button class="filter-btn">
            <span>💎</span> Hidden Gems
        </button>

        <button class="filter-btn">
            <span>🏨</span> Luxury
        </button>

        <button class="filter-btn">
            <span>💰</span> Budget
        </button>

    </div>
    </section>

<section class="cards-section">

    <h2 class="results-title">All Destinations</h2>

    <div class="cards-container">

        <!-- CARD 1 -->
       <?php while($row = mysqli_fetch_assoc($result)) { ?>
<div class="card">
    <div class="card-slider">
        <img src="images/<?php echo $row['image1']; ?>">
        <img src="images/<?php echo $row['image2']; ?>">
        <img src="images/<?php echo $row['image3']; ?>">
        <img src="<?php echo $row['image4']; ?>">

        <div class="image-text">
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['region']; ?></p>
        </div>

        <div class="rating">
            <i class="fas fa-star" style="color: rgb(255, 208, 0);"></i>
            <?php echo $row['rating']; ?>
        </div>
    </div>

    <div class="card-inner">
        <div class="card-content">
            <p class="desc"><?php echo $row['short_description']; ?></p>

            <div class="tags">
                <button><?php echo $row['tag1']; ?></button>
                <button><?php echo $row['tag2']; ?></button>
            </div>

            <div class="card-footer">
                <span class="price">
                    <i class="fas fa-dollar-sign"></i>
                    <?php echo $row['price_range']; ?>/day
                </span>

                <span class="reviews">
                    <?php echo $row['reviews']; ?> reviews
                </span>
            </div>

            <a href="destination-details.php?id=<?php echo $row['id']; ?>" class="details-btn">
                View Details
            </a>
        </div>
    </div>
</div>
<?php } ?>

        <!-- CARD 2 -->
        <div class="card">
            <div class="card-slider">
                 <img src="images/lalibeladd2.jpg" alt="">
                <img src="images/lalibeladd.jpg" alt="">
                <img src="images/lalibeladd2.jpg" alt="">
                <img src="images/lalibeladd.jpg" alt="">
                <div class="image-text">
                    <h3>Lalibela Churches</h3>
                    <p>Amhara Region</p>
                </div>
                <div class="rating"><i class="fas fa-star" style="color:  rgb(255, 208, 0);"></i> 4.9</div>
            </div>
            <div class="card-inner">
                <div class="card-content">
                    <p class="desc">Ancient monolithic churches carved into the rocks</p>
                    <div class="tags">
                        <button>UNESCO Site</button>
                        <button>Historical</button>
                    </div>
                    <div class="card-footer">
                        <span class="price"><i class="fas fa-dollar-sign" style="color:  rgb(255, 208, 0);"></i>200-350/day</span>
                        <span class="reviews">102 reviews</span>
                    </div>
                     <a href="lalibela.html" class="details-btn">View Details</a> 
                </div>
            </div>
        </div>

        <!-- CARD 3 -->
        <div class="card">
            <div class="card-slider">
                <img src="images/omov1.jpg" class="active">
                <img src="images/omov2.jpg">
                 <img src="images/lalibeladd2.jpg" alt="">
                <img src="images/lalibeladd.jpg" alt=""><div class="image-text">
                    <h3>Omo Valley</h3>
                    <p>SNNPR Region</p>
                </div>
                <div class="rating"><i class="fas fa-star"  style="color:  rgb(255, 208, 0);"></i> 4.6</div>
            </div>
            <div class="card-inner">
                <div class="card-content">
                    <p class="desc">Cultural exploration with vibrant tribal communities</p>
                    <div class="tags">
                        <button>Cultural</button>
                        <button>Adventure</button>
                    </div>
                    <div class="card-footer">
                        <span class="price"><i class="fas fa-dollar-sign"></i>120-200/day</span>
                        <span class="reviews">76 reviews</span>
                    </div>
                    <a href="omo valley.html" class="details-btn">View Details</a> 
                </div>
            </div>
        </div>

    </div>
</section>



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
  
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            
            // Remove active from all
            buttons.forEach(b => b.classList.remove('active'));
            
            // Add active to clicked
            btn.classList.add('active');
        });
    });

    // cards java script code




/* IMAGE SLIDER */
document.querySelectorAll('.card-slider').forEach(slider => {
    let images = slider.querySelectorAll('img');
    let index = 0;

    setInterval(() => {
        images[index].classList.remove('active');
        index = (index + 1) % images.length;
        images[index].classList.add('active');
    }, 3000);
});


/* FILTER COUNT */
const filterButtons = document.querySelectorAll('.filter-btn');
const cards = document.querySelectorAll('.card');
const resultText = document.getElementById('result-count');

filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {

        let category = btn.innerText.toLowerCase();
        let count = 0;

        cards.forEach(card => {
            if (category === "all destinations") {
                card.style.display = "block";
                count++;
            } else {
                if (card.dataset.category.includes(category)) {
                    card.style.display = "block";
                    count++;
                } else {
                    card.style.display = "none";
                }
            }
        });

        resultText.innerText = count + " destinations found";
    });
});


// for the image slider dots

document.querySelectorAll('.card-slider').forEach(slider => {
    const slides = slider.querySelectorAll('.slides img');
    const dots = slider.querySelectorAll('.dot');
    let current = 0;

    function showSlide(index) {
        slides.forEach((img, i) => {
            img.classList.toggle('active', i === index);
            dots[i].classList.toggle('active', i === index);
        });
    }

    // Auto-slide every 3s
    setInterval(() => {
        current = (current + 1) % slides.length;
        showSlide(current);
    }, 3000);

    // Dot click
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            current = i;
            showSlide(current);
        });
    });
});

</script>
</body>
</html>