<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $pass    = $_POST['confirm_password'];
    
    // 1. First, check if password is correct
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        // PASSWORD CORRECT - Proceed to save
        $dest_id = $_POST['destination_id'];
        $date    = $_POST['travel_date'];
        $count   = $_POST['people_count'];
        $guide   = $_POST['guide_id'];
        $amount  = $_POST['total_amount'];
        $method  = $_POST['method'];

        // Save Booking
        $query1 = "INSERT INTO bookings (user_id, destination_id, travel_date, people_count, guide_id) 
                   VALUES ('$user_id', '$dest_id', '$date', '$count', '$guide')";
        
        if (mysqli_query($conn, $query1)) {
            $booking_id = mysqli_insert_id($conn);

            // Save Payment as 'completed'
            $query2 = "INSERT INTO payments (booking_id, user_id, method, amount, status) 
                       VALUES ('$booking_id', '$user_id', '$method', '$amount', 'completed')";
            mysqli_query($conn, $query2);

            // Mark Guide as Booked
            mysqli_query($conn, "INSERT INTO guide_availability (guide_id, available_date, status) 
                                VALUES ('$guide', '$date', 'booked') 
                                ON DUPLICATE KEY UPDATE status='booked'");

            echo "<script>alert('Payment Successful! Your trip is booked.'); window.location.href='index.php';</script>";
        }
    } else {
        // PASSWORD INCORRECT
        echo "<script>alert('Error: Incorrect password. Payment could not be processed.'); window.history.back();</script>";
    }
}
?>
