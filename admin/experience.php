<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "EXperiences";
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM experiences WHERE 1";

if($search != ''){
    $sql .= " AND name LIKE '%".$conn->real_escape_string($search)."%'";
}

if($category != ''){
    $sql .= " AND category='".$conn->real_escape_string($category)."'";
}

if($difficulty != ''){
    $sql .= " AND difficulty='".$conn->real_escape_string($difficulty)."'";
}

if($status != ''){
    $sql .= " AND status='".$conn->real_escape_string($status)."'";
}

$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>
<doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="modal.css">
     
    <link rel="stylesheet" href="experience.css">
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="mgmt-container">
    <div class="header-section">
        <h1>Experience Management</h1>
        <p>Manage travel experiences and activities</p>
    </div>

    <!-- Filters Row (simplified for layout) -->
    <form method="GET" class="filter-bar">

    <div class="search-box">
        <i class="fa fa-search"></i>
        <input type="text" name="search" placeholder="Search experiences..."
               value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <select name="category" class="filter-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <option value="Adventure" <?php if($category=='Adventure') echo 'selected'; ?>>Adventure</option>
        <option value="Nature" <?php if($category=='Nature') echo 'selected'; ?>>Nature</option>
        <option value="Cultural" <?php if($category=='Cultural') echo 'selected'; ?>>Cultural</option>
    </select>

    <select name="difficulty" class="filter-select" onchange="this.form.submit()">
        <option value="">All Difficulty</option>
        <option value="Easy" <?php if($difficulty=='Easy') echo 'selected'; ?>>Easy</option>
        <option value="Moderate" <?php if($difficulty=='Moderate') echo 'selected'; ?>>Moderate</option>
        <option value="Hard" <?php if($difficulty=='Hard') echo 'selected'; ?>>Hard</option>
    </select>

    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="Active" <?php if($status=='Active') echo 'selected'; ?>>Active</option>
        <option value="Inactive" <?php if($status=='Inactive') echo 'selected'; ?>>Inactive</option>
    </select>

    <button type="submit" class="filter-btn">Search</button>

</form>

    <div class="table-actions">
        <button class="add-btn"><i class="fa fa-plus"></i> Add Experience</button>
        <?php
$total_exp = $conn->query("SELECT COUNT(*) as total FROM experiences")->fetch_assoc()['total'];
?>
<span class="count"><?php echo $total_exp; ?> total experiences</span>
    </div>

    <table class="exp-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>IMAGE</th>
                <th>NAME</th>
                <th>CATEGORY</th>
                <th>AVAILABILITY</th>
                <th>DIFFICULTY</th>
                <th>STATUS</th>
                <th>PRICE</th>
                <th>FEATURED</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="uploads/<?php echo $row['image']; ?>" class="thumb"></td>
                <td>
                    <strong><?php echo $row['name']; ?></strong>
                    <?php if($row['is_featured']): ?><i class="fa fa-star featured-star"></i><?php endif; ?>
                </td>
                <td><span class="badge category"><?php echo $row['category']; ?></span></td>
                <td><i class="fa fa-cube"></i> <?php echo $row['capacity']; ?></td>
                <td><span class="badge diff-<?php echo strtolower($row['difficulty']); ?>"><?php echo $row['difficulty']; ?></span></td>
                <td><span class="badge status-active"><?php echo $row['status']; ?></span></td>
                <td class="price">$<?php echo number_format($row['price']); ?></td>
                <td>
                    <label class="switch">
                        <input type="checkbox" <?php echo $row['is_featured'] ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                </td>
                <td class="actions">
    <a href="edit_experience.php?id=<?php echo $row['id']; ?>">
        <i class="fa-regular fa-pen-to-square"></i>
    </a>

    <a href="view_experience.php?id=<?php echo $row['id']; ?>">
        <i class="fa-regular fa-eye"></i>
    </a>

    <a href="delete_experience.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this experience?')">
        <i class="fa-regular fa-trash-can"></i>
    </a>
</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Overlay -->
<div id="addExpModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Experience</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form action="add_experience.php" method="POST" enctype="multipart/form-data">
    <div class="form-grid">

        <div class="form-group full-width">
            <label>Image</label>
            <input type="file" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category">
                <option value="Adventure">Adventure</option>
                <option value="Nature">Nature</option>
                <option value="Cultural">Cultural</option>
                <option value="Food">Food</option>
            </select>
        </div>

        <div class="form-group">
            <label>Availability</label>
            <input type="number" name="capacity" min="1">
        </div>

        <div class="form-group">
            <label>Difficulty</label>
            <select name="difficulty">
                <option value="Easy">Easy</option>
                <option value="Moderate">Moderate</option>
                <option value="Hard">Hard</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price">
        </div>

        <div class="form-group">
            <label>
    <input type="checkbox" name="is_featured" value="1">
    Featured
</label>
            <select name="is_featured">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group full-width">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn-cancel close-btn">Cancel</button>
        <button type="submit" name="submit" class="btn-save">Save Experience</button>
    </div>
</form>
    </div>
</div>

<script>
const modal = document.getElementById("addExpModal");
const addBtn = document.querySelector(".add-btn");
const closeBtns = document.querySelectorAll(".close-btn");

// Open modal
addBtn.onclick = () => modal.style.display = "block";

// Close modal (when clicking X, Cancel, or outside the box)
closeBtns.forEach(btn => {
    btn.onclick = () => modal.style.display = "none";
});

window.onclick = (event) => {
    if (event.target == modal) modal.style.display = "none";
}
</script>

</body></html>
