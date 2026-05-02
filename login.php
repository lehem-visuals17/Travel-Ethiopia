<?php
session_start();

// Database connection settings
$host = "localhost";
$db_user = "root"; 
$db_pass = "";     
$db_name = "travel_db"; 

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// --- SIGN UP LOGIC ---
if (isset($_POST['fullname'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'customer';

    // Step 1: Validate uniqueness for BOTH username and email
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkQuery->bind_param("ss", $username, $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // ERROR: One of them is already taken
        echo "<script>alert('Error: Username or Email is already in use by another account!'); window.history.back();</script>";
    } else {
        // Step 2: Proceed with registration
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $username, $email, $password, $phone, $role);

        if ($stmt->execute()) {
            // SUCCESS: Lead back to index.php (where the login form is)
            echo "<script>alert('Registration successful! Please sign in now.'); window.location.href='index.php';</script>";
        } else {
            echo "Registration Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkQuery->close();
}

// --- LOGIN LOGIC ---
if (isset($_POST['username']) && !isset($_POST['fullname'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } elseif ($user['role'] == 'tour_guide') {
        header("Location: Tour_guide/sidebar.php"); // Path to guide folder
    } elseif ($user['role'] == 'customer') {
        header("Location: users/profile.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
 else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found!'); window.history.back();</script>";
    }
    $stmt->close();
}



$conn->close();
?>
