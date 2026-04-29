<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM blog_posts WHERE id = $id");
$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $author_name = $conn->real_escape_string($_POST['author_name']);
    $read_time = $conn->real_escape_string($_POST['read_time']);
    $summary = $conn->real_escape_string($_POST['summary']);
    $content = $conn->real_escape_string($_POST['content']);
    $cover_image = $conn->real_escape_string($_POST['cover_image']);
    $slider_images = $conn->real_escape_string($_POST['slider_images']);
    $status = $_POST['status'];

    $sql = "UPDATE blog_posts SET 
            title = '$title', 
            category = '$category', 
            author_name = '$author_name', 
            read_time = '$read_time', 
            summary = '$summary', 
            content = '$content', 
            cover_image = '$cover_image', 
            slider_images = '$slider_images', 
            status = '$status' 
            WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: blog.php?msg=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="blog.css">
</head>
<body>
    <div class="modal-content" style="display:block; margin: 50px auto; max-width: 700px;">
        <h2>Edit Blog Post</h2>
        
        <form method="POST">
            <div class="form-group">
                <label>Post Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            
            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Category</label>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($post['category']); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Author Name</label>
                    <input type="text" name="author_name" value="<?php echo htmlspecialchars($post['author_name']); ?>">
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label>Read Time</label>
                    <input type="text" name="read_time" value="<?php echo htmlspecialchars($post['read_time']); ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Status</label>
                    <select name="status">
                        <option value="published" <?php if($post['status'] == 'published') echo 'selected'; ?>>Published</option>
                        <option value="draft" <?php if($post['status'] == 'draft') echo 'selected'; ?>>Draft</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Short Summary</label>
                <textarea name="summary" rows="3"><?php echo htmlspecialchars($post['summary']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Full Content (HTML Allowed)</label>
                <textarea name="content" rows="5"><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Grid Cover Image URL</label>
                <input type="text" name="cover_image" value="<?php echo htmlspecialchars($post['cover_image'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Modal Slider Image URLs (Separated by commas)</label>
                <input type="text" name="slider_images" value="<?php echo htmlspecialchars($post['slider_images'] ?? ''); ?>">
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn-save">Update Post</button>
                <br>
                <a href="blog.php" style="display:inline-block; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
