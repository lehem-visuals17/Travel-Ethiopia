<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount_badge = $_POST['discount_badge'];
    $deal_note = $_POST['deal_note'];
    $old_price = $_POST['old_price'];
    $new_price = $_POST['new_price'];
    $image_url = $_POST['image_url'];
    $end_datetime = $_POST['end_datetime'];

    $stmt = $conn->prepare("INSERT INTO deals (title, description, discount_badge, deal_note, old_price, new_price, image_url, end_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddss", $title, $description, $discount_badge, $deal_note, $old_price, $new_price, $image_url, $end_datetime);

    if ($stmt->execute()) {
        header("Location: admin_deals.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
