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


$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build Dynamic WHERE Clause
$where_clauses = [];
$params = [];
$types = "";

if (!empty($_GET['search_name'])) {
    $where_clauses[] = "(fullname LIKE ? OR email LIKE ?)";
    $search_val = "%" . $_GET['search_name'] . "%";
    $params[] = $search_val; $params[] = $search_val;
    $types .= "ss";
}
if (!empty($_GET['search_role'])) {
    $where_clauses[] = "role = ?";
    $params[] = $_GET['search_role'];
    $types .= "s";
}
if (!empty($_GET['search_status'])) {
    $where_clauses[] = "status = ?";
    $params[] = $_GET['search_status'];
    $types .= "s";
}
if (!empty($_GET['search_date'])) {
    $where_clauses[] = "DATE(created_at) = ?";
    $params[] = $_GET['search_date'];
    $types .= "s";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count Total for Pagination
$count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM users $where_sql");
if (!empty($types)) $count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$total_users = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit);

$filters = $_GET;
unset($filters['page']); // Remove old page so we can add the new one
$query_string = http_build_query($filters);

// Main Query with Pagination
$query = "SELECT *, 
          (SELECT COUNT(*) FROM bookings WHERE user_id = users.id) as bookings_count,
          (SELECT SUM(amount) FROM payments WHERE user_id = users.id) as total_spent 
          FROM users $where_sql 
          ORDER BY created_at DESC LIMIT ?, ?";

$stmt = $conn->prepare($query);
$params[] = $offset; $params[] = $limit;
$types .= "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>

    <link rel="stylesheet" href="users.css">
    <link rel="stylesheet" href="style.css">
    
    
    <!-- 1. DataTables CSS (Version 2.3.8) -->
   <!-- DataTables CSS -->
<!-- DataTables 2.3.8 CSS -->
<link rel="stylesheet" href="https://datatables.net">

<style>
    /* Custom styling to match your badges with DataTables */
    .table-card { padding: 20px; background: #fff; border-radius: 8px; }
    .role-badge, .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.85em; }
    .role-admin { background: #ffebee; color: #c62828; }
    .role-customer { background: #e3f2fd; color: #1565c0; }
    .role-tour_guide { background: #f0f8e8; color: #2e7d32; }
    /* DataTables search box styling fix */
    .dt-search input { border: 1px solid #ccc !important; border-radius: 4px; padding: 5px; }
</style>

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
           <form method="GET" class="filter-form">
    <div class="filter-group">
        <label>Name or Email</label>
        <input type="text" name="search_name" placeholder="Search keyword..." value="<?= htmlspecialchars($_GET['search_name'] ?? '') ?>">
    </div>
    
    <div class="filter-group">
        <label>Role</label>
        <select name="search_role">
            <option value="">All Roles</option>
            <option value="admin" <?= (($_GET['search_role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="customer" <?= (($_GET['search_role'] ?? '') == 'customer') ? 'selected' : '' ?>>Customer</option>
            <option value="tour_guide" <?= (($_GET['search_role'] ?? '') == 'tour_guide') ? 'selected' : '' ?>>Tour Guide</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Status</label>
        <select name="search_status">
            <option value="">All Status</option>
            <option value="active" <?= (($_GET['search_status'] ?? '') == 'active') ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= (($_GET['search_status'] ?? '') == 'inactive') ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div class="filter-group">
        <label>Date Joined</label>
        <input type="date" name="search_date" value="<?= htmlspecialchars($_GET['search_date'] ?? '') ?>">
    </div>
    
    <button type="submit" class="btn-search">Filter</button>
    <a href="?" class="btn-clear">Clear</a>
</form>


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

    <div class="pagination-container">
    
    <!-- Previous Button -->
    <?php if($page > 1): ?>
        <a href="?<?php echo $query_string; ?>&page=<?php echo $page - 1; ?>" class="pagination-link">&laquo; Previous</a>
    <?php else: ?>
        <span class="pagination-disabled">&laquo; Previous</span>
    <?php endif; ?>

    <!-- Page Info -->
    <span class="pagination-info">Page <strong><?php echo $page; ?></strong> of <?php echo $total_pages; ?></span>

    <!-- Next Button -->
    <?php if($page < $total_pages): ?>
        <a href="?<?php echo $query_string; ?>&page=<?php echo $page + 1; ?>" class="pagination-link">Next &raquo;</a>
    <?php else: ?>
        <span class="pagination-disabled">Next &raquo;</span>
    <?php endif; ?>

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
            
            <div class="form-group"><label>Full Name</label><input type="text" name="fullname" id="fullname" required></div>
            <div class="form-group"><label>Username</label><input type="text" name="username" id="username" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" id="email" required></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" id="phone" required></div>
            
            <div class="form-group password-group" id="passwordGroup">
                <label>Password</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role">
        <option value="customer">Customer</option>
        <option value="admin">Admin</option>
        <option value="tour_guide">Tour Guide</option> <!-- Corrected value -->
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

<script>


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
    document.getElementById('role').value = "user.role";
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

<!-- 1. Load jQuery First -->
<script src="https://jquery.com"></script>

<!-- 2. Load DataTables JS -->
<script src="https://datatables.net"></script>

<!-- 3. Initialize with 5 rows per page -->
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#usersTable')) {
        $('#usersTable').DataTable().destroy();
    }

    $('#usersTable').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "order": [[ 8, "desc" ]], // Sort by 'Created' column
        "columnDefs": [
            { "orderable": false, "targets": 9 } // Disable sorting on 'Actions'
        ]
    });
});
</script>

</body>
</html>
