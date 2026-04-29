<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $conn->query("SELECT * FROM experiences WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: experience.php");
    exit();
}
$row = $result->fetch_assoc();

// Decode simple lists
$included = !empty($row['whats_included']) ? explode("\n", $row['whats_included']) : [];
$not_included = !empty($row['not_included']) ? explode("\n", $row['not_included']) : [];

// ✅ Protect against null values to stop errors
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

    <header class="detail-hero" style="background-image: url('uploads/<?php echo htmlspecialchars($row['image']); ?>');">
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
        <div class="booking-card">
          <div class="price-header">
            <span class="amount">$<?php echo number_format($row['price']); ?></span>
            <span class="per">per person</span>
          </div>
          
          <div class="booking-group">
            <label>Select Date</label>
            <input type="date" class="booking-input" placeholder="mm/dd/yyyy">
          </div>
          
          <div class="booking-group">
            <label>Select Time</label>
            <select class="booking-input">
              <option value="">Choose a time</option>
              <option value="11:00 AM">11:00 AM</option>
              <option value="5:00 PM">5:00 PM</option>
            </select>
          </div>

          <div class="booking-group">
            <label>Number of Guests</label>
            <div class="guest-counter">
              <button type="button" class="counter-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
              <input type="number" value="1" min="1" max="<?php echo $row['capacity']; ?>" readonly>
              <button type="button" class="counter-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
            </div>
          </div>

          <button class="book-now-btn">Book Now</button>

          <!-- Trust Badges Under Button -->
          <ul class="trust-list">
            <li><i class="fa-solid fa-check"></i> Free cancellation up to 24 hours</li>
            <li><i class="fa-solid fa-check"></i> Instant confirmation</li>
            <li><i class="fa-solid fa-check"></i> Mobile voucher accepted</li>
          </ul>
        </div>
      </aside>
    </div>
  </div>
</body>
</html>
