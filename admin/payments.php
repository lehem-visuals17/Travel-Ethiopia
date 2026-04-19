<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "travel_db");

$pageTitle = "Payments";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. Fetch Stats
$revenue = $conn->query("
    SELECT SUM(amount) AS total 
    FROM payments 
    WHERE status='paid'
")->fetch_assoc()['total'] ?? 0;

$pending_val = $conn->query("
    SELECT SUM(amount) AS total 
    FROM payments 
    WHERE status='pending'
")->fetch_assoc()['total'] ?? 0;

$trans_count = $conn->query("
    SELECT COUNT(*) AS total 
    FROM payments
")->fetch_assoc()['total'];

// 2. Handle Actions (Confirm/Reject) - SAFE VERSION
if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $new_status = 'paid';
    } else {
        $new_status = 'failed';
    }

    $stmt = $conn->prepare("UPDATE payments SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();

    header("Location: payment.php");
    exit();
}

// 3. Fetch Table Data
$sql = "SELECT p.*, u.fullname 
        FROM payments p 
        LEFT JOIN users u ON p.user_id = u.id 
        ORDER BY p.id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Management</title>
    <link rel="stylesheet" href="payment.css">
    <?php include "layout.php"; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="page-container">

    <header class="header">
        <h1>Payment Management</h1>
        <p>Track and manage all payments</p>
    </header>

    <!-- STATS -->
    <div class="stats-row">

        <div class="stat-card">
            <div class="stat-info">
                <span>Total Revenue</span>
                <h2 class="text-green">$<?php echo number_format($revenue); ?></h2>
            </div>
            <i class="fa-solid fa-dollar-sign ico-green"></i>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Pending Payments</span>
                <h2 class="text-orange">$<?php echo number_format($pending_val); ?></h2>
            </div>
            <i class="fa-solid fa-arrows-rotate ico-orange"></i>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span>Total Transactions</span>
                <h2 class="text-orange"><?php echo $trans_count; ?></h2>
            </div>
            <i class="fa-solid fa-receipt ico-orange"></i>
        </div>

    </div>

    <!-- TABLE -->
    <div class="content-box">
        <h3>All Payments</h3>

        <div class="table-wrapper">
            <table>

                <thead>
                    <tr>
                        <th>ID</th>
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

                    <?php
                        // STATUS FIX
                        $statusText = match($row['status']) {
                            'paid' => 'Completed',
                            'pending' => 'Pending',
                            'failed' => 'Failed',
                            default => 'Unknown'
                        };
                    ?>

                    <tr>

                        <td class="text-dim">#<?php echo $row['id']; ?></td>
                        <td>#<?php echo $row['booking_id']; ?></td>

                        <td class="text-bold">
                            <?php echo htmlspecialchars($row['fullname'] ?? 'Unknown User'); ?>
                        </td>

                        <td class="text-orange text-bold">
                            $<?php echo number_format($row['amount']); ?>
                        </td>

                        <td>
                            <span class="badge-meth-<?php echo $row['method']; ?>">
                                <?php echo ucfirst($row['method']); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge-outline">
                                <?php echo ucfirst($row['payment_type']); ?>
                            </span>
                        </td>

                        <td>
                            <?php echo date("n/j/Y", strtotime($row['created_at'])); ?>
                        </td>

                        <td>
                            <span class="status-pill pill-<?php echo $row['status']; ?>">
                                <?php echo $statusText; ?>
                            </span>
                        </td>

                        <td>
                            <?php if($row['status'] == 'pending'): ?>
                                <div class="btn-group">

                                    <a href="?action=confirm&id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-confirm">
                                        <i class="fa-regular fa-circle-check"></i> Confirm
                                    </a>

                                    <a href="?action=reject&id=<?php echo $row['id']; ?>" 
                                       class="btn-action btn-reject">
                                        <i class="fa-regular fa-circle-xmark"></i> Reject
                                    </a>

                                </div>
                            <?php else: ?>
                                
                            <?php endif; ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>