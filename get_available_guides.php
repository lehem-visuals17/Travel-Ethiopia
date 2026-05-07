-<?php
include 'db.php'; 
$date = $_GET['date'];
$dest_id = $_GET['dest_id'];

// Query for guides at this destination NOT booked on this specific date
$sql = "SELECT id, name, experience_years FROM guides 
        WHERE destination_id = $dest_id 
        AND id NOT IN (
            SELECT guide_id FROM bookings 
            WHERE travel_date = '$date' AND status != 'cancelled'
        )";

$result = mysqli_query($conn, $sql);
$guides = [];
while($row = mysqli_fetch_assoc($result)) {
    $guides[] = $row;
}
echo json_encode($guides);
