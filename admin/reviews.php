<?php
include "../db.php";
$pageTitle = "Reviews";

/* Handle delete */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM reviews WHERE id=$id");
    header("Location: reviews.php");
    exit();
}

/* Fetch reviews */
$reviews = $conn->query("
    SELECT 
        r.*,
        u.fullname,
        u.email,
        d.name AS destination_name,
        p.title AS package_name,
        g.fullname AS guide_name
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN destinations d ON r.destination_id = d.id
    LEFT JOIN packages p ON r.package_id = p.id
    LEFT JOIN users g ON r.guide_id = g.id  -- Assumes guides are also in 'users' table
    ORDER BY r.created_at DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reviews</title>
    <link rel="stylesheet" href="review.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include "layout.php"; ?>

<div class="reviews-container">
    <h1>Reviews & Ratings</h1>
    <p>Manage customer reviews and feedback</p>

    <?php if($reviews && $reviews->num_rows > 0): ?>
        <?php while($row = $reviews->fetch_assoc()): ?>

            <?php
                $firstLetter = strtoupper(substr($row['fullname'],0,1));

                if(!empty($row['destination_name'])){
                    $review_item = $row['destination_name'];
                }elseif(!empty($row['package_name'])){
                    $review_item = $row['package_name'];
                }else{
                    $review_item = "General Review";
                }
            ?>

            <div class="review-card">

                <div class="review-top">
                    <div class="review-user">
                        <div class="avatar"><?php echo $firstLetter; ?></div>
                        <div>
                            <h3><?php echo htmlspecialchars($row['fullname']); ?></h3>
                            <small><?php echo htmlspecialchars($review_item); ?></small>
                        </div>
                    </div>

                    <div class="review-actions">
                        <a href="#" class="btn-reply">
                            <i class="fa-regular fa-comment"></i> Respond
                        </a>

                        <a href="reviews.php?delete=<?php echo $row['id']; ?>" class="btn-delete"
                           onclick="return confirm('Delete this review?')">
                            <i class="fa-regular fa-trash-can"></i> Delete
                        </a>
                    </div>
                </div>

                <div class="stars">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <?php if($i <= $row['rating']): ?>
                            <i class="fa-solid fa-star filled"></i>
                        <?php else: ?>
                            <i class="fa-regular fa-star empty"></i>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <span class="approved">approved</span>
                </div>

                <div class="comment">
                    <?php echo nl2br(htmlspecialchars($row['comment'])); ?>
                </div>

                <div class="review-date">
                    <?php echo date("n/j/Y", strtotime($row['created_at'])); ?>
                </div>

            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-review">No reviews found.</div>
    <?php endif; ?>

</div>

</body>
</html>