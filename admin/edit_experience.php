<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = intval($_GET['id']);

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    
    // Cast to exact numbers
    $price = floatval($_POST['price']); 
    $capacity = intval($_POST['capacity']);
    $is_featured = intval($_POST['is_featured']);
    
    $duration = $_POST['duration'];
    $schedule = $_POST['schedule'];
    $difficulty = $_POST['difficulty'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $whats_included = $_POST['whats_included'];
    $not_included = $_POST['not_included'];
    $itinerary = $_POST['itinerary'];

    // Handle Image Upload
    $image = $_POST['old_image'];
    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    $sql = "UPDATE experiences SET
            name=?, category=?, location=?, price=?, duration=?, schedule=?, 
            capacity=?, difficulty=?, status=?, is_featured=?, description=?, 
            whats_included=?, not_included=?, itinerary=?, image=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    
    // Maps precisely to field types including the ID at the end
    $stmt->bind_param("sssdssississsssi", 
        $name, $category, $location, $price, $duration, $schedule, $capacity, 
        $difficulty, $status, $is_featured, $description, $whats_included, 
        $not_included, $itinerary, $image, $id
    );

    if ($stmt->execute()) {
        header("Location: experience_management.php?success=1");
        exit();
    } else {
        echo "SQL Error: " . $stmt->error;
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM experiences WHERE id=$id");
$row = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Experience</title>
    <link rel="stylesheet" href="experience.css">
</head>
<body>

<div class="modal-overlay">
    <div class="modal-content">

        <div class="modal-header">
            <h2>Edit Experience</h2>
            <a href="experience_management.php" class="close-btn">&times;</a>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?php echo $row['image']; ?>">

            <div class="form-grid">

                <div class="form-group full-width">
                    <label>Current Hero Image</label><br>
                    <img src="uploads/<?php echo $row['image']; ?>" style="width:120px;height:80px;object-fit:cover;border-radius:8px;">
                </div>

                <div class="form-group full-width">
                    <label>Change Hero Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="Adventure" <?php if($row['category']=="Adventure") echo "selected"; ?>>Adventure</option>
                        <option value="Nature" <?php if($row['category']=="Nature") echo "selected"; ?>>Nature</option>
                        <option value="Cultural" <?php if($row['category']=="Cultural") echo "selected"; ?>>Cultural</option>
                        <option value="Food" <?php if($row['category']=="Food") echo "selected"; ?>>Food</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" value="<?php echo htmlspecialchars($row['duration']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Schedule</label>
                    <input type="text" name="schedule" value="<?php echo htmlspecialchars($row['schedule']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Group Size / Capacity</label>
                    <input type="number" name="capacity" min="1" value="<?php echo $row['capacity']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Difficulty</label>
                    <select name="difficulty">
                        <option value="Easy" <?php if($row['difficulty']=="Easy") echo "selected"; ?>>Easy</option>
                        <option value="Moderate" <?php if($row['difficulty']=="Moderate") echo "selected"; ?>>Moderate</option>
                        <option value="Challenging" <?php if($row['difficulty']=="Challenging") echo "selected"; ?>>Challenging</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="Active" <?php if($row['status']=="Active") echo "selected"; ?>>Active</option>
                        <option value="Inactive" <?php if($row['status']=="Inactive") echo "selected"; ?>>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Featured</label>
                    <select name="is_featured">
                        <option value="0" <?php if($row['is_featured']==0) echo "selected"; ?>>No</option>
                        <option value="1" <?php if($row['is_featured']==1) echo "selected"; ?>>Yes</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label>Description</label>
                    <textarea name="description" rows="3" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label>What's Included (Put each item on a new line)</label>
                    <textarea name="whats_included" rows="4"><?php echo htmlspecialchars($row['whats_included']); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label>Not Included (Put each item on a new line)</label>
                    <textarea name="not_included" rows="4"><?php echo htmlspecialchars($row['not_included']); ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label>Itinerary (Type as many steps as you want!)</label>
                    <textarea name="itinerary" rows="6"><?php echo htmlspecialchars($row['itinerary']); ?></textarea>
                    <small style="color: #666;">📝 Put each event on a new line and use the pipe "|" to separate the fields.</small>
                </div>

                <div class="form-group full-width">
                    <label>Gallery Images (Select multiple files to overwrite or add)</label>
                    <input type="file" name="gallery_images[]" accept="image/*" multiple>
                </div>

            </div>

            <div class="modal-footer">
                <a href="experience.php" class="btn-cancel">Cancel</a>
                <button type="submit" name="update" class="btn-save">Update Experience</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
