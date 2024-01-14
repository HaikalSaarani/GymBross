<?php
// Include database connection
include '../../database/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the mem_id from the form submission
    $mem_id = $_POST['mem_id'];

    // Assuming you have a user_id for the logged-in user, replace USER_ID_HERE with the actual user_id
    $user_id = 'id';

    // Update mem_id in the users table for the specific user
    $updateSql = "UPDATE users SET mem_id = $mem_id WHERE id = $user_id";
    mysqli_query($conn, $updateSql) or die(mysqli_error($conn));
}

// Close the database connection
mysqli_close($conn);

// Redirect back to the original page after updating mem_id
header("Location: profileuser.php");
exit();
?>
