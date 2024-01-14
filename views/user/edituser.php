<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Start the session
session_start();

// Retrieve user information from the session
$username = $_SESSION['username'];

// Retrieve user details from the database
$user_query = "SELECT username, email, mem_id, password, address, points FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_row = mysqli_fetch_assoc($user_result);
    $username = $user_row['username'];
    $email = $user_row['email'];
    $password = $user_row['password'];
    $mem_id = $user_row['mem_id'];
    $address = $user_row['address'];
    $points = $user_row['points'];
}

// Check if the form is submitted for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated data from the form
    $updated_username = $_POST['username'];
    $updated_email = $_POST['email'];
    $updated_password = $_POST['password'];
    $updated_mem_id = $_POST['mem_id'];
    $updated_address = $_POST['address'];

    // Update user details in the database
    $update_sql = "UPDATE users SET username = '$updated_username', email = '$updated_email', password = '$updated_password', mem_id = '$updated_mem_id', address = '$updated_address' WHERE username = '$username'";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        // Redirect to viewUsers.php upon successful update
        header("Location: profileuser.php");
        exit();
    } else {
        echo "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include('headeruser.php'); ?>
    <div class="container mt-4">
        <h2>Edit User</h2>
        <form method="POST" id="editForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="mem_id">Member Type:</label>
                <select class="form-control" id="mem_id" name="mem_id" readonly>
                    <?php
                    $membership_types = [
                        1 => 'Sigma',
                        2 => 'Beta',
                        3 => 'Alpha',
                        4 => 'Amateur',
                    ];

                    $selected_membership = isset($membership_types[$user_row['mem_id']]) ? $membership_types[$user_row['mem_id']] : 'Amateur';
                    echo "<option value='{$user_row['mem_id']}' selected>{$selected_membership}</option>";
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" id="address" name="address" required><?php echo $address; ?></textarea>
            </div>
            <!-- Add other fields to edit here -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include any custom scripts here -->
    <script>
        const passwordField = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const toggleIcon = document.getElementById("toggleIcon");

        togglePassword.addEventListener("click", function () {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
            toggleIcon.classList.toggle("fa-eye");
            toggleIcon.classList.toggle("fa-eye-slash");
        });
    </script>
    <script>
        document.getElementById('editForm').addEventListener('submit', function (event) {
            event.preventDefault();
            if (confirm('Are you sure you want to update this user?')) {
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
