<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the form is submitted for adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $new_email = $_POST['email'];
    $new_role_id = $_POST['role_id'];
    $new_mem_id = $_POST['mem_id'];
    $new_address = $_POST['address'];

    // Insert new user into the database
    $insert_sql = "INSERT INTO users (username, password, email, role_id, mem_id, address) VALUES ('$new_username', '$new_password', '$new_email', 2, 4, '$new_address')";
    $insert_result = mysqli_query($conn, $insert_sql);

    if ($insert_result) {
        // Redirect to viewUsers.php upon successful addition
        header("Location: viewUsers.php");
        exit();
    } else {
        echo "Error adding user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>GymBross Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/styles.css">

<body>
    <?php include('topbar.php'); ?>
    <br>
    <div class="container mt-4">
        <h2>Add New User</h2>
        <form method="POST">
            <!-- Input fields for new user details -->
            <!-- Adjust form fields according to your database schema -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
            <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>

    <!-- ...existing script tags... -->
</body>

</html>
