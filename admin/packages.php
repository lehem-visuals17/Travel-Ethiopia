<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$pageTitle = "Packages";
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tour Packages</title>
    <!-- We will use your custom design for the popup layout -->
    <link rel="stylesheet" href="admin_deals.css"> 
    
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="package.css?v=1.1">

    
</head>
<body>
    <?php include "layout.php"; ?>
    <div class="admin-view-wrapper">
    <div class="admin-controls">
        <div>
            <h1>Manage Tour Packages</h1>
            <p>Add, edit, and control featured site packages</p>
        </div>
        <!-- Trigger Modal for ADD -->
        <button onclick="openAddModal()" style="background:#ff9326; color:white; padding:12px 20px; border:none; border-radius:6px; cursor:pointer; font-weight:600; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-plus"></i> Create New
        </button>
    </div>

    <section class="packages">
        <div class="package-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <div style="height:180px; overflow:hidden;">
                        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div class="card-content">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <h3 style="margin:0; font-size:18px;"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <?php if(!empty($row['badge_text'])): ?>
                                <span style="background:#ff4757; color:white; font-size:11px; padding:3px 6px; border-radius:4px; font-weight:bold;"><?php echo htmlspecialchars($row['badge_text']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="budget">$<?php echo number_format($row['price'], 2); ?></div>
                        
                        <div style="font-size:13px; color:#777;">
                            <span><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($row['duration']); ?></span> | 
                            <span><i class="fa-solid fa-star" style="color:#f1c40f;"></i> <?php echo $row['rating']; ?> (<?php echo $row['reviews_count']; ?>)</span>
                        </div>
                    </div>
                    <div class="admin-actions">
    <!-- DETAILS -->
    <a href="package_details.php?id=<?php echo $row['id']; ?>" class="btn-details">
        <i class="fa-regular fa-eye"></i> Details
    </a>

    <!-- EDIT -->
    <button class="btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
        <i class="fa-regular fa-pen-to-square"></i> Edit
    </button>

    <!-- DELETE -->
    <a href="delete_package.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this?')">
        <i class="fa-regular fa-trash-can"></i> Delete
    </a>
</div>

                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- UNIFIED POP-UP MODAL -->
    <div id="packageModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6); overflow-y: auto;">
        <div style="background:#fff; margin:20px auto; padding:30px; width:60%; max-width:700px; border-radius:10px; border:1px solid #eef0f2; position:relative;">
            <span onclick="closeModal()" style="position:absolute; right:20px; top:15px; font-size:28px; cursor:pointer;">&times;</span>
            <h2 id="modalTitle">Create New Tour Package</h2>
            
            <form action="save_package.php" method="POST" enctype="multipart/form-data">
                <!-- Hidden input dictates whether it is an insert or update -->
                <input type="hidden" name="id" id="pkg_id" value="">

                <div style="display:flex; gap:15px; margin-bottom:15px;">
                    <div style="flex:2;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Package Title</label>
                        <input type="text" name="title" id="pkg_title" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Category Type</label>
                        <select name="type" id="pkg_type" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                            <option value="honeymoon">Honeymoon</option>
                            <option value="family">Family</option>
                            <option value="adventure">Adventure</option>
                            <option value="luxury">Luxury</option>
                            <option value="budget">Budget</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Description</label>
                    <textarea name="description" id="pkg_description" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
                </div>

                <div style="display:flex; gap:15px; margin-bottom:15px;">
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Price ($)</label>
                        <input type="number" step="0.01" name="price" id="pkg_price" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Duration</label>
                        <input type="text" name="duration" id="pkg_duration" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Max People</label>
                        <input type="number" name="max_people" id="pkg_max_people" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                    </div>
                </div>

                <div style="display:flex; gap:15px; margin-bottom:15px;">
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Star Rating</label>
                        <input type="number" step="0.1" name="rating" id="pkg_rating" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Reviews Count</label>
                        <input type="number" name="reviews_count" id="pkg_reviews_count" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Card Badge</label>
                        <input type="text" name="badge_text" id="pkg_badge_text" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                    </div>
                </div>

                <div style="margin-bottom:15px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Checklist Features (Separated by commas)</label>
                    <input type="text" name="includes_list" id="pkg_includes_list" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div style="display:flex; gap:15px; margin-bottom:15px;">
                    <div style="flex:2;">
                        <label style="display:block; font-weight:bold; margin-bottom:5px;">Cover Image <span id="img_label" style="font-weight:normal; color:#888;"></span></label>
                        <input type="file" name="image" id="pkg_image" style="width:100%;">
                    </div>
                    <div style="flex:1; align-items:center; display:flex;">
                        <label style="font-weight:bold;"><input type="checkbox" name="featured" id="pkg_featured" value="1"> Feature on Home</label>
                    </div>
                </div>

                <button type="submit" id="saveBtn" style="background:#ff9326; color:white; padding:12px 20px; border:none; border-radius:6px; font-weight:bold; cursor:pointer; width:100%;">Save Package</button>
            </form>
        </div>
    </div></div>

    <!-- MODAL JS LOGIC -->
    <script>
    const modal = document.getElementById("packageModal");

    function openAddModal() {
        document.getElementById("modalTitle").innerText = "Create New Tour Package";
        document.getElementById("saveBtn").innerText = "Save Package";
        document.getElementById("pkg_id").value = "";
        
        // Clear all inputs
        document.getElementById("pkg_title").value = "";
        document.getElementById("pkg_type").value = "honeymoon";
        document.getElementById("pkg_description").value = "";
        document.getElementById("pkg_price").value = "";
        document.getElementById("pkg_duration").value = "";
        document.getElementById("pkg_max_people").value = "1";
        document.getElementById("pkg_rating").value = "";
        document.getElementById("pkg_reviews_count").value = "";
        document.getElementById("pkg_badge_text").value = "";
        document.getElementById("pkg_includes_list").value = "";
        document.getElementById("pkg_featured").checked = false;
        
        document.getElementById("pkg_image").required = true;
        document.getElementById("img_label").innerText = "";

        modal.style.display = "block";
    }

    function openEditModal(data) {
        document.getElementById("modalTitle").innerText = "Edit Tour Package";
        document.getElementById("saveBtn").innerText = "Update Package";
        
        // Fill data from the row
        document.getElementById("pkg_id").value = data.id;
        document.getElementById("pkg_title").value = data.title;
        document.getElementById("pkg_type").value = data.type;
        document.getElementById("pkg_description").value = data.description;
        document.getElementById("pkg_price").value = data.price;
        document.getElementById("pkg_duration").value = data.duration;
        document.getElementById("pkg_max_people").value = data.max_people;
        document.getElementById("pkg_rating").value = data.rating;
        document.getElementById("pkg_reviews_count").value = data.reviews_count;
        document.getElementById("pkg_badge_text").value = data.badge_text;
        document.getElementById("pkg_includes_list").value = data.includes_list;
        document.getElementById("pkg_featured").checked = (data.featured == 1);
        
        // Image is not required on edit
        document.getElementById("pkg_image").required = false;
        document.getElementById("img_label").innerText = "(Leave blank to keep old)";

        modal.style.display = "block";
    }

    function closeModal() { modal.style.display = "none"; }
    window.onclick = (e) => { if (e.target == modal) closeModal(); }
    </script>
</body>
</html>
