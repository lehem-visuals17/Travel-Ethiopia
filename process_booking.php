<?php
include 'config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guide_id = $_POST['guide_id'];
    $date = $_POST['travel_date'];
    // ... other post variables

    $sql = "INSERT INTO bookings (guide_id, travel_date, ...) VALUES ('$guide_id', '$date', ...)";
    
    if(mysqli_query($conn, $sql)) {
        // Notification Logic
        $guide_res = mysqli_query($conn, "SELECT phone FROM guides WHERE id = $guide_id");
        $guide = mysqli_fetch_assoc($guide_res);
        
        // Example: Logic for sending SMS or adding to a 'notifications' table
        $msg = "New booking confirmed for $date. Check your dashboard.";
        mysqli_query($conn, "INSERT INTO notifications (guide_id, message) VALUES ('$guide_id', '$msg')");
        
        header("Location: success.php");
    }
}
