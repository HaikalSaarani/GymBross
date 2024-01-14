<?php
// Start the session and include necessary files
session_start();
include '../../database/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Fetch membership data from the database
$sql = "SELECT id, mem_type, mem_price, mem_discount, mem_description FROM membership";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Memberships</title>
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
                <h2>Membership Details</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Membership ID</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Description</th>
                                <th>Action</th> <!-- New column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through the fetched membership data and display it in the table rows
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['mem_type'] . "</td>";
                                echo "<td>" . $row['mem_price'] . "</td>";
                                echo "<td>" . $row['mem_discount'] . "</td>";
                                echo "<td>" . $row['mem_description'] . "</td>";
                                echo "<td>";
                                echo "<a href='edit_membership.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>&nbsp;<br><br>";
                                echo "<a href='delete_membership.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add New Membership button -->
                <div class="text-right">
                    <a href="add_membership.php" class="btn btn-success">Add New Membership</a>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Include any custom scripts here -->
        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this membership?");
            }
        </script>
    </div>
</body>

</html>
