<?php
include('../../database/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $purchase_id = $_POST['purchase_id'];
    $pro_id = $_POST['pro_id'];
    $user_id = $_POST['user_id'];
    $pur_quantity = $_POST['pur_quantity'];
    $pur_date = $_POST['pur_date'];

    // Retrieve product details for price calculation
    $sql_product = "SELECT pro_price FROM products WHERE id = $pro_id";
    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    // Calculate the total purchase amount
    $pur_total = $product['pro_price'] * $pur_quantity;

    // Update purchase details in the database
    $update_sql = "UPDATE purchases SET pro_id = '$pro_id', user_id = '$user_id', pur_quantity = '$pur_quantity', pur_date = '$pur_date', pur_total = '$pur_total' WHERE id = $purchase_id";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        // Redirect to viewPurchases.php upon successful update
        header("Location: viewPurchases.php");
        exit();
    } else {
        echo "Error updating purchase: " . mysqli_error($conn);
    }
}

// Fetch necessary data for the form (products and users)
$purchase_id = $_GET['id'];
$sql_purchase = "SELECT * FROM purchases WHERE id = $purchase_id";
$result_purchase = mysqli_query($conn, $sql_purchase);
$purchase = mysqli_fetch_assoc($result_purchase);

$sql_products = "SELECT id, pro_name FROM products";
$result_products = mysqli_query($conn, $sql_products);

$sql_users = "SELECT id, username, address FROM users";
$result_users = mysqli_query($conn, $sql_users);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Purchase</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php include('topbar.php'); ?>
    <br>
    <div class="container mt-4">
        <h2>Edit Purchase</h2>
        <form method="POST" id="editPurchase">
            <input type="hidden" name="purchase_id" value="<?php echo $purchase['id']; ?>">
            <div class="form-group">
                <label for="pro_id">Product:</label>
                <select class="form-control" id="pro_id" name="pro_id" required>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_products)) {
                        echo "<option value='" . $row['id'] . "' " . ($purchase['pro_id'] == $row['id'] ? 'selected' : '') . ">" . $row['pro_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="user_id">User:</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <?php
                    while ($row = mysqli_fetch_assoc($result_users)) {
                        echo "<option value='" . $row['id'] . "' " . ($purchase['user_id'] == $row['id'] ? 'selected' : '') . ">" . $row['username'] . " - " . $row['address'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="pur_quantity">Quantity:</label>
                <input type="number" class="form-control" id="pur_quantity" name="pur_quantity" value="<?php echo $purchase['pur_quantity']; ?>" required>
            </div>
            <div class="form-group">
                <label for="pur_date">Purchase Date:</label>
                <input type="date" class="form-control" id="pur_date" name="pur_date" value="<?php echo $purchase['pur_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Purchase</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('editPurchase').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to update this purchase details?')) {
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
