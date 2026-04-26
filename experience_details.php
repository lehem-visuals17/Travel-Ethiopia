<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $conn->query("SELECT * FROM experiences WHERE id = $id");
if ($result->num_rows == 0) {
    header("Location: experience.php");
    exit();
}
$row = $result->fetch_assoc();

// Decode JSON or handle strings stored for lists
$included = !empty($row['whats_included']) ? explode("\n", $row['whats_included']) : ["Guide service", "Local taxes"];
$not_included = !empty($row['not_included']) ? explode("\n", $row['not_included']) : ["Personal tips", "Souvenirs"];
$itinerary = !empty($row['itinerary']) ? explode("\n", $row['itinerary']) : ["Starting out", "Main event", "Return"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo htmlspecialchars($row['name']); ?> - Betora Travels</title>
   <link rel="stylesheet" href="https://cloudflare.com">
   <link rel="stylesheet" href="experience-details.css">
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
        <div class="quick-stats">
          <div class="stat"><i class="fa-solid fa-clock"></i> <div><strong>Duration</strong><br><?php echo htmlspecialchars($row['duration']); ?></div></div>
          <div class="stat"><i class="fa-solid fa-users"></i> <div><strong>Group Size</strong><br>Up to <?php echo $row['capacity']; ?> people</div></div>
          <div class="stat"><i class="fa-solid fa-calendar"></i> <div><strong>Schedule</strong><br><?php echo htmlspecialchars($row['schedule']); ?></div></div>
          <div class="stat"><i class="fa-solid fa-signal"></i> <div><strong>Difficulty</strong><br><?php echo htmlspecialchars($row['difficulty']); ?></div></div>
        </div>

        <section class="about-section">
          <h3>About This Experience</h3>
          <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        </section>

        <div class="lists-grid">
          <div class="list-box">
            <h4>What's Included</h4>
            <ul>
              <?php foreach($included as $inc): ?>
                <li><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars(trim($inc)); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="list-box">
            <h4>Not Included</h4>
            <ul class="not-included">
              <?php foreach($not_included as $notinc): ?>
                <li><i class="fa-solid fa-circle-xmark"></i> <?php echo htmlspecialchars(trim($notinc)); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right Column Sticky Sidebar -->
      <aside class="sidebar">
        <div class="booking-card">
          <div class="price-header">
            <span class="amount">$<?php echo number_format($row['price']); ?></span>
            <span class="per">per person</span>
          </div>
          <button class="book-now-btn">Book Now</button>
        </div>
      </aside>
    </div>
  </div>
</body>
</html>
