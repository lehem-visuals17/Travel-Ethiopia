<?php
session_start();
include 'db.php';

// Check if a guide is logged in
if (!isset($_SESSION['guide_id'])) {
    header("Location: login.php");
    exit();
}

$guide_id = $_SESSION['guide_id'];

// 1. Total Tours Completed (Status: completed)
$completed_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id = '$guide_id' AND travel_date < CURDATE()");
$total_completed = mysqli_fetch_assoc($completed_query)['total'];

// 2. Upcoming Bookings (Next 30 days)
$upcoming_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM bookings WHERE guide_id = '$guide_id' AND travel_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)");
$total_upcoming = mysqli_fetch_assoc($upcoming_query)['total'];

// 3. Total Earnings (Sum of payments for this guide's bookings)
$earnings_query = mysqli_query($conn, "SELECT SUM(p.amount) as total FROM payments p JOIN bookings b ON p.booking_id = b.id WHERE b.guide_id = '$guide_id' AND p.status = 'completed'");
$total_earnings = mysqli_fetch_assoc($earnings_query)['total'] ?? 0;

// 4. Total Reviews Received
$reviews_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM reviews WHERE guide_id = '$guide_id'");
$total_reviews = mysqli_fetch_assoc($reviews_count_query)['total'];

// 5. Recent Bookings List
$recent_bookings = mysqli_query($conn, "SELECT b.*, d.name as dest_name, u.full_name 
    FROM bookings b 
    JOIN destinations d ON b.destination_id = d.id 
    JOIN users u ON b.user_id = u.id 
    WHERE b.guide_id = '$guide_id' 
    ORDER BY b.travel_date DESC LIMIT 3");

// 6. Recent Reviews List
$recent_reviews = mysqli_query($conn, "SELECT r.*, u.full_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.guide_id = '$guide_id' 
    ORDER BY r.created_at DESC LIMIT 3");
?>

<div class="dashboard-container" style="padding: 20px; font-family: sans-serif; background: #f9f9f9;">
    <h2>Dashboard</h2>
    <p>Welcome back! Here's your overview</p>

    <!-- Top Summary Cards -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <small>Total Tours Completed</small>
            <h1 style="margin: 10px 0;"><?php echo $total_completed; ?></h1>
            <span style="color: #888; font-size: 12px;">Lifetime tours</span>
        </div>
        <div class="card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <small>Upcoming Bookings</small>
            <h1 style="margin: 10px 0;"><?php echo $total_upcoming; ?></h1>
            <span style="color: #888; font-size: 12px;">Next 30 days</span>
        </div>
        <div class="card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <small>Total Earnings</small>
            <h1 style="margin: 10px 0;">$<?php echo number_format($total_earnings, 2); ?></h1>
            <span style="color: #888; font-size: 12px;">All time</span>
        </div>
        <div class="card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <small>Reviews</small>
            <h1 style="margin: 10px 0;"><?php echo $total_reviews; ?></h1>
            <span style="color: #888; font-size: 12px;">Total reviews received</span>
        </div>
    </div>

    <!-- Bottom Lists Section -->
    <div class="list-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        <!-- Recent Bookings -->
        <div class="list-card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <h3>Recent Bookings</h3>
            <?php while($book = mysqli_fetch_assoc($recent_bookings)): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f4f4f4;">
                    <div>
                        <div style="font-weight: bold;"><?php echo $book['full_name']; ?></div>
                        <small style="color: #666;"><?php echo $book['dest_name']; ?></small>
                    </div>
                    <div style="color: #888; font-size: 13px;"><?php echo date('M d, Y', strtotime($book['travel_date'])); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Recent Reviews -->
        <div class="list-card" style="background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #eee;">
            <h3>Recent Reviews</h3>
            <?php while($rev = mysqli_fetch_assoc($recent_reviews)): ?>
                <div style="padding: 10px 0; border-bottom: 1px solid #f4f4f4;">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="font-weight: bold;"><?php echo $rev['full_name']; ?></div>
                        <div style="color: #f1c40f;">
                            <?php for($i=0; $i<$rev['rating']; $i++) echo "★"; ?>
                        </div>
                    </div>
                    <p style="margin: 5px 0; color: #666; font-size: 13px;">"<?php echo $rev['comment']; ?>"</p>
                </div>
            <?php endwhile; ?>
        </div>

    </div>
</div>
