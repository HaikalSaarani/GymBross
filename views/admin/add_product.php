<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define folder path for storing uploaded images
    $targetDirectory = "../../../img"; // Change this directory path to your desired location

    // Retrieve data from the form
    $pro_name = $_POST['pro_name'];
    $pro_price = $_POST['pro_price'];
    $pro_description = $_POST['pro_description'];

    // Process uploaded image file
    if (!empty($_FILES['pro_img']['name'])) {
        $fileName = basename($_FILES['pro_img']['name']);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Upload file to the specified directory
            if (move_uploaded_file($_FILES['pro_img']['tmp_name'], $targetFilePath)) {
                // Insert product details into the database with the uploaded image file
                $insert_sql = "INSERT INTO products (pro_img, pro_name, pro_price, pro_description) VALUES ('$targetFilePath', '$pro_name', '$pro_price', '$pro_description')";
                $insert_result = mysqli_query($conn, $insert_sql);

                if ($insert_result) {
                    // Redirect to viewProducts.php upon successful addition
                    header("Location: viewProducts.php");
                    exit();
                } else {
                    echo "Error adding product: " . mysqli_error($conn);
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        // If no image is uploaded, insert product details without the image
        $insert_sql = "INSERT INTO products (pro_name, pro_price, pro_description) VALUES ('$pro_name', '$pro_price', '$pro_description')";
        $insert_result = mysqli_query($conn, $insert_sql);

        if ($insert_result) {
            // Redirect to viewProducts.php upon successful addition
            header("Location: viewProducts.php");
            exit();
        } else {
            echo "Error adding product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php include('topbar.php'); ?>
    <br>
    <div class="container mt-4">
        <h2>Add New Product</h2>
        <form method="POST" id="addProduct" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pro_img">Product Image:</label>
                <input type="file" class="form-control-file" id="pro_img" name="pro_img">
            </div>
            <div class="form-group">
                <label for="pro_name">Product Name:</label>
                <input type="text" class="form-control" id="pro_name" name="pro_name" required>
            </div>
            <div class="form-group">
                <label for="pro_price">Product Price:</label>
                <input type="number" class="form-control" id="pro_price" name="pro_price" required>
            </div>
            <div class="form-group">
                <label for="pro_description">Product Description:</label>
                <textarea class="form-control" id="pro_description" name="pro_description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('addProduct').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to add this product?')) {
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
