<?php
session_start();
include '../../database/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
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

// Fetch total user count
$userCountSql = "SELECT COUNT(u.id) as total_users FROM users u";
$userCountResult = mysqli_query($conn, $userCountSql) or die(mysqli_error($conn));
$userCountRow = mysqli_fetch_assoc($userCountResult);

// Fetch total purchase count
$purchaseCountSql = "SELECT COUNT(pc.id) as total_purchases FROM purchases pc";
$purchaseCountResult = mysqli_query($conn, $purchaseCountSql) or die(mysqli_error($conn));
$purchaseCountRow = mysqli_fetch_assoc($purchaseCountResult);

// Fetch total product count
$productCountSql = "SELECT COUNT(p.id) as total_products FROM products p";
$productCountResult = mysqli_query($conn, $productCountSql) or die(mysqli_error($conn));
$productCountRow = mysqli_fetch_assoc($productCountResult);
// Fetch the count of users for each membership type
$membershipCountSql = "SELECT mem_type, COUNT(u.id) as total_users FROM users u
                      INNER JOIN membership m ON u.mem_id = m.id
                      GROUP BY mem_type";
$membershipCountResult = mysqli_query($conn, $membershipCountSql) or die(mysqli_error($conn));
$membershipCounts = [];

while ($row = mysqli_fetch_assoc($membershipCountResult)) {
    $membershipCounts[$row['mem_type']] = $row['total_users'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php include('topbar.php'); ?>
    <br><br>


    <div class="sidebar">
        <a href="indexadmin.php">Home</a>
        <a href="viewUsers.php">Users</a>
        <a href="viewProducts.php">Products</a>
        <a href="viewPurchases.php">Purchases</a>
        <a href="viewMembership.php">Memberships</a>
    </div>
    <div class="container-fluid">
    
    <div class="row">
        
        <main role="main" class=" ml-sm-5 col-lg-10 px-4">
        
        <div class="container mt-4"><h2 >Admin Dashboard</h2>
    
    <div class="row justify-content-center">

        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Users:</h5>
                    <p class="card-text"><?= $userCountRow['total_users'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Purchases:</h5>
                    <p class="card-text"><?= $purchaseCountRow['total_purchases'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Products:</h5>
                    <p class="card-text"><?= $productCountRow['total_products'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-4">
            <div class="card bg-warning text-white shadow">
                <div class="card-body text-white">
                    <h5 class="card-title text-center">Users by Membership Type</h5>
                    <!-- Add a canvas element for the chart -->
                    <canvas id="membershipChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>
        </main>
    </div>
</div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include any custom scripts here -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const links = document.querySelectorAll(".sidebar-link");

            links.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const currentActive = document.querySelector('.sidebar a.active');
                    if (currentActive) {
                        currentActive.classList.remove('active');
                    }
                    this.classList.add('active');
                });
            });
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the canvas element
        var ctx = document.getElementById('membershipChart').getContext('2d');

        // Create the chart
        var membershipChart = new Chart(ctx, {
            type: 'bar', // Bar chart type
            data: {
                labels: <?php echo json_encode(array_keys($membershipCounts)); ?>, // Membership types as labels
                datasets: [{
                    label: 'User Count',
                    data: <?php echo json_encode(array_values($membershipCounts)); ?>, // User counts as data
                    backgroundColor: 'rgb(129, 133, 137)', // Bar color
                    borderColor: 'rgba(0, 0, 0)', // Border color
                    borderWidth: 1 // Border width
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>

</html>
