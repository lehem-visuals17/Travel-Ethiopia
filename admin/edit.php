<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM blog_posts WHERE id = $id");
$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $summary = $conn->real_escape_string($_POST['summary']);
    $status = $_POST['status'];

    // Handle Image Update (Only if a new file is uploaded)
    if (!empty($_FILES["image"]["name"])) {
        $target_file = "uploads/blog/" . time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image_sql = ", image = '$target_file'";
    } else {
        $image_sql = "";
    }

    $sql = "UPDATE blog_posts SET 
            title = '$title', 
            summary = '$summary', 
            status = '$status' 
            $image_sql 
            WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: blog.php?msg=updated");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="blog.css">
    <title>Edit Post</title>
</head>
<body>
    <div class="modal-content" style="display:block; margin: 50px auto;">
        <h2>Edit Post</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $post['title']; ?>">
            </div>
            <div class="form-group">
                <label>Summary</label>
                <textarea name="summary"><?php echo $post['summary']; ?></textarea>
            </div>
            <div class="form-row">
               <div class="form-group">
                    <label>Current Image</label><br>
                    <img src="<?php echo $post['image']; ?>" width="100">
                    <input type="file" name="image">
               </div>
               <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="published" <?php if($post['status']=='published') echo 'selected'; ?>>Published</option>
                        <option value="draft" <?php if($post['status']=='draft') echo 'selected'; ?>>Draft</option>
                    </select>
               </div>
            </div>
            <button type="submit" class="btn-save">Update Post</button>
            <a href="blog.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Cancel</a>
        </form>
    </div>
</body>
</html>
