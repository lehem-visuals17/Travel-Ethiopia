<?php
session_start();
include '../db.php'; // Path to your connection file

// Inside save_review.php
if (isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $rating  = floatval($_POST['rating']);
    $comment = $_POST['comment'];
    $type    = $_POST['review_type']; // 'guide', 'destination', etc.

    $destination_id = ($type == 'destination') ? $_POST['destination_id'] : null;
    $package_id     = ($type == 'package') ? $_POST['package_id'] : null;
    $guide_id       = ($type == 'guide') ? $_POST['guide_id'] : null;
    $hotel_id       = ($type == 'hotel') ? $_POST['hotel_id'] : null;

    // We include the 'review_type' in the INSERT if you added that column, 
    // otherwise the guide page filters by guide_id.
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, destination_id, package_id, guide_id, hotel_id, rating, comment) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiids", $user_id, $destination_id, $package_id, $guide_id, $hotel_id, $rating, $comment);
    
    if ($stmt->execute()) {
        echo "<script>alert('Success!'); window.history.back();</script>";
    }
}

$conn->close();
?>
