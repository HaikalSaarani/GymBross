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

// Fetch user data from the database
$sql = "SELECT u.id as user_id, u.username, u.password, u.email, r.role_name, m.mem_type, u.address , u.points
        FROM users u
        INNER JOIN roles r ON u.role_id = r.id
        INNER JOIN membership m ON u.mem_id = m.id";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                <h2>User Details</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role Name</th>
                        <th>Member Type</th>
                        <th>Address</th>
                        <th>Points</th>
                        <th>Action</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the fetched data and display it in the table rows
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>".$row['password']."</td>";
                        echo "<td>" . $row['role_name'] . "</td>";
                        echo "<td>" . $row['mem_type'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['points'] . "</td>";
                        echo "<td>";
                        echo "<a href='edit_user.php?id=" . $row['user_id'] . "' class='btn btn-primary btn-sm'>Edit</a>&nbsp;";
                        echo "<button onclick='confirmDelete(" . $row['user_id'] . ")' class='btn btn-danger btn-sm'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="text-right mb-3">
    <a href="add_user.php" class="btn btn-success">Add New User</a>
</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
<!-- At the end of the HTML body -->
<script>
    function togglePassword(element) {
        const passwordField = element.previousElementSibling;
        if (passwordField.classList.contains('visually-hidden')) {
            passwordField.classList.remove('visually-hidden');
            element.classList.remove('fa-eye');
            element.classList.add('fa-eye-slash');
        } else {
            passwordField.classList.add('visually-hidden');
            element.classList.remove('fa-eye-slash');
            element.classList.add('fa-eye');
        }
    }
</script>
<script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                // Redirect to the delete_user.php page with the user ID for deletion
                window.location.href = 'delete_user.php?id=' + userId;
            } else {
                // Do nothing or perform any other action upon cancellation
                return false;
            }
        }
    </script>
</body>

</html>
