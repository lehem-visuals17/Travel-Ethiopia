<?php
// 1. Database Connection
$conn = new mysqli("localhost", "root", "", "travel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_guide'])) {
    $destination_id = $_POST['destination_id'];
    $name           = $_POST['name'];
    $phone          = $_POST['phone'];
    $language       = $_POST['language'];
    $experience     = $_POST['experience_years'];
    $rating         = $_POST['rating'];
    
    // NEW CREDENTIALS logic
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'tour_guide';

    // Start Transaction
    $conn->begin_transaction();

    try {
        // STEP 1: Create the login account in 'users' table
        $stmt_user = $conn->prepare("INSERT INTO users (fullname, username, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt_user->bind_param("sssss", $name, $username, $password, $phone, $role);
        $stmt_user->execute();

        // Grab the ID of the user we just created
        $new_user_id = $conn->insert_id;

        // STEP 2: Handle Image Upload
        $image = "";
        if (!empty($_FILES['image']['name'])) {
            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0777, true);
            }
            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
        }

        // STEP 3: Create the Guide profile linked to that NEW User ID
        $stmt_guide = $conn->prepare("INSERT INTO guides (user_id, destination_id, name, phone, language, experience_years, rating, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // i = integer, s = string, d = double
        $stmt_guide->bind_param("iisssids", $new_user_id, $destination_id, $name, $phone, $language, $experience, $rating, $image);
        $stmt_guide->execute();

        // Commit changes
        $conn->commit();
        echo "<script>alert('Tour Guide added successfully! Login: $username'); window.location.href='guides.php';</script>";
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Tour Guide</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>
<div class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2>Add New Tour Guide</h2>
            <a href="guides.php" class="close-x">&times;</a>
        </div>
        <form method="POST" enctype="multipart/form-data" class="modal-form">
            <!-- 1. Destination Selection -->
            <div class="input-box">
                <label>Assigned Destination</label>
                <select name="destination_id" required>
                    <option value="">Select Destination</option>
                    <?php 
                    $dest_result = $conn->query("SELECT id, name FROM destinations ORDER BY name ASC");
                    while($dest = $dest_result->fetch_assoc()){
                        echo "<option value='".$dest['id']."'>".$dest['name']."</option>";
                    } 
                    ?>
                </select>
            </div>

            <!-- 2. Personal Info -->
            <div class="input-box">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="Enter guide's full name">
            </div>

            <!-- 3. LOGIN CREDENTIALS -->
            <div class="input-row">
                <div class="input-box">
                    <label>Login Username</label>
                    <input type="text" name="username" required placeholder="Unique username">
                </div>
                <div class="input-box">
                    <label>Login Password</label>
                    <input type="password" name="password" required placeholder="Create password">
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="09...">
                </div>
                <div class="input-box">
                    <label>Languages</label>
                    <input type="text" name="language" placeholder="e.g. Amharic, English">
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Experience (Years)</label>
                    <input type="number" name="experience_years" value="0">
                </div>
                <div class="input-box">
                    <label>Initial Rating</label>
                    <input type="text" name="rating" placeholder="5.0">
                </div>
            </div>

            <div class="input-box">
                <label>Profile Photo</label>
                <input type="file" name="image">
            </div>

            <div class="modal-actions">
                <a href="guides.php" class="btn-secondary">Cancel</a>
                <button type="submit" name="add_guide" class="btn-primary">Add Guide & Create Account</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
