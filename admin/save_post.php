<?php
// 1. Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Collect and Sanitize Data
    $title       = $conn->real_escape_string($_POST['title']);
    $category    = $conn->real_escape_string($_POST['category']);
    $author_name = $conn->real_escape_string($_POST['author_name']);
    $summary     = $conn->real_escape_string($_POST['summary']);
    $content     = $conn->real_escape_string($_POST['content']);
    $status      = $conn->real_escape_string($_POST['status']);
    
    // Create a simple URL slug (e.g., "Hello World" -> "hello-world")
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    // 3. Handle Image Upload
    $target_dir = "uploads/blog/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;
    } else {
        $image_path = "uploads/blog/default.jpg"; // Fallback image
    }

    // 4. Insert into Database
    $sql = "INSERT INTO blog_posts (title, slug, category, summary, content, author_name, status, image) 
            VALUES ('$title', '$slug', '$category', '$summary', '$content', '$author_name', '$status', '$image_path')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the dashboard on success
        header("Location: blog.php?status=success");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
