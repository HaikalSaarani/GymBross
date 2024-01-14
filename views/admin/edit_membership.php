<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the membership ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewMembership.php");
    exit();
}

// Fetch membership details based on the provided ID
$membership_id = $_GET['id'];
$sql = "SELECT * FROM membership WHERE id = $membership_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    // Redirect if no membership found with the provided ID
    header("Location: viewMembership.php");
    exit();
}

// Fetch the membership data
$membership = mysqli_fetch_assoc($result);

// Check if the form is submitted for updating membership details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated data from the form
    $updated_mem_type = $_POST['mem_type'];
    $updated_mem_price = $_POST['mem_price'];
    $updated_mem_discount = $_POST['mem_discount'];
    $updated_mem_description = $_POST['mem_description'];

    // Update membership details in the database
    $update_sql = "UPDATE membership SET mem_type = '$updated_mem_type', mem_price = '$updated_mem_price', mem_discount = '$updated_mem_discount', mem_description = '$updated_mem_description' WHERE id = $membership_id";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        // Redirect to viewMembership.php upon successful update
        header("Location: viewMembership.php");
        exit();
    } else {
        echo "Error updating membership: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Membership</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">

</head>

<body>
    <?php include('topbar.php'); ?>
    <br><br>
    <div class="container mt-4">
        <h2>Edit Membership</h2>
        <form method="POST" class="editMem">
            <div class="form-group">
                <label for="mem_type">Membership Type:</label>
                <input type="text" class="form-control" id="mem_type" name="mem_type" value="<?php echo $membership['mem_type']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mem_price">Membership Price:</label>
                <input type="number" class="form-control" id="mem_price" name="mem_price" value="<?php echo $membership['mem_price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mem_discount">Membership Discount:</label>
                <input type="number" class="form-control" id="mem_discount" name="mem_discount" value="<?php echo $membership['mem_discount']; ?>" required>
            </div>
            <div class="form-group">
                <label for="mem_description">Membership Description:</label>
                <textarea class="form-control" id="mem_description" name="mem_description" required><?php echo $membership['mem_description']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('editMem').addEventListener('submit', function(event) {
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
