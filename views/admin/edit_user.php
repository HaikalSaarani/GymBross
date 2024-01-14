<?php
// Include your dbconnect file to establish a database connection
include('../../database/dbconnect.php');

// Check if the user ID is set and valid
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided or invalid
    header("Location: viewUsers.php");
    exit();
}

// Fetch user details based on the provided ID
$user_id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    // Redirect if no user found with the provided ID
    header("Location: viewUsers.php");
    exit();
}

// Fetch the user data
$user = mysqli_fetch_assoc($result);

// Check if the form is submitted for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated data from the form
    $updated_username = $_POST['username'];
    $updated_email = $_POST['email'];
    $updated_password = $_POST['password'];
    $updated_mem_id = $_POST['mem_id'];
    $updated_address = $_POST['address'];
    $updated_points = $_POST['points'];

    // Update user details in the database
    $update_sql = "UPDATE users SET username = '$updated_username', email = '$updated_email', password = '$updated_password', mem_id = '$updated_mem_id', address = '$updated_address', points = '$updated_points' WHERE id = $user_id";
    $update_result = mysqli_query($conn, $update_sql);
    

    if ($update_result) {
        // Redirect to viewUsers.php upon successful update
        header("Location: viewUsers.php");
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
    <?php include('topbar.php'); ?>
    <div class="container mt-4">
        <h2>Edit User</h2>
        <form method="POST" id="editForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
    <label for="password">Password:</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>" required>
        <div class="input-group-append">
            <span class="input-group-text" id="togglePassword">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </span>
        </div>
    </div>
</div>

            <div class="form-group">
                <label for="mem_id">Member Type:</label>
                    <select class="form-control" id="mem_id" name="mem_id" required>
                        <option value="4" <?php if ($user['mem_id'] == 1) echo "selected"; ?>>Amateur</option>
                        <option value="1" <?php if ($user['mem_id'] == 1) echo "selected"; ?>>Sigma</option>
                        <option value="2" <?php if ($user['mem_id'] == 2) echo "selected"; ?>>Beta</option>
                        <option value="3" <?php if ($user['mem_id'] == 3) echo "selected"; ?>>Alpha</option>
                    </select>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" id="address" name="address" required><?php echo $user['address']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="points">Points:</label>
                <input type="number" class="form-control" id="points" name="points" value="<?php echo $user['points']; ?>" >
            </div>
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

    togglePassword.addEventListener("click", function() {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);
        toggleIcon.classList.toggle("fa-eye");
        toggleIcon.classList.toggle("fa-eye-slash");
    });
</script>
<script>
        document.getElementById('editForm').addEventListener('submit', function(event) {
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
