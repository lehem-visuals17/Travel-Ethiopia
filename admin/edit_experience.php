<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$id = intval($_GET['id']);

if(isset($_POST['update'])){

    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $difficulty = $_POST['difficulty'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $is_featured = $_POST['is_featured'];

    $image = $_POST['old_image'];

    if(!empty($_FILES['image']['name'])){
        $image = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
    }

    $sql = "UPDATE experiences SET
            name=?,
            category=?,
            price=?,
            location=?,
            difficulty=?,
            capacity=?,
            description=?,
            image=?,
            status=?,
            is_featured=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssdssisssii",
        $name,
        $category,
        $price,
        $location,
        $difficulty,
        $capacity,
        $description,
        $image,
        $status,
        $is_featured,
        $id
    );

    $stmt->execute();

    header("Location: experience.php");
    exit();
}

$result = $conn->query("SELECT * FROM experiences WHERE id=$id");
$row = $result->fetch_assoc();
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
            <a href="experience.php" class="close-btn">&times;</a>
        </div>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="old_image" value="<?php echo $row['image']; ?>">

            <div class="form-grid">

                <div class="form-group full-width">
                    <label>Current Image</label><br>
                    <img src="uploads/<?php echo $row['image']; ?>" style="width:120px;height:80px;object-fit:cover;border-radius:8px;">
                </div>

                <div class="form-group full-width">
                    <label>Change Image</label>
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
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>">
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>">
                </div>

                <div class="form-group">
                    <label>Difficulty</label>
                    <select name="difficulty">
                        <option value="Easy" <?php if($row['difficulty']=="Easy") echo "selected"; ?>>Easy</option>
                        <option value="Moderate" <?php if($row['difficulty']=="Moderate") echo "selected"; ?>>Moderate</option>
                        <option value="Hard" <?php if($row['difficulty']=="Hard") echo "selected"; ?>>Hard</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Availability</label>
                    <input type="number" name="capacity" value="<?php echo $row['capacity']; ?>">
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
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
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