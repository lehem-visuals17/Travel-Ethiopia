<?php
session_start();
include "../db.php";

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE users 
        SET fullname=?, email=?, phone=?, role=?, status=? 
        WHERE id=?
    ");
    $stmt->bind_param("sssssi", $fullname, $email, $phone, $role, $status, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-card">
    <h2>Edit User</h2>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo $user['fullname']; ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="admin" <?php if($user['role']=="admin") echo "selected"; ?>>Admin</option>
                <option value="customer" <?php if($user['role']=="customer") echo "selected"; ?>>Customer</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="active" <?php if($user['status']=="active") echo "selected"; ?>>Active</option>
                <option value="suspended" <?php if($user['status']=="suspended") echo "selected"; ?>>Suspended</option>
            </select>
        </div>

        <button type="submit" name="update" class="btn-create">Update User</button>
    </form>
</div>

</body>
</html>