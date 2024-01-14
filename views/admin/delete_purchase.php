<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the purchase ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewPurchases.php");
    exit();
}

// Fetch the purchase ID from the URL parameter
$purchase_id = $_GET['id'];

// Delete the purchase from the database
$delete_sql = "DELETE FROM purchases WHERE id = $purchase_id";
$delete_result = mysqli_query($conn, $delete_sql);

if ($delete_result) {
    // Redirect to viewPurchases.php upon successful deletion
    header("Location: viewPurchases.php");
    exit();
} else {
    echo "Error deleting purchase: " . mysqli_error($conn);
}
?>
