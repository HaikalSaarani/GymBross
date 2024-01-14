<?php
// Assuming you have a session established after login
session_start();
include('../../database/dbconnect.php');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Retrieve user information from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/stylo.css">
    <link rel="stylesheet" href="../../css/style copy.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>GymBross Shop</title>
</head>
<body>
    <header>
    <div class="logo">
            <img src="../../img/brosslogo.png" alt="GymBross Logo" width="200" height="200">
            
        </div>
        <nav>
            <a href="indexuser.php">Home</a>
            <a href="shopuser.php">Shop</a>
            <a href="membershipuser.php">Membership</a>
            <a href="contactuser.php">Contact</a>
            
            <a href="profileuser.php">Profile</a>
            <a href="../../logout.php">Logout</a>
            
        </nav>
    </header>



    <div class="container text-center">
        <h1>Our Shop</h1>
        <p>Supplements for fitness can complement your diet and exercise routine</p>
        <p>to enhance performance, aid recovery, and support overall health.</p>
    </div>

    <div class="container">
        <div class="row">
            <?php
            // Include your database connection file
            include('../../database/dbconnect.php');

            // Function to check if the user is logged in
            function isLoggedIn()
            {
                return isset($_SESSION['user_id']);
            }

            // Fetch product data from the database
            $sql = "SELECT * FROM products";
            $result = mysqli_query($conn, $sql);

            
            while ($product = mysqli_fetch_assoc($result)) {
                echo '<div class="col-md-4 text-center">';
                echo '<div class="card mb-4">';
                echo '<img src="' . $product['pro_img'] . '" class="card-img-top" alt="Product Image">';
                echo '<div class="card-body">';
                echo '<h2 class="card-title">' . $product['pro_name'] . '</h2>';
                echo '<p class="card-text">Price: RM' . number_format($product['pro_price'], 2) . '</p>';

                // View button
                echo '<a href="viewProduct.php?pro_id=' . $product['id'] . '" class="btn btn-secondary">View</a> &nbsp;';
                echo '<a href="purchaseuser.php?pro_id='. $product['id'] . '" class="btn btn-success">Purchase</a>';

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <?php include('../../footer.php'); ?>
</body>

</html>