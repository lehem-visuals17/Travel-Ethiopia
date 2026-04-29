<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = intval($_GET['id']);

// HANDLE UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $discount_badge = $_POST['discount_badge'];
    $deal_note = $_POST['deal_note'];
    $old_price = $_POST['old_price'];
    $new_price = $_POST['new_price'];
    $image_url = $_POST['image_url'];
    $end_datetime = $_POST['end_datetime'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE deals SET title=?, description=?, discount_badge=?, deal_note=?, old_price=?, new_price=?, image_url=?, end_datetime=?, status=? WHERE id=?");
    $stmt->bind_param("ssssddsssi", $title, $description, $discount_badge, $deal_note, $old_price, $new_price, $image_url, $end_datetime, $status, $id);

    if ($stmt->execute()) {
        header("Location: admin_deals.php");
        exit();
    }
    $stmt->close();
}

// FETCH EXISTING DATA
$result = $conn->query("SELECT * FROM deals WHERE id = $id");
$deal = $result->fetch_assoc();

// Format date for HTML input
$formatted_date = date('Y-m-d\TH:i', strtotime($deal['end_datetime']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Deal</title>
    <!-- Kept your CSS link to maintain navigation layout if active -->
    <link rel="stylesheet" href="admin_deals.css">
</head>
<body style="background:#fafafa; font-family: 'Segoe UI', sans-serif;">

<div style="background:#fff; margin:50px auto; padding:30px; width:50%; max-width:600px; border-radius:10px; border: 1px solid #eef0f2; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Edit Deal</h2>
        <a href="admin_deals.php" style="color: #666; text-decoration: none; font-size: 24px;">&times;</a>
    </div>
    
    <form method="POST" style="margin-top: 15px;">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Package Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($deal['title']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;"><?php echo htmlspecialchars($deal['description']); ?></textarea>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Discount Badge</label>
                <input type="text" name="discount_badge" value="<?php echo htmlspecialchars($deal['discount_badge']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Promo Code</label>
                <input type="text" name="deal_note" value="<?php echo htmlspecialchars($deal['deal_note']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;">
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Original Price ($)</label>
                <input type="number" step="0.01" name="old_price" value="<?php echo $deal['old_price']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;" required>
            </div>
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Discounted Price ($)</label>
                <input type="number" step="0.01" name="new_price" value="<?php echo $deal['new_price']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;" required>
            </div>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Image URL</label>
            <input type="text" name="image_url" value="<?php echo htmlspecialchars($deal['image_url']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;" required>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 20px;">
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Deal Expiry Date & Time</label>
                <input type="datetime-local" name="end_datetime" value="<?php echo $formatted_date; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;" required>
            </div>
            <div style="flex: 1;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Status</label>
                <select name="status" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; box-sizing: border-box;">
                    <option value="active" <?php if($deal['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="expired" <?php if($deal['status'] == 'expired') echo 'selected'; ?>>Expired</option>
                </select>
            </div>
        </div>

        <button type="submit" style="background-color: #ff9326; color: white; border: none; padding: 10px 20px; border-radius: 4px; font-weight: 600; cursor: pointer; width: 100%;">Update Deal</button>
        
        <div style="text-align: center; margin-top: 15px;">
            <a href="admin_deals.php" style="color: #6c757d; font-size: 14px; text-decoration: none;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
