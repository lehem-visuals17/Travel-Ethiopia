<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Users";
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing
    $role     = $_POST['role'];
    
    // Since your DB requires a 'username', we'll use the email as a default
    $username = $email; 

    $sql = "INSERT INTO users (fullname, username, email, password, phone, role) 
            VALUES ('$fullname', '$username', '$email', '$password', '$phone', '$role')";

    if ($conn->query($sql) === TRUE) {
        header("Location: users_list.php"); // Redirect back to your dashboard
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <!-- SweetAlert2 for beautiful popups -->
<script src="https://jsdelivr.net"></script>

    <link rel="stylesheet" href="style.css"> <!-- Reusing your existing CSS -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <?php include 
    
    "layout.php"; ?>
</head>
<body>

<div class="user-management-container">
    <div class="page-header">
        <a href="users.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <h1>Add New User</h1>
        <p>Create a new user account</p>
    </div>

    <div class="form-card">
        <form action="add_user.php" method="POST">
            <h3>User Information</h3>
            
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="fullname" placeholder="Enter full name" required>
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" placeholder="Enter email address" required>
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" placeholder="+251 912 345 678" required>
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" placeholder="Enter password (min. 6 characters)" required>
            </div>

            <div class="form-group">
                <label>Role *</label>
                <select name="role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-create">Create User</button>
                <a href="users_list.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
