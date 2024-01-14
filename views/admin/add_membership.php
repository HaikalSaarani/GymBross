<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $mem_type = $_POST['mem_type'];
    $mem_price = $_POST['mem_price'];
    $mem_discount = $_POST['mem_discount'];
    $mem_description = $_POST['mem_description'];

    // Insert membership details into the database
    $insert_sql = "INSERT INTO membership (mem_type, mem_price, mem_discount, mem_description) VALUES ('$mem_type', '$mem_price', '$mem_discount', '$mem_description')";
    $insert_result = mysqli_query($conn, $insert_sql);

    if ($insert_result) {
        // Redirect to viewMembership.php upon successful addition
        header("Location: viewMembership.php");
        exit();
    } else {
        echo "Error adding membership: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Membership</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>

<body>
    <?php include('topbar.php'); ?>
    <br>
    <div class="container mt-4">
        <h2>Add New Membership</h2>
        <form method="POST" id="addMembership">
            <div class="form-group">
                <label for="mem_type">Membership Type:</label>
                <input type="text" class="form-control" id="mem_type" name="mem_type" required>
            </div>
            <div class="form-group">
                <label for="mem_price">Membership Price:</label>
                <input type="number" class="form-control" id="mem_price" name="mem_price" required>
            </div>
            <div class="form-group">
                <label for="mem_discount">Membership Discount:</label>
                <input type="number" class="form-control" id="mem_discount" name="mem_discount" required>
            </div>
            <div class="form-group">
                <label for="mem_description">Membership Description:</label>
                <textarea class="form-control" id="mem_description" name="mem_description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Membership</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById('addMembership').addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to add this membership?')) {
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
