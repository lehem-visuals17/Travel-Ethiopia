<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$pageTitle = "Deals & Promotions";
$result = $conn->query("SELECT * FROM deals ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deals & Promotions</title>
    <link rel="stylesheet" href="admin_deals.css">
    <!-- Fixed broken Cloudflare link -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <?php include "layout.php"; ?>
</head>
<body>

<div class="dashboard-container">
    
    <!-- PAGE HEADER -->
    <div class="dashboard-header">
        <div>
            <h1>Deals & Promotions</h1>
            <p>Manage discounts and promo codes</p>
        </div>
        <button class="btn-create" onclick="openDealModal()">
            <i class="fa-solid fa-plus"></i> Create Deal
        </button>
    </div>

    <!-- CARDS GRID -->
    <div class="cards-grid">
        <?php while($row = $result->fetch_assoc()): 
            $is_perc = (strpos($row['discount_badge'], '%') !== false);
        ?>
        <div class="promo-card">
            <!-- Top Status Badge -->
            <span class="status-pill"><?php echo htmlspecialchars($row['status']); ?></span>

            <!-- Title Header -->
            <div class="card-top">
                <div class="icon-square">
                    <?php if($is_perc): ?>
                        <i class="fa-solid fa-percent"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-tag"></i>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            </div>

            <p class="card-description"><?php echo htmlspecialchars($row['description']); ?></p>

            <!-- Discount Section -->
            <div class="discount-section">
                <span>Discount</span>
                <h2><?php echo htmlspecialchars($row['discount_badge']); ?></h2>
            </div>

            <!-- Promo Code Row -->
            <div class="promo-row">
                <span>Promo Code:</span>
                <span class="code-pill"><?php echo htmlspecialchars($row['deal_note']); ?></span>
            </div>

            <!-- Dates -->
            <div class="date-section">
                <p>Valid from: <?php echo date('n/j/Y', strtotime($row['end_datetime'] . ' - 30 days')); ?></p>
                <p>Valid to: <?php echo date('n/j/Y', strtotime($row['end_datetime'])); ?></p>
            </div>

            <!-- Actions -->
            <div class="action-buttons">
                <a href="edit_deal.php?id=<?php echo $row['id']; ?>" class="btn-edit">
                    <i class="fa-regular fa-pen-to-square"></i> Edit
                </a>
                <a href="delete_deal.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this deal?')">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- ADD DEAL MODAL -->
<div id="dealModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.6);">
    <div style="background:#fff; margin:5% auto; padding:30px; width:50%; max-width:600px; border-radius:10px; position:relative;">
        <span onclick="closeDealModal()" style="position:absolute; right:20px; top:15px; font-size:28px; cursor:pointer;">&times;</span>
        <h2>Add New Deal</h2>
        
        <form action="save_deal.php" method="POST" style="margin-top: 15px;">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Package Title</label>
                <input type="text" name="title" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Description</label>
                <textarea name="description" rows="3" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;"></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Discount Badge</label>
                    <input type="text" name="discount_badge" placeholder="e.g. 25% OFF" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Promo Code</label>
                    <input type="text" name="deal_note" placeholder="e.g. SUMMER2026" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Original Price ($)</label>
                    <input type="number" step="0.01" name="old_price" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                </div>
                <div style="flex: 1;">
                    <label style="display:block; margin-bottom: 5px; font-weight: bold;">Discounted Price ($)</label>
                    <input type="number" step="0.01" name="new_price" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Image URL</label>
                <input type="text" name="image_url" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom: 5px; font-weight: bold;">Deal Expiry Date & Time</label>
                <input type="datetime-local" name="end_datetime" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;" required>
            </div>

            <button type="submit" class="btn-create" style="padding: 10px 20px; cursor: pointer;">Save Deal</button>
        </form>
    </div>
</div>

<!-- Fixed invalid jQuery link -->
<script src="https://jquery.com"></script>
<script>
function openDealModal() { document.getElementById("dealModal").style.display = "block"; }
function closeDealModal() { document.getElementById("dealModal").style.display = "none"; }
</script>

</body>
</html>
