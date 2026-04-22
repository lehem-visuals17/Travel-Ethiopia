<?php
// Database connection
$conn = new mysqli("localhost", "root","","travel_db");
$pageTitle = "Users";
$query = "
SELECT 
    u.*,
    COUNT(b.id) AS bookings_count,
    COALESCE(SUM(b.total_price),0) AS total_spent
FROM users u
LEFT JOIN bookings b ON u.id = b.user_id
GROUP BY u.id
ORDER BY u.id DESC
";

$result = $conn->query($query);
$user_count = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Connect your CSS file here -->
    <link rel="stylesheet" href="users.css">
    <?php include 
    
    "layout.php"; ?>

    <!-- If you are using Font Awesome, it goes here too -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
   

<div class="user-management-container">
    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p>Manage all users and their permissions</p>
        </div>
        <!-- The Add User Button -->
        <button class="btn-add-user" onclick="openAddModal()">
    <i class="fa-solid fa-user-plus"></i> Add User
</button>
    </div>

    <div class="search-section">
        <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search users by name or email...">
        </div>
    </div>

    <div class="table-card">
    <h3>All Users (<?php echo $user_count; ?>)</h3>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Bookings</th>
                    <th>Total Spent</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>

                    <td>
                        <span class="role-badge <?php echo $row['role']; ?>">
                            <?php echo ucfirst($row['role']); ?>
                        </span>
                    </td>

                    <td><?php echo $row['bookings_count']; ?></td>

                    <td>$<?php echo number_format($row['total_spent'], 2); ?></td>

                    <td><?php echo date('m/d/Y', strtotime($row['created_at'])); ?></td>

                    <td class="action-buttons">
                        <button class="btn-edit" onclick='openEditModal(
                            <?php echo $row["id"]; ?>,
                            "<?php echo addslashes($row["fullname"]); ?>",
                            "<?php echo addslashes($row["username"]); ?>",
                            "<?php echo addslashes($row["email"]); ?>",
                            "<?php echo addslashes($row["phone"]); ?>",
                            "<?php echo $row["role"]; ?>"
                        )'>
                            Edit
                        </button>

                        <a class="btn-delete"
                           href="delete_user.php?id=<?php echo $row['id']; ?>"
                           onclick="return confirm('Delete this user?')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
</div>
</div>

<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>

        <h2 id="modalTitle">Add User</h2>

        <form action="save_user.php" method="POST">
            <input type="hidden" name="id" id="user_id">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" id="fullname" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div class="form-group password-group">
                <label>Password</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-create">Save User</button>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById("modalTitle").innerText = "Add User";
    document.getElementById("user_id").value = "";
    document.getElementById("fullname").value = "";
    document.getElementById("email").value = "";
    document.getElementById("phone").value = "";
    document.getElementById("password").value = "";
    document.getElementById("role").value = "customer";

    document.querySelector(".password-group").style.display = "block";
    document.getElementById("userModal").style.display = "block";
}

function openEditModal(id, fullname, username, email, phone, role) {
    document.getElementById("modalTitle").innerText = "Edit User";
    document.getElementById("user_id").value = id;
    document.getElementById("fullname").value = fullname;
    document.getElementById("username").value = username;
    document.getElementById("email").value = email;
    document.getElementById("phone").value = phone;
    document.getElementById("role").value = role;

    document.querySelector(".password-group").style.display = "none";
    document.getElementById("userModal").style.display = "block";
}

function closeModal() {
    document.getElementById("userModal").style.display = "none";
}

window.onclick = function(e) {
    if (e.target == document.getElementById("userModal")) {
        closeModal();
    }
}
</script>

</body>
</html>