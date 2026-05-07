<?php

include 'db.php'; 
$dest_id = $_GET['destination_id'];

// Get destination name for the header
$res = mysqli_query($conn, "SELECT name FROM destinations WHERE id = $dest_id");
$dest = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="lalibela.css">
    </head>
<body>


<script>
function fetchAvailableGuides() {
    const date = document.getElementById('travel_date').value;
    const destId = <?php echo $dest_id; ?>;
    const dropdown = document.getElementById('guide_dropdown');

    if(!date) return;

    fetch(`get_available_guides.php?date=${date}&dest_id=${destId}`)
        .then(response => response.json())
        .then(data => {
            dropdown.innerHTML = '<option value="">-- Choose a Guide --</option>';
            if(data.length === 0) {
                dropdown.innerHTML = '<option value="">No guides available for this day</option>';
            } else {
                data.forEach(guide => {
                    dropdown.innerHTML += `<option value="${guide.id}">${guide.name} (${guide.experience_years} yrs exp)</option>`;
                });
            }
        });
}
</script>
</body></html>