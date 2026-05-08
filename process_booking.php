<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $pass    = $_POST['confirm_password'];
    
    // 1. Check if password is correct
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        // PASSWORD CORRECT
        $dest_id = $_POST['destination_id'];
        $date    = $_POST['travel_date'];
        $count   = $_POST['people_count'];
        $guide   = $_POST['guide_id'];
        $amount  = $_POST['total_amount'];
        $method  = $_POST['method'];

        // --- NEW RESTRICTION LOGIC START ---
        // Count how many people are already booked for this guide on this date
        // We sum 'people_count' to ensure the total CUSTOMERS don't exceed 5
        $check_stmt = $conn->prepare("SELECT SUM(people_count) as current_total FROM bookings WHERE guide_id = ? AND travel_date = ? AND status != 'Cancelled'");
        $check_stmt->bind_param("is", $guide, $date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $booking_data = $check_result->fetch_assoc();
        $current_total = $booking_data['current_total'] ?? 0;

        if (($current_total + $count) > 5) {
            echo "<script>alert('Error: This guide can only take 5 customers per day. Remaining slots: " . (5 - $current_total) . "'); window.history.back();</script>";
            exit();
        }
        // --- NEW RESTRICTION LOGIC END ---

        // Save Booking using Prepared Statements
        $query1 = $conn->prepare("INSERT INTO bookings (user_id, destination_id, travel_date, people_count, guide_id) VALUES (?, ?, ?, ?, ?)");
        $query1->bind_param("iissi", $user_id, $dest_id, $date, $count, $guide);
        
        if ($query1->execute()) {
            $booking_id = $conn->insert_id;

            // Save Payment
            $query2 = $conn->prepare("INSERT INTO payments (booking_id, user_id, method, amount, status) VALUES (?, ?, ?, ?, 'completed')");
            $query2->bind_param("iisd", $booking_id, $user_id, $method, $amount);
            $query2->execute();

            // Mark Guide as Booked (Only if capacity is fully reached)
            if (($current_total + $count) >= 5) {
                $query3 = $conn->prepare("INSERT INTO guide_availability (guide_id, available_date, status) 
                                         VALUES (?, ?, 'booked') 
                                         ON DUPLICATE KEY UPDATE status='booked'");
                $query3->bind_param("is", $guide, $date);
                $query3->execute();
            }

            echo "<script>alert('Payment Successful! Your trip is booked.'); window.location.href='index.php';</script>";
        }
    } else {
        // PASSWORD INCORRECT
        echo "<script>alert('Error: Incorrect password. Payment could not be processed.'); window.history.back();</script>";
    }
}
?>