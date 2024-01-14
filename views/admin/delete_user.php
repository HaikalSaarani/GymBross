<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the user ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewUsers.php");
    exit();
}

// Fetch the user ID from the URL parameter
$user_id = $_GET['id'];

// Delete the user from the database
$delete_sql = "DELETE FROM users WHERE id = $user_id";
$delete_result = mysqli_query($conn, $delete_sql);

if ($delete_result) {
    // Redirect to viewUsers.php upon successful deletion
    header("Location: viewUsers.php");
    exit();
} else {
    echo "Error deleting user: " . mysqli_error($conn);
}
?>
