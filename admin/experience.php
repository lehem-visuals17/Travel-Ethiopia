<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
$result = $conn->query("SELECT * FROM experiences ORDER BY id DESC");
?>

<div class="mgmt-container">
    <div class="header-section">
        <h1>Experience Management</h1>
        <p>Manage travel experiences and activities</p>
    </div>

    <!-- Filters Row (simplified for layout) -->
    <div class="filter-bar">
        <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" placeholder="Search experiences...">
        </div>
        <button class="filter-btn"><i class="fa fa-tag"></i> Category</button>
        <button class="filter-btn"><i class="fa fa-chart-line"></i> Difficulty</button>
        <button class="filter-btn"><i class="fa fa-bolt"></i> Status</button>
        <div class="price-range"> $0 - $300 </div>
    </div>

    <div class="table-actions">
        <button class="add-btn"><i class="fa fa-plus"></i> Add Experience</button>
        <span class="count">10 total experiences</span>
    </div>

    <table class="exp-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th>IMAGE</th>
                <th>NAME</th>
                <th>CATEGORY</th>
                <th>AVAILABILITY</th>
                <th>DIFFICULTY</th>
                <th>STATUS</th>
                <th>PRICE</th>
                <th>FEATURED</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox"></td>
                <td><img src="uploads/<?php echo $row['image']; ?>" class="thumb"></td>
                <td>
                    <strong><?php echo $row['name']; ?></strong>
                    <?php if($row['is_featured']): ?><i class="fa fa-star featured-star"></i><?php endif; ?>
                </td>
                <td><span class="badge category"><?php echo $row['category']; ?></span></td>
                <td><i class="fa fa-cube"></i> <?php echo $row['capacity']; ?></td>
                <td><span class="badge diff-<?php echo strtolower($row['difficulty']); ?>"><?php echo $row['difficulty']; ?></span></td>
                <td><span class="badge status-active"><?php echo $row['status']; ?></span></td>
                <td class="price">$<?php echo number_format($row['price']); ?></td>
                <td>
                    <label class="switch">
                        <input type="checkbox" <?php echo $row['is_featured'] ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                </td>
                <td class="actions">
                    <i class="fa-regular fa-pen-to-square"></i>
                    <i class="fa-regular fa-eye"></i>
                    <i class="fa-regular fa-trash-can"></i>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
