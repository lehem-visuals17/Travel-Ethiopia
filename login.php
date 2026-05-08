<?php
session_start();

// Database connection settings
$host = "localhost";
$db_user = "root"; 
$db_pass = "";     
$db_name = "travel_db"; 

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

/*if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/

// --- SIGN UP LOGIC ---
if (isset($_POST['fullname'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'customer';

    $checkQuery = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $checkQuery->bind_param("ss", $username, $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: Username or Email is already in use!'); window.history.back();</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $username, $email, $password, $phone, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please sign in.'); window.location.href='index.php';</script>";
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
            // Set Session Data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // --- DEBUGGING BLOCK ---
            $raw_role = $user['role']; 
            $clean_role = strtolower(trim($raw_role));
            
            // IF THE LOGIN STILL FAILS: Remove the '//' from the line below and try to login again.
            // die("DEBUG: DB says role is: [" . $raw_role . "] | Cleaned to: [" . $clean_role . "]");
            // --- END DEBUGGING BLOCK ---

            $_SESSION['role'] = $clean_role;

            // Strict Redirection Logic
            if ($clean_role === 'admin') {
                header("Location: admin/dashboard.php");
                exit();
            } 
            elseif ($clean_role === 'tour_guide') {
                header("Location: Tour_guide/dashboard.php");
                exit();
            } 
            elseif ($clean_role === 'customer') {
                header("Location: users/profile.php");
                exit();
            } 
            else {
                header("Location: index.php?error=unknown_role&role_found=" . urlencode($clean_role));
                exit();
            }
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found with that username!'); window.history.back();</script>";
    }
    $stmt->close();
}

$conn->close();
?>
