<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $pro_id = $_POST['pro_id'];
    $user_id = $_POST['user_id'];
    $pur_quantity = $_POST['pur_quantity'];
    $pur_date = date('d-m-Y H:i:s'); // Assuming the purchase date is the current date/time

    // Retrieve product details for price calculation
    $sql_product = "SELECT pro_price FROM products WHERE id = $pro_id";
    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    // Calculate the total purchase amount
    $pur_total = $product['pro_price'] * $pur_quantity;

    // Insert new purchase details into the database
    $insert_sql = "INSERT INTO purchases (pro_id, user_id, pur_quantity, pur_date, pur_total) VALUES ('$pro_id', '$user_id', '$pur_quantity', '$pur_date', '$pur_total')";
    $insert_result = mysqli_query($conn, $insert_sql);

    if ($insert_result) {
        // Redirect to viewPurchases.php upon successful addition
        header("Location: viewPurchases.php");
        exit();
    } else {
        echo "Error adding purchase: " . mysqli_error($conn);
    }
}

// Fetch necessary data for the form (products and users)
$sql_products = "SELECT id, pro_name FROM products";
$result_products = mysqli_query($conn, $sql_products);

$sql_users = "SELECT id, username, address FROM users";
$result_users = mysqli_query($conn, $sql_users);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Purchase</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php include('topbar.php'); ?>
    <br>
    <div class="container mt-4">
        <h2>Add New Purchase</h2>
        <form method="POST" id="addPurchase">
            <div class="form-group">
                <label for="pro_id">Product:</label>
                <select class="form-control" id="pro_id" name="pro_id" required>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_products)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['pro_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="user_id">User:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_users)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['username'] . " - " . $row['address'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pur_quantity">Quantity:</label>
                <input type="number" class="form-control" id="pur_quantity" name="pur_quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Purchase</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('addPurchase').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to add this purchase details?')) {
                // If the admin confirms, submit the form
                this.submit();
            } else {
                // If the admin cancels, do nothing
                return false;
            }
        });
    </script>
</body>

</html>
