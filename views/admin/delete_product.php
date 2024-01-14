<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the product ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewProducts.php");
    exit();
}

// Fetch the product ID from the URL parameter
$product_id = $_GET['id'];

// Delete the product from the database
$delete_sql = "DELETE FROM products WHERE id = $product_id";
$delete_result = mysqli_query($conn, $delete_sql);

if ($delete_result) {
    // Redirect to viewProducts.php upon successful deletion
    header("Location: viewProducts.php");
    exit();
} else {
    echo "Error deleting product: " . mysqli_error($conn);
}
?>
