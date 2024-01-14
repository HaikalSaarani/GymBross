<?php
// Include your database connection file
include 'database/dbconnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Error: Invalid email format.";
    } elseif (strlen($password) < 8) {
        // Validate password length
        echo "Error: Password must be at least 8 characters.";
    } elseif ($password !== $confirmPassword) {
        // Check if passwords match
        echo "Error: Passwords do not match.";
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO `users` (`username`, `password`, `email`, `role_id`, `mem_id`, `address`) 
                VALUES ('$username', '$password', '$email', 2, 4, '$address')";

        if (mysqli_query($conn, $sql)) {
            // Registration successful, redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymBross Registration</title>
    <style>
        .password-container {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <h2 style="text-align: center;">Register for GymBross Gym Membership</h2>
    <section>
    <div class="row g-0">
        <div class="col-lg-6">
            <div class="bg-dark p-5">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
    <div class="row g-1">
    <div class="col-lg-6">    
        <input type="text" class="form-control bg-light border-0 px-4" placeholder="Username" name="username" required><br>
        </div>

        <div class="col-lg-12">
        <div class="password-container">
            <input type="password" class="form-control bg-light border-0 px-4" placeholder="Password" name="password" id="password" required>
            <img  class="eye-icon" onclick="togglePasswordVisibility('password')">
        </div>
    </div>

        <div class="col-12">
        <div class="password-container">
            <input type="password" class="form-control bg-light border-0 px-4" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
            <img  class="eye-icon" onclick="togglePasswordVisibility('confirm_password')">
        </div>
    </div>

        <div class="col-12">
        <input type="email" class="form-control bg-light border-0 px-4" placeholder="Email" name="email" required><br><br>
        </div>

        <div class="col-12">
        <textarea class="form-control bg-light border-0 px-4 py-3" rows="4" placeholder="Address" name="address" required></textarea><br><br>
    </div>

        <div class="col-12">
        <input type="submit" button class="btn btn-primary w-100 py-3" value="Register">
    </form>
    </section>

    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
