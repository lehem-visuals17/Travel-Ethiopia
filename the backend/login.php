<?php
session_start();

// Database connection settings
$host = "localhost";
$db_user = "root"; // Default XAMPP user
$db_pass = "";     // Default XAMPP password
$db_name = "travel_db"; 

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- SIGN UP LOGIC ---
if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $username = $_POST['username'];
    // Securely hash the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'customer'; // Default role for new users

    // Step 1: Check if username or email already exists to avoid SQL errors
    $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkQuery->bind_param("ss", $username, $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already taken!'); window.history.back();</script>";
    } else {
        // Step 2: Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $username, $email, $password, $phone, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please sign in.'); window.location.href='index.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkQuery->close();
}

// --- LOGIN LOGIC ---
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Step 1: Retrieve the user based on the username
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Step 2: Verify the password against the stored hash
        if (password_verify($password, $user['password'])) {
            // Save user details to the session
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            // Step 3: Redirect to the welcome page
            header("Location: welcome.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>
