<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";
$pageTitle = "Blog";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Fetch posts matching your updated table structure
$sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog & Content Dashboard</title>
    <link rel="stylesheet" href="blog.css">
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-text">
            <h1>Blog & Content</h1>
            <p>Manage travel articles and blog posts</p>
        </div>
        <button class="btn-new">+ New Post</button>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="blog-card">
                <div class="card-left">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Thumbnail">
                </div>
                <div class="card-center">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p class="summary"><?php echo htmlspecialchars($row['summary']); ?></p>
                    <div class="meta-row">
                        <span class="tag-category"><?php echo htmlspecialchars($row['category']); ?></span>
                        <span class="tag-status"><?php echo htmlspecialchars($row['status']); ?></span>
                        <span class="meta-text">By <?php echo htmlspecialchars($row['author_name']); ?></span>
                        <span class="meta-text"><i class="fa-regular fa-eye"></i> <?php echo $row['views_count']; ?> views</span>
                    </div>
                    <p class="published-date">Published: <?php echo date('n/j/Y', strtotime($row['created_at'])); ?></p>
                </div>
                <div class="card-right">
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit"><i class="fa-solid fa-pen"></i> Edit</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete this post?')"><i class="fa-solid fa-trash-can"></i> Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>
</div>

<!-- The Modal Overlay -->
<div id="postModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add New Blog Post</h2>
      <span class="close-btn">&times;</span>
    </div>
    
    <form action="save_post.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Post Title</label>
        <input type="text" name="title" placeholder="e.g. Best Places in Ethiopia" required>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label>Category</label>
          <input type="text" name="category" placeholder="Destinations">
        </div>
        <div class="form-group">
          <label>Author Name</label>
          <input type="text" name="author_name" placeholder="Admin User">
        </div>
      </div>

      <div class="form-group">
        <label>Short Summary</label>
        <textarea name="summary" rows="2" placeholder="Brief description for the dashboard..."></textarea>
      </div>

      <div class="form-group">
        <label>Full Content</label>
        <textarea name="content" rows="5"></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Thumbnail Image</label>
          <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status">
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>
        </div>
      </div>

      <button type="submit" class="btn-save">Save Post</button>
    </form>
  </div>
</div>

<script>
// Simple script to toggle the popup
const modal = document.getElementById("postModal");
const btn = document.querySelector(".btn-new");
const close = document.querySelector(".close-btn");

btn.onclick = () => modal.style.display = "block";
close.onclick = () => modal.style.display = "none";
window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; }
</script>


</body>
</html>
