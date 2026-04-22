<?php
$conn = new mysqli("localhost","root","","travel_db");

if(!isset($_GET['id'])){
    header("Location: experience.php");
    exit();
}

$id = intval($_GET['id']);

$exp = $conn->query("
    SELECT * FROM experiences 
    WHERE id='$id'
")->fetch_assoc();

if(!$exp){
    header("Location: experience.php");
    exit();
}

/* Total bookings */
$total_bookings = $conn->query("
    SELECT COUNT(*) as total 
    FROM experience_bookings
    WHERE experience_id='$id'
")->fetch_assoc()['total'];

/* Total revenue */
$total_revenue = $conn->query("
    SELECT SUM(total_price) as total 
    FROM experience_bookings
    WHERE experience_id='$id'
")->fetch_assoc()['total'] ?? 0;

/* Unique customers */
$total_customers = $conn->query("
    SELECT COUNT(DISTINCT user_id) as total 
    FROM experience_bookings
    WHERE experience_id='$id'
")->fetch_assoc()['total'];

/* Recent bookings */
$bookings = $conn->query("
    SELECT eb.*, u.fullname
    FROM experience_bookings eb
    LEFT JOIN users u ON eb.user_id = u.id
    WHERE eb.experience_id='$id'
    ORDER BY eb.id DESC
    LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Experience</title>
    <link rel="stylesheet" href="experience.css">
</head>
<body>

<div class="view-container">

    <a href="experience.php" class="back-btn">← Back</a>

    <div class="view-header">
        <img src="uploads/<?php echo $exp['image']; ?>" class="view-img">

        <div class="view-info">
            <h1><?php echo $exp['name']; ?></h1>
            <p><?php echo $exp['description']; ?></p>

            <div class="tags">
                <span><?php echo $exp['category']; ?></span>
                <span><?php echo $exp['difficulty']; ?></span>
                <span><?php echo $exp['status']; ?></span>
                <span>$<?php echo number_format($exp['price']); ?></span>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h2><?php echo $total_bookings; ?></h2>
            <p>Total Bookings</p>
        </div>

        <div class="stat-box">
            <h2>$<?php echo number_format($total_revenue); ?></h2>
            <p>Total Revenue</p>
        </div>

        <div class="stat-box">
            <h2><?php echo $total_customers; ?></h2>
            <p>Customers</p>
        </div>

        <div class="stat-box">
            <h2><?php echo $exp['capacity']; ?></h2>
            <p>Capacity</p>
        </div>
    </div>

    <div class="booking-table">
        <h2>Recent Bookings</h2>

        <table>
            <tr>
                <th>Customer</th>
                <th>People</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php while($row = $bookings->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['fullname']; ?></td>
                <td><?php echo $row['people_count']; ?></td>
                <td>$<?php echo number_format($row['total_price']); ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</div>

</body>
</html>