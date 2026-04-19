<?php
$conn = new mysqli("localhost","root","","travel_db");
$pageTitle = "Bookings";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")
                       ->fetch_assoc()['total'];

$pending_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='pending'")
                         ->fetch_assoc()['total'];

$confirmed_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='confirmed'")
                           ->fetch_assoc()['total'];

$cancelled_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status='cancelled'")
                           ->fetch_assoc()['total'];

/* APPROVE BOOKING */
if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    $conn->query("UPDATE bookings SET status='confirmed' WHERE id='$id'");
    header("Location: bookings.php");
    exit();
}

/* REJECT BOOKING */
if(isset($_GET['reject'])){
    $id = $_GET['reject'];
    $conn->query("UPDATE bookings SET status='cancelled' WHERE id='$id'");
    header("Location: bookings.php");
    exit();
}

/* FETCH BOOKINGS */
$sql = "
SELECT bookings.*,
       users.fullname,
       packages.title AS package_title,
       destinations.name AS destination_name
FROM bookings
LEFT JOIN users ON bookings.user_id = users.id
LEFT JOIN packages ON bookings.package_id = packages.id
LEFT JOIN destinations ON bookings.destination_id = destinations.id
ORDER BY bookings.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking Management</title>
<link rel="stylesheet" href="booking.css">
 
<?php include "layout.php"; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dest-header">
        <div>
            <h1>Booking Management</h1>
            <p>Manage and track all customer bookings</p>
        </div>
</div>

<div class="booking-stats">
    <div class="stat-box total">
        <div class="stat-content">
            <p>Total Bookings</p>
            <h3><?php echo $total_bookings; ?></h3>
        </div>
        <i class="fa-regular fa-calendar-check"></i>
    </div>

    <div class="stat-box pending">
        <div class="stat-content">
            <p>Pending</p>
            <h3><?php echo $pending_bookings; ?></h3>
        </div>
        <i class="fa-regular fa-calendar"></i>
    </div>

    <div class="stat-box confirmed">
        <div class="stat-content">
            <p>Confirmed</p>
            <h3><?php echo $confirmed_bookings; ?></h3>
        </div>
        <i class="fa-regular fa-circle-check"></i>
    </div>
</div>


<div class="container">

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Package / Destination</th>
                    <th>Travel Date</th>
                    <th>People</th>
                    <th>Total Price</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>

                    <?php
                    $booking_name = !empty($row['package_title']) 
                        ? $row['package_title']
                        : $row['destination_name'];

                    $statusClass = strtolower($row['status']);
                    ?>

                    <tr>
                        <td><?php echo $row['fullname']; ?></td>
                        <td><?php echo $booking_name; ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['travel_date'])); ?></td>
                        <td><?php echo $row['people_count']; ?></td>
                        <td>$<?php echo number_format($row['total_price'],2); ?></td>
                        <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>

                        <td>
                            <span class="status-badge status-<?php echo $statusClass; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>

                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <div class="actions">
                                    <a href="bookings.php?approve=<?php echo $row['id']; ?>" class="btn approve">
                                        <i class="fa-solid fa-check"></i>
                                    </a>

                                    <a href="bookings.php?reject=<?php echo $row['id']; ?>" class="btn reject">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No bookings found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>