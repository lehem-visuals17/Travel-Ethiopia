<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Payments";

$revenue_query = "SELECT SUM(amount) AS total FROM payments WHERE status = 'completed'";
$revenue_res = $conn->query($revenue_query);
$total_revenue = number_format($revenue_res->fetch_assoc()['total'] ?? 0);

$pending_query = "SELECT SUM(amount) AS total FROM payments WHERE status = 'pending'";
$pending_res = $conn->query($pending_query);
$pending_payments = number_format($pending_res->fetch_assoc()['total'] ?? 0);

$count_query = "SELECT COUNT(id) AS total FROM payments";
$count_res = $conn->query($count_query);
$total_transactions = $count_res->fetch_assoc()['total'] ?? 0;

// 2. Fetch All Payments (Joining with a 'users' table if you have one for names)
$sql = "SELECT p.*, u.username as customer_name 
        FROM payments p 
        LEFT JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php include "layout.php"; ?>
     <link rel="stylesheet" href="paymnet.css">
</head>
<body>
    <div class="main-content">
    <div class="container">
        <header>
            <h1>Payment Management</h1>
            <p>Track and manage all payments</p>
        </header>

        <div class="stats-grid">
            <div class="card card-green">
                <div class="card-info"><span>Total Revenue</span><h3>$<?php echo $total_revenue; ?></h3></div>
                <div class="card-icon">$</div>
            </div>
            <div class="card card-orange">
                <div class="card-info"><span>Pending Payments</span><h3>$<?php echo $pending_payments; ?></h3></div>
                <div class="card-icon">🔄</div>
            </div>
            <div class="card card-orange-light">
                <div class="card-info"><span>Total Transactions</span><h3><?php echo $total_transactions; ?></h3></div>
                <div class="card-icon">$</div>
            </div>
        </div>

        <div class="table-container">
            <h3>All Payments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th><th>Booking ID</th><th>Customer</th>
                        <th>Amount</th><th>Method</th><th>Type</th>
                        <th>Date</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>#<?php echo $row['booking_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name'] ?? 'Unknown'); ?></td>
                            <td class="amt-text">$<?php echo number_format($row['amount'], 2); ?></td>
                            <td><span class="badge badge-purple"><?php echo $row['method']; ?></span></td>
                            <td><?php echo ucfirst($row['payment_type']); ?></td>
                            <td><?php echo date('n/j/Y', strtotime($row['created_at'])); ?></td>
                            <td><span class="status-pill <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <form action="update_status.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button name="action" value="completed" class="btn btn-confirm">✓ Confirm</button>
                                        <button name="action" value="rejected" class="btn btn-reject">ⓧ Reject</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9">No payments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
                    </div>
</body>
</html>
