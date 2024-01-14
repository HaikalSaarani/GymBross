<?php
session_start();

// Include your database connection file
include('../../database/dbconnect.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if pro_id is set in the URL
if (isset($_GET['pro_id'])) {
    // Sanitize the input to prevent SQL injection
    $pro_id = mysqli_real_escape_string($conn, $_GET['pro_id']);

    // Fetch product data from the database for the specified pro_id
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $pro_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the product exists
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Product Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/stylo.css" rel="stylesheet">
</head>

<body>

    <?php include('headeruser.php'); ?>

    <div class="container text-center">
        <h1>Product Detail</h1>
    </div>

    <div class="container">
            <img src="<?= $product['pro_img']; ?>" class="pro.img" alt="Product Image">
            
                <h2 class="title"><?= $product['pro_name']; ?></h2>
                <p class="text">Price: RM<?= number_format($product['pro_price'], 2); ?></p>
                <p class="text"><?= $product['pro_description']; ?></p>

                <a href="purchaseuser.php?pro_id=<?php echo $product['id']; ?>" class="btn btn-success">Purchase</a>


        </div>
    </div>
<br>
    <?php include('../../footer.php'); ?>

</body>

</html>
<?php
} // This closing brace corresponds to the opening brace after if (isset($_GET['pro_id']))
?>
