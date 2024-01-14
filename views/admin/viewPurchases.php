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

// Fetch purchase data from the database
$sql = "SELECT pc.id as pur_id, p.pro_name, pc.pur_quantity, pc.pur_date, pc.pur_total, u.username, u.address
        FROM purchases pc
        INNER JOIN users u ON pc.user_id = u.id
        INNER JOIN products p ON pc.pro_id = p.id";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Purchases</title>
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
                <h2>Purchase Details</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Purchase ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Purchase Date</th>
                                <th>Total</th>
                                <th>Username</th>
                                <th>Address</th>
                                <th>Action</th> <!-- New column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the fetched purchase data and display it in the table rows
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['pur_id'] . "</td>";
                                echo "<td>" . $row['pro_name'] . "</td>";
                                echo "<td>" . $row['pur_quantity'] . "</td>";
                                echo "<td>" . $row['pur_date'] . "</td>";
                                echo "<td>" . $row['pur_total'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['address'] . "</td>";
                                echo "<td>";
                                echo "<a href='edit_purchase.php?id=" . $row['pur_id'] . "' class='btn btn-primary btn-sm'>Edit</a>&nbsp;";
                                echo "<a href='delete_purchase.php?id=" . $row['pur_id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Add New Purchase button -->
                    <div class="text-right mb-3">
                        <a href="add_purchase.php" class="btn btn-success">Add New Purchase</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include any custom scripts here -->
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this purchase detail?");
        }
    </script>
</body>

</html>
