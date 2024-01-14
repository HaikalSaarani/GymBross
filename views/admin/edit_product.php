<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the product ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewProducts.php");
    exit();
}

// Fetch product details based on the provided ID
$product_id = $_GET['id'];
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    // Redirect if no product found with the provided ID
    header("Location: viewProducts.php");
    exit();
}

// Fetch the product data
$product = mysqli_fetch_assoc($result);

// Check if the form is submitted for updating product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated data from the form
    $updated_pro_name = $_POST['pro_name'];
    $updated_pro_price = $_POST['pro_price'];
    $updated_pro_description = $_POST['pro_description'];

    // Check if a new image is uploaded
    if (!empty($_FILES['pro_img']['name'])) {
        // Process uploaded image file
        $targetDirectory = "../../../img"; // Change this directory path to your desired location
        $fileName = basename($_FILES['pro_img']['name']);
        $targetFilePath = $targetDirectory . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Upload file to the specified directory
            if (move_uploaded_file($_FILES['pro_img']['tmp_name'], $targetFilePath)) {
                // Update product details in the database with the new image file
                $update_sql = "UPDATE products SET pro_img = '$targetFilePath', pro_name = '$updated_pro_name', pro_price = '$updated_pro_price', pro_description = '$updated_pro_description' WHERE id = $product_id";
                $update_result = mysqli_query($conn, $update_sql);
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        // If no new image is uploaded, update other product details except for the image
        $update_sql = "UPDATE products SET pro_name = '$updated_pro_name', pro_price = '$updated_pro_price', pro_description = '$updated_pro_description' WHERE id = $product_id";
        $update_result = mysqli_query($conn, $update_sql);
    }

    if ($update_result) {
        // Redirect to viewProducts.php upon successful update
        header("Location: viewProducts.php");
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">

</head>

<body>
    <?php include('topbar.php'); ?>
    <div class="container mt-4">
        <h2>Edit Product</h2>
        <form method="POST" id="editpro" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pro_img">Product Image:</label>
                <img src="<?php echo $product['pro_img']; ?>" alt="Product Image" width="100"><br>
                <input type="file" class="form-control-file" id="pro_img" name="pro_img">
            </div>
            <div class="form-group">
                <label for="pro_name">Product Name:</label>
                <input type="text" class="form-control" id="pro_name" name="pro_name" value="<?php echo $product['pro_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="pro_price">Product Price:</label>
                <input type="number" class="form-control" id="pro_price" name="pro_price" value="<?php echo $product['pro_price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="pro_description">Product Description:</label>
                <textarea class="form-control" id="pro_description" name="pro_description" required><?php echo $product['pro_description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('editpro').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to update this product?')) {
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
