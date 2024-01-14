<!DOCTYPE html>
<html>

<head>
    <title>Simple Shop</title>
    <!-- Link to Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/stylo.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>GymBross Shop</title>
</head>

<body>
    
<?php include 'header.php'; ?>

    <div class="container text-center">
        <h1>Our Shop</h1>
        <p>Supplements for fitness can complement your diet and exercise routine</p>
        <p>to enhance performance, aid recovery, and support overall health.</p>
    </div>

    <div class="container">
        <div class="row">
            <?php
            // Include your database connection file
            include('database/dbconnect.php');

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
                echo '<a href="viewProduct.php?pro_id=' . $product['id'] . '" class="btn btn-secondary">View</a>';

                // Check if the user is logged in
                if (isLoggedIn()) {
                    // Purchase button
                    echo '<form action="purchaseProduct.php" method="post" class="d-inline">';
                    echo '<input type="hidden" name="pro_id" value="' . $product['id'] . '">';
                    echo '<input type="submit" class="btn btn-primary" value="Purchase">';
                    echo '</form>';
                } else {
                    // Display a message or redirect to the login page
                    echo '<p>Please <a href="login.php">log in</a> or <a href="registeruser.php">sign up</a> to make a purchase.</p>';
                }

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>
