<?php
session_start();
include '../../database/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details, including role, from the database
$username = $_SESSION['username'];
$userSql = "SELECT role_id FROM users WHERE username = '$username'";
$userResult = mysqli_query($conn, $userSql) or die(mysqli_error($conn));
$userRow = mysqli_fetch_assoc($userResult);

// Check user role and restrict access if necessary
$allowedRoleID = 1; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['role_id'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Fetch product data from the database
$sql = "SELECT p.id as pro_id, p.pro_img, p.pro_name, p.pro_price,  p.pro_description 
FROM products p";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
<?php include('topbar.php'); ?>

<div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="sidebar">
                    <a href="indexadmin.php">Home</a>
                    <a href="viewUsers.php">Users</a>
                    <a href="viewProducts.php">Products</a>
                    <a href="viewPurchases.php">Purchases</a>
                    <a href="viewMembership.php">Memberships</a>
                </div>
            </div>
            <br>
            <div class="container mt-4">
                <h2>Product Details</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th> 
                        <th>Description</th>
                        <th>Action</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the fetched product data and display it in the table rows
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['pro_id'] . "</td>";
                        echo "<td><img src='" . $row['pro_img'] . "' width='90' height='100'></td>";
                        echo "<td>" . $row['pro_name'] . "</td>";
                        echo "<td>" . $row['pro_price'] . "</td>";
                        echo "<td>" . $row['pro_description'] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_product.php?id=" . $row['pro_id'] . "' class='btn btn-primary btn-sm'>Edit</a>&nbsp;<br><br>";
                        echo "<a href='delete_product.php?id=" . $row['pro_id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    
            
                    }
                    
                    ?>
                </tbody>
            </table>
            
        </div>
       
        <!-- Add New Product button -->
        <div class="text-right">
            <a href="add_product.php" class="btn btn-success">Add New Product</a>
        </div>
    
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include any custom scripts here -->
    <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this product?");
            }
        </script>
</body>

</html>
