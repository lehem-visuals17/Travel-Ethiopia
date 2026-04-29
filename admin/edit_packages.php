<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $rating = $_POST['rating'];
    $reviews_count = $_POST['reviews_count'];
    $max_people = $_POST['max_people'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $badge_text = $_POST['badge_text'];
    $includes_list = $_POST['includes_list'];

    if (!empty($_FILES["image"]["name"])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
        $image_sql = ", image='$image'";
    } else { $image_sql = ""; }

        // ... your existing code above ...
    $sql = "UPDATE packages SET title='$title', type='$type', description='$description', price='$price', duration='$duration', rating='$rating', reviews_count='$reviews_count', max_people='$max_people', featured='$featured', badge_text='$badge_text', includes_list='$includes_list' $image_sql WHERE id=$id";

    if ($conn->query($sql)) {
        // ADD THESE TWO LINES HERE:
        header("Location: admin_packages.php");
        exit();
    }
}

$package = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Package</title>
    <link rel="stylesheet" href="admin_deals.css">
</head>
<body style="background:#fafafa; font-family:sans-serif; padding: 20px;">

<div style="background:#fff; margin:20px auto; padding:30px; width:60%; max-width:700px; border-radius:10px; border:1px solid #eef0f2;">
    <h2>Edit Tour Package</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <!-- FORM FIELDS ARE AN EXACT DUPLICATE OF ADD EXCEPT value="" TAGS LOAD LIVE ROW DATA -->
        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:2;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Package Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($package['title']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Category Type</label>
                <select name="type" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    <option value="honeymoon" <?php if($package['type'] == 'honeymoon') echo 'selected'; ?>>Honeymoon</option>
                    <option value="family" <?php if($package['type'] == 'family') echo 'selected'; ?>>Family</option>
                    <option value="adventure" <?php if($package['type'] == 'adventure') echo 'selected'; ?>>Adventure</option>
                    <option value="luxury" <?php if($package['type'] == 'luxury') echo 'selected'; ?>>Luxury</option>
                    <option value="budget" <?php if($package['type'] == 'budget') echo 'selected'; ?>>Budget</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"><?php echo htmlspecialchars($package['description']); ?></textarea>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Price ($)</label>
                <input type="number" step="0.01" name="price" value="<?php echo $package['price']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Duration</label>
                <input type="text" name="duration" value="<?php echo htmlspecialchars($package['duration']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Max People</label>
                <input type="number" name="max_people" value="<?php echo $package['max_people']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Star Rating</label>
                <input type="number" step="0.1" name="rating" value="<?php echo $package['rating']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Reviews Count</label>
                <input type="number" name="reviews_count" value="<?php echo $package['reviews_count']; ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Card Badge</label>
                <input type="text" name="badge_text" value="<?php echo htmlspecialchars($package['badge_text']); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
            </div>
        </div>

        <div style="margin-bottom:15px;">
            <label style="display:block; font-weight:bold; margin-bottom:5px;">Checklist Features (Separated by commas)</label>
            <input type="text" name="includes_list" value="<?php echo htmlspecialchars($package['includes_list'] ?? ''); ?>" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>

        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div style="flex:2;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Cover Image (Leave blank to keep old)</label>
                <input type="file" name="image" style="width:100%;">
            </div>
            <div style="flex:1; align-items:center; display:flex;">
                <label style="font-weight:bold;"><input type="checkbox" name="featured" value="1" <?php if($package['featured'] == 1) echo 'checked'; ?>> Feature on Home</label>
            </div>
        </div>

        <button type="submit" style="background:#ff9326; color:white; padding:12px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer; width:100%;">Update Package</button>
        <div style="text-align:center; margin-top:15px;">
            <a href="admin_packages.php" style="color:#777; text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>
