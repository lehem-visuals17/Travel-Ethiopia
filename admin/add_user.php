<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Users";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    
    // Secure hashing
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role     = $_POST['role'];
    
    // Fallback defaults
    $username = $email; 
    $status   = 'active';
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, phone, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $fullname, $username, $email, $password, $phone, $role, $status, $created_at);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        
        // Success redirect back to the table
        header("Location: users.php"); 
        exit();
    } else {
        $message = "Error: " . $stmt->error;
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New User</title>
    <!-- Loaded functional SweetAlert2 tag -->
    <script src="https://jsdelivr.net"></script>
    
    <link rel="stylesheet" href="style.css"> 
    <!-- Replaced broken cloudflare link with FontAwesome -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <?php include "layout.php"; ?>
</head>
<body>

<div class="user-management-container">
    <div class="page-header">
        <a href="users.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <h1>Add New User</h1>
        <p>Create a new user account</p>
    </div>

    <div class="form-card">
        <!-- FIXED ACTION: Post directly to this same file -->
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
                <input type="password" name="password" placeholder="Enter password (min. 6 characters)" required minlength="6">
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
                <a href="users.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php if(!empty($message)): ?>
<script>
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: '<?php echo $message; ?>',
    })
</script>
<?php endif; ?>

</body>
</html>
