<?php
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
<html>
<head>
    <title>Users</title>

    <link rel="stylesheet" href="users.css">
    <link rel="stylesheet" href="style.css">
    
    
    <!-- 1. DataTables CSS (Version 2.3.8) -->
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.8/css/dataTables.dataTables.min.css">

    <?php include "layout.php"; ?>
    
    <!-- Inline styles to fix standard DataTable clashes with custom layouts -->
    <style>
        .dt-container { padding: 15px 0; }
        table.dataTable th { background: #f8f9fa; }
    </style>
</head>

<body>

<div class="user-management-container">

    <div class="page-header">
        <div>
            <h1>User Management</h1>
            <p>Manage users and permissions</p>
        </div>

        <button class="btn-add-user" onclick="openAddModal()">
            <i class="fa-solid fa-user-plus"></i> Add User
        </button>
    </div>

    <div class="table-card">

        <h3>All Users (<?php echo $user_count; ?>)</h3>

        <div class="table-scroll">
            <!-- 2. Added id="usersTable" for DataTables initialization -->
            <table id="usersTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
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
                        <td><?php echo htmlspecialchars($row['phone'] ?? ''); ?></td>

                        <td>
                            <span class="role-badge role-<?php echo strtolower($row['role']); ?>">
                                <?php echo ucfirst($row['role']); ?>
                            </span>
                        </td>

                        <td>
                            <span class="status-badge status-<?php echo strtolower($row['status'] ?? 'active'); ?>">
                                <?php echo ucfirst($row['status'] ?? 'active'); ?>
                            </span>
                        </td>

                        <td><?php echo $row['bookings_count']; ?></td>
                        <td>$<?php echo number_format($row['total_spent'], 2); ?></td>
                        <td><?php echo date('m/d/Y', strtotime($row['created_at'])); ?></td>

                        <td class="action-icons">
                            <!-- EDIT -->
                            <a href="#" class="btn-edit" onclick="openEditModal('<?php echo $row['id']; ?>', '<?php echo htmlspecialchars($row['fullname'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['username'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['phone'] ?? '', ENT_QUOTES); ?>', '<?php echo $row['role']; ?>', '<?php echo $row['status'] ?? 'active'; ?>')">
                                <i class="fa fa-pen"></i>
                            </a>

                            <!-- RESET PASSWORD -->
                            <a href="#" class="btn-password" onclick="openPasswordModal(<?php echo $row['id']; ?>)">
                                <i class="fa fa-key"></i>
                            </a>

                            <!-- CHANGE ROLE -->
                            <a href="change_role.php?id=<?php echo $row['id']; ?>" class="btn-role">
                                <i class="fa fa-user-shield"></i>
                            </a>

                            <!-- TOGGLE STATUS -->
                            <a href="toggle_user.php?id=<?php echo $row['id']; ?>" class="btn-status">
                                <i class="fa fa-user-lock"></i>
                            </a>

                            <!-- DELETE -->
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this user?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ADD/EDIT MODAL -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Add User</h2>
        <form action="save_user.php" method="POST">
            <input type="hidden" name="id" id="user_id">
            <div class="form-group"><label>Full Name</label><input type="text" name="fullname" id="fullname"></div>
            <div class="form-group"><label>Username</label><input type="text" name="username" id="username"></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" id="email"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" id="phone"></div>
            <div class="form-group password-group"><label>Password</label><input type="password" name="password" id="password"></div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group" id="statusGroup" style="display:none;">
                <label>Status</label>
                <select name="status" id="status">
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <button type="submit" class="btn-create">Save User</button>
        </form>
    </div>
</div>

<!-- CHANGE PASSWORD MODAL -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePasswordModal()">&times;</span>
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST">
            <input type="hidden" name="user_id" id="pass_user_id">
            <div class="form-group"><label>Old Password</label><input type="password" name="old_password" required></div>
            <div class="form-group"><label>New Password</label><input type="password" name="new_password" required></div>
            <div class="form-group"><label>Confirm New Password</label><input type="password" name="confirm_password" required></div>
            <button type="submit" class="btn-create">Update Password</button>
        </form>
    </div>
</div>

<!-- 3. jQuery and DataTables JS (Version 2.3.8) -->
<script src="https://jquery.com"></script>
<!-- 3. jQuery and DataTables JS (Corrected Links) -->

<script src="https://datatables.net"></script>


<script>
$(document).ready(function() {
    // 4. Initialize DataTables
    $('#usersTable').DataTable({
        "order": [[ 8, "desc" ]], // Sort by 'Created' column descending by default
        "pageLength": 5,
        "columnDefs": [
            { "orderable": false, "targets": 9 } // Disable sorting on the "Actions" column
        ]
    });
});

// --- Your Existing Modal JS Functions ---
function openPasswordModal(id){
    document.getElementById("pass_user_id").value = id;
    document.getElementById("passwordModal").style.display = "block";
}

function closePasswordModal(){
    document.getElementById("passwordModal").style.display = "none";
}

function openAddModal(){
    document.getElementById("modalTitle").innerText = "Add User";
    document.getElementById("user_id").value = "";
    document.getElementById("fullname").value = "";
    document.getElementById("username").value = "";
    document.getElementById("email").value = "";
    document.getElementById("phone").value = "";
    document.getElementById("password").value = "";
    document.getElementById("role").value = "customer";
    document.getElementById("status").value = "active";
    document.querySelector(".password-group").style.display = "block";
    document.getElementById("statusGroup").style.display = "none";
    document.getElementById("userModal").style.display = "block";
}

function openEditModal(id, name, uname, userEmail, userPhone, userRole, userStatus){
    document.getElementById("modalTitle").innerText = "Edit User";
    document.getElementById("user_id").value = id;
    document.getElementById("fullname").value = name;
    document.getElementById("username").value = uname;
    document.getElementById("email").value = userEmail;
    document.getElementById("phone").value = userPhone;
    document.getElementById("role").value = userRole;
    document.getElementById("status").value = userStatus;
    document.querySelector(".password-group").style.display = "none";
    document.getElementById("statusGroup").style.display = "block";
    document.getElementById("userModal").style.display = "block";
}

function closeModal(){
    document.getElementById("userModal").style.display = "none";
}
</script>

</body>
</html>
