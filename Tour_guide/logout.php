<?php
session_start();
session_unset();
session_destroy();

// Redirect to your main login/index page
header("Location: ../index.php"); 
exit();
?>
<!-- Logout Item -->
<a href="logout.php" style="text-decoration: none;" onclick="return confirm('Are you sure you want to log out?')">
    <div class="settings-card logout-card">
        <div class="icon-box gray-bg"><i class="fa-solid fa-right-from-bracket"></i></div>
        <div class="card-info">
            <h3 class="text-dark">Log Out</h3>
            <p>Safely sign out of your account</p>
        </div>
        <i class="fa-solid fa-chevron-right arrow"></i>
    </div>
</a>
