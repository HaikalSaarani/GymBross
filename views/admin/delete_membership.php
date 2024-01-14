<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the membership ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewMembership.php");
    exit();
}

// Fetch the membership ID from the URL parameter
$membership_id = $_GET['id'];

// Delete the membership from the database
$delete_sql = "DELETE FROM membership WHERE id = $membership_id";
$delete_result = mysqli_query($conn, $delete_sql);

if ($delete_result) {
    // Redirect to viewMembership.php upon successful deletion
    header("Location: viewMembership.php");
    exit();
} else {
    echo "Error deleting membership: " . mysqli_error($conn);
}
?>
