<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author_name = $_POST['author_name'];
    $read_time = $_POST['read_time'];
    $status = $_POST['status'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $cover_image = $_POST['cover_image'];
    $slider_images = $_POST['slider_images'];

    // Generate a quick URL slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    $stmt = $conn->prepare("INSERT INTO blog_posts (title, slug, category, summary, content, cover_image, slider_images, author_name, read_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssss", $title, $slug, $category, $summary, $content, $cover_image, $slider_images, $author_name, $read_time, $status);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: blog.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
        $stmt->close();
        $conn->close();
    }
}
?>
