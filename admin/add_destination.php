<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_dest'])) {
    $name = $_POST['name'];
    $tagline = $_POST['tagline'];
    $region = $_POST['region'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $budget = $_POST['budget_cost'];
    $standard = $_POST['standard_cost'];
    $luxury = $_POST['luxury_cost'];
    $best_time = $_POST['best_time'];
    $weather = $_POST['weather_info'];

    // Image Upload Logic
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . $_FILES['image']['name']; // Added timestamp to prevent overwrite
        $target = "uploads/" . $image_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO destinations (name, tagline, region, type, description, budget_cost, standard_cost, luxury_cost, best_time, weather_info, image) 
                    VALUES ('$name', '$tagline', '$region', '$type', '$description', '$budget', '$standard', '$luxury', '$best_time', '$weather', '$image_name')";
            
            if ($conn->query($sql)) {
                echo "<script>alert('Destination Added Successfully!'); window.location='destinations.php';</script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="modal.css"> <!-- Make sure this path is correct -->
</head>
<body>

<div class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <div class="header-text">
                <h2>Add New Destination</h2>
                <p>Enter the destination details for Ethiopian tourism</p>
            </div>
            <a href="destinations.php" class="close-x" style="text-decoration:none;">&times;</a>
        </div>

        <!-- action="" sends it to the same page -->
        <form action="" method="POST" enctype="multipart/form-data" class="modal-form">
            <div class="input-box">
                <label>Destination Name</label>
                <input type="text" name="name" placeholder="e.g., Lalibela" required>
            </div>

            <div class="input-box">
                <label>Tagline</label>
                <input type="text" name="tagline" placeholder="Brief catchy description">
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Region</label>
                    <select name="region" required>
                        <option value="">Select region</option>
                        <option value="Amhara">Amhara</option>
                        <option value="Tigray">Tigray</option>
                        <option value="Oromia">Oromia</option>
                        <option value="Southern">Southern</option>
                    </select>
                </div>
                <div class="input-box">
                    <label>Type</label>
                    <select name="type" required>
                        <option value="">Select type</option>
                        <option value="adventure">Adventure</option>
                        <option value="cultural">Cultural</option>
                        <option value="religious">Religious</option>
                    </select>
                </div>
            </div>

            <div class="input-box">
                <label>Description</label>
                <textarea name="description" placeholder="Detailed description..."></textarea>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Budget Cost ($)</label>
                    <input type="text" name="budget_cost" placeholder="1200">
                </div>
                <div class="input-box">
                    <label>Standard Cost ($)</label>
                    <input type="text" name="standard_cost" placeholder="2500">
                </div>
            </div>

            <div class="input-row">
                <div class="input-box">
                    <label>Luxury Cost ($)</label>
                    <input type="text" name="luxury_cost" placeholder="5000">
                </div>
                <div class="input-box">
                    <label>Best Time to Visit</label>
                    <input type="text" name="best_time" placeholder="October to March">
                </div>
            </div>

            <div class="input-box">
                <label>Weather Info</label>
                <input type="text" name="weather_info" placeholder="Cool and dry, 15-25°C">
            </div>

            <div class="input-box">
                <label>Primary Featured Image</label>
                <input type="file" name="image" class="file-input" required>
            </div>

            <div class="modal-actions">
                <a href="destinations.php" class="btn-secondary" style="text-decoration:none; text-align:center;">Cancel</a>
                <button type="submit" name="create_dest" class="btn-primary">Create Destination</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
