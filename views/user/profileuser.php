<?php
// Assuming you have a session established after login
session_start();
include('../../database/dbconnect.php');

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

// Retrieve user information from the session
$username = $_SESSION['username'];


// Retrieve user details from the database
$user_query = "SELECT username, email, mem_id, address, points FROM users WHERE username = ?";
$user_stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($user_stmt, "s", $username);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_row = mysqli_fetch_assoc($user_result);
    $username = $user_row['username'];
    $email = $user_row['email'];
    $user_mem_id = $user_row['mem_id'];
    $address = $user_row['address'];
    $points = $user_row['points'];

    // Retrieve membership details from the database
    $membership_query = "SELECT m.mem_type, m.mem_discount FROM membership m 
                        WHERE m.id = ?";
    $membership_stmt = mysqli_prepare($conn, $membership_query);
    mysqli_stmt_bind_param($membership_stmt, "i", $user_mem_id);
    mysqli_stmt_execute($membership_stmt);
    $membership_result = mysqli_stmt_get_result($membership_stmt);

    if ($membership_result && mysqli_num_rows($membership_result) > 0) {
        $membership_row = mysqli_fetch_assoc($membership_result);
        $membership_type = $membership_row['mem_type'];
        $membership_discount = $membership_row['mem_discount'];
    } else {
        $membership_type = "Not a member";
        $membership_discount = 0;  // Set a default discount value or handle it as needed
    }

} else {
    // Redirect if user details are not found
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/stylo.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <!-- Add this line to include the CSS stylesheet -->
<link rel="stylesheet" href="../../css/prof.css">

    <title>Profile</title>
</head>
<body>
<?php include('headeruser.php'); ?>

    <section>
        <h2><?php echo $username; ?>'s Profile</h2><br>
        <div class="row"></div>
        <p>Username: <?php echo $username; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Address: <?php echo $address; ?></p><br>
        <p>Membership Type: <?php echo $membership_type; ?></p>
        <p>Membership Discount: <?php echo $membership_discount; ?></p>
        <p>Total Point Gained: <?php echo $points; ?></p>

        <a href="edituser.php" class="edit-button btn-primary">Edit Profile</a>
    </section>

    <?php include('../../footer.php'); ?>
</body>
</html>
