<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Get the ID safely
$guide_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($guide_id) {
    // 2. We need to find the user_id linked to this guide before we delete it
    $stmt = $conn->prepare("SELECT user_id FROM guides WHERE id = ?");
    $stmt->bind_param("i", $guide_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $guide = $result->fetch_assoc();

    if ($guide) {
        $user_id = $guide['user_id'];

        // 3. Start transaction to delete from both tables
        $conn->begin_transaction();

        try {
            // Delete the guide profile first
            $del_guide = $conn->prepare("DELETE FROM guides WHERE id = ?");
            $del_guide->bind_param("i", $guide_id);
            $del_guide->execute();

            // Delete the user account
            $del_user = $conn->prepare("DELETE FROM users WHERE id = ?");
            $del_user->bind_param("i", $user_id);
            $del_user->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            die("Error deleting: " . $e->getMessage());
        }
    }
}

// 4. Redirect back to the list
header("Location: guides.php");
exit();
?>
