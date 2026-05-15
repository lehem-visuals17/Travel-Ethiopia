<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
     <link rel="stylesheet" href="packages.css">
</head>
<body>
    <!-- Back Button -->
<a href="packages.php" class="back-btn">
    <i class="fa-solid fa-arrow-left"></i> Back to Packages
</a>

<section class="details-page">
    <div class="details-hero">
        <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/691447030.jpg?k=515423bb8ea2fc07ba0166497881adbc81522d9e3a26b84acd3f13bdcf94709c&o=" alt="Beynouna Honeymoon">
        <div class="hero-overlay">
            <h1>Beynouna Honeymoon</h1>
            <p><i class="fa-solid fa-location-dot"></i> On the shores of Lake Beseka, near Metehara, Ethiopia</p>
        </div>
    </div>

    <div class="info-bar">
        <div class="notes-container">
            <!-- These notes now zoom on touch -->
            <div class="info-note" data-hover="Activity level: relaxing">
                <i class="fa-solid fa-leaf"></i>
                <div><span>Pace</span><strong>Relaxing</strong></div>
            </div>
            <div class="info-note" data-hover="Maximum group capacity">
                <i class="fa-solid fa-users"></i>
                <div><span>Group Size</span><strong>Max 2 people</strong></div>
            </div>
            <div class="info-note" data-hover="Couples & Honeymooners">
                <i class="fa-solid fa-heart"></i>
                <div><span>Best for</span><strong>Couples</strong></div>
            </div>
        </div>

        <div class="status-container">
            <!-- These status buttons now have hover effects -->
            <span class="status-btn booked"><i class="fa-solid fa-fire"></i> 3 booked in last 24 hrs</span>
            <span class="status-btn spots"><i class="fa-solid fa-hourglass-half"></i> Only 2 spots left</span>
        </div>
    </div>

    <!-- Thin Golden Line -->
    <hr class="golden-divider">
</section>


<section class="itinerary-section">
    <h2 class="section-title">Interactive Itinerary</h2>
    <div class="itinerary-split">
        <div class="itinerary-left">
            
            <!-- Day 1 -->
            <div class="day-card">
                <div class="day-header" onclick="toggleDay(this)">
                    <div class="day-title"><span class="day-label">Day 1</span><h3>Arrival & Welcome</h3></div>
                    <i class="fa-solid fa-chevron-down v-icon"></i>
                </div>
                <div class="day-content">
                    <p class="day-intro">Arrive at Lake Beseka and unwind at the private resort.</p>
                    <div class="plan-grid vertical-stack">
                        <div class="plan-card"><i class="fa-solid fa-plane-arrival"></i><h4>Arrival</h4><span class="time-meta">07:00 • 2h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-map-location-dot"></i><h4>Tour to the resort</h4><span class="time-meta">09:30 • 2h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-utensils"></i><h4>Lake side dinner</h4><span class="time-meta">11:30 • 1h</span></div>
                    </div>
                </div>
            </div>

            <!-- Day 2 -->
            <div class="day-card">
                <div class="day-header" onclick="toggleDay(this)">
                    <div class="day-title"><span class="day-label">Day 2</span><h3>Nature Exploration</h3></div>
                    <i class="fa-solid fa-chevron-down v-icon"></i>
                </div>
                <div class="day-content">
                    <p class="day-intro">Explore the volcanic wonders and local wildlife.</p>
                    <div class="plan-grid vertical-stack">
                        <div class="plan-card"><i class="fa-solid fa-binoculars"></i><h4>Bird Watching</h4><span class="time-meta">06:00 • 3h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-mountain-sun"></i><h4>Volcanic Trek</h4><span class="time-meta">10:00 • 4h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-camera"></i><h4>Sunset Photos</h4><span class="time-meta">05:30 • 1h</span></div>
                    </div>
                </div>
            </div>

            <!-- Day 3 -->
            <div class="day-card">
                <div class="day-header" onclick="toggleDay(this)">
                    <div class="day-title"><span class="day-label">Day 3</span><h3>Relaxation & Spa</h3></div>
                    <i class="fa-solid fa-chevron-down v-icon"></i>
                </div>
                <div class="day-content">
                    <div class="plan-grid vertical-stack">
                        <div class="plan-card"><i class="fa-solid fa-spa"></i><h4>Thermal Spa</h4><span class="time-meta">09:00 • 3h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-book-open"></i><h4>Leisure Time</h4><span class="time-meta">02:00 • 4h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-wine-glass-empty"></i><h4>Private Lounge</h4><span class="time-meta">08:00 • 2h</span></div>
                    </div>
                </div>
            </div>

            <!-- Day 4 -->
            <div class="day-card">
                <div class="day-header" onclick="toggleDay(this)">
                    <div class="day-title"><span class="day-label">Day 4</span><h3>Cultural Experience</h3></div>
                    <i class="fa-solid fa-chevron-down v-icon"></i>
                </div>
                <div class="day-content">
                    <div class="plan-grid vertical-stack">
                        <div class="plan-card"><i class="fa-solid fa-wheat-awn"></i><h4>Cooking Class</h4><span class="time-meta">11:00 • 3h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-people-group"></i><h4>Village Visit</h4><span class="time-meta">03:00 • 2h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-music"></i><h4>Traditional Music</h4><span class="time-meta">07:30 • 2h</span></div>
                    </div>
                </div>
            </div>

            <!-- Day 5 -->
            <div class="day-card">
                <div class="day-header" onclick="toggleDay(this)">
                    <div class="day-title"><span class="day-label">Day 5</span><h3>Departure</h3></div>
                    <i class="fa-solid fa-chevron-down v-icon"></i>
                </div>
                <div class="day-content">
                    <div class="plan-grid vertical-stack">
                        <div class="plan-card"><i class="fa-solid fa-mug-hot"></i><h4>Sunrise Breakfast</h4><span class="time-meta">07:00 • 1h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-suitcase"></i><h4>Gift Shopping</h4><span class="time-meta">09:00 • 2h</span></div>
                        <div class="plan-card"><i class="fa-solid fa-van-shuttle"></i><h4>Airport Transfer</h4><span class="time-meta">12:00 • 3h</span></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="itinerary-right">
    <h2 class="section-title">What's Included</h2>
    
    <!-- Included Card -->
    <div class="inclusion-card">
        <div class="inc-header">
            <i class="fa-solid fa-circle-check icon-green"></i>
            <h3>Included</h3>
        </div>
        <ul class="inc-list">
            <li><i class="fa-solid fa-check"></i> Flight</li>
            <li><i class="fa-solid fa-check"></i> Hotel</li>
            <li><i class="fa-solid fa-check"></i> Dinner</li>
            <li><i class="fa-solid fa-check"></i> Spa Treatment</li>
            <li><i class="fa-solid fa-check"></i> Private Beach Access</li>
        </ul>
    </div>

    <!-- Not Included Card -->
    <div class="inclusion-card">
        <div class="inc-header">
            <i class="fa-solid fa-circle-xmark icon-red"></i>
            <h3>Not Included</h3>
        </div>
        <ul class="inc-list">
            <li><i class="fa-solid fa-xmark"></i> Airport Transfer</li>
            <li><i class="fa-solid fa-xmark"></i> Personal Expenses</li>
            <li><i class="fa-solid fa-xmark"></i> Lunch & Breakfast</li>
            <li><i class="fa-solid fa-xmark"></i> Travel Insurance</li>
        </ul>
    </div>
</div>

    </div>
</section>


<script>
    
function toggleDay(element) {
    const card = element.parentElement;
    card.classList.toggle('active');
}
</script>
</body>
</html>