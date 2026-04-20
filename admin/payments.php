<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$pageTitle = "Payments";

/* Stats Logic */
$total_revenue = $conn->query("SELECT SUM(amount) total FROM payments WHERE status='paid'")->fetch_assoc()['total'] ?? 0;
$pending_payments = $conn->query("SELECT SUM(amount) total FROM payments WHERE status='pending'")->fetch_assoc()['total'] ?? 0;
$total_transactions = $conn->query("SELECT COUNT(*) total FROM payments")->fetch_assoc()['total'];

/* Action Handling */
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'confirm') ? 'paid' : 'failed';
    $stmt = $conn->prepare("UPDATE payments SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    header("Location: payments.php");
    exit();
}

$result = $conn->query("SELECT p.*, u.fullname FROM payments p LEFT JOIN users u ON p.user_id = u.id ORDER BY p.id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payments</title>
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php include "layout.php"; ?>
</head>
<body>

<div class="content payment-page">
    <div class="payment-header"> <!-- Fixed class name -->
        <h1>Payment Management</h1>
        <p>Track and manage all payments</p>
    </div>

    <div class="payment-stats"> <!-- Fixed class name -->
        <div class="payment-card">
            <div>
                <h4>Total Revenue</h4>
                <h2 style="color: #22a35a;">$<?php echo number_format($total_revenue); ?></h2>
            </div>
            <i class="fa-solid fa-dollar-sign icon" style="color: #22a35a; font-size: 24px;"></i>
        </div>

        <div class="payment-card">
            <div>
                <h4>Pending Payments</h4>
                <h2 style="color: #d48a00;">$<?php echo number_format($pending_payments); ?></h2>
            </div>
            <i class="fa-solid fa-rotate-right icon" style="color: #d48a00; font-size: 24px;"></i>
        </div>

        <div class="payment-card">
            <div>
                <h4>Total Transactions</h4>
                <h2 style="color: #d48a00;"><?php echo $total_transactions; ?></h2>
            </div>
            <i class="fa-solid fa-dollar-sign icon" style="color: #d48a00; font-size: 24px;"></i>
        </div>
    </div>

    <div class="payment-table-box"> <!-- Fixed class name -->
        <h3>All Payments</h3>
        <table class="payment-table"> <!-- Added class -->
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['fullname']; ?></td>
                    <td style="font-weight: bold; color: <?php echo ($row['status']=='paid') ? '#22a35a' : '#d48a00'; ?>">
                        $<?php echo number_format($row['amount']); ?>
                    </td>
                    <td><span class="method-badge" style="background:#e0d4ff; color:#6f42c1;"><?php echo $row['method']; ?></span></td>
                    <td><span class="type-badge" style="border: 1px solid #ddd;"><?php echo ucfirst($row['payment_type']); ?></span></td>
                    <td><?php echo date("n/j/Y", strtotime($row['created_at'])); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ($row['status']=='paid') ? "completed" : $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if($row['status']=="pending"): ?>
                            <a href="?action=confirm&id=<?php echo $row['id']; ?>" class="btn-action btn-confirm">
                                <i class="fa-regular fa-circle-check"></i> Confirm
                            </a>
                            <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn-action btn-reject">
                                <i class="fa-regular fa-circle-xmark"></i> Reject
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>