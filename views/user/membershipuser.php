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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize user input
    $mem_id = mysqli_real_escape_string($conn, $_POST['mem_id']);
    $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);

    // Retrieve membership details based on mem_id
    $membershipSql = "SELECT mem_type, mem_description FROM membership WHERE id = $mem_id";
    $membershipResult = mysqli_query($conn, $membershipSql) or die(mysqli_error($conn));
    $membershipRow = mysqli_fetch_assoc($membershipResult);

    // Display confirmation message with details
    echo "Membership Plan: " . $membershipRow['mem_type'] . "<br>";
    echo "Description: " . $membershipRow['mem_description'] . "<br>";
    echo "Payment Type: " . $payment_type . "<br>";

    // You can perform the update to the user's profile here
    // Update mem_id in the users table for the specific user
    $updateSql = "UPDATE users SET mem_id = $mem_id WHERE username = '$username'";
    mysqli_query($conn, $updateSql) or die(mysqli_error($conn));

    // Close the database connection
    mysqli_close($conn);

    // You may redirect the user to a success page or perform additional actions
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/stylo.css">
    <title>GymBross Membership</title>
</head>
<body>
<?php include 'headeruser.php'; ?>

    <h2>Membership Plans</h2>
    <p>Choose the membership plan that best suits your fitness goals and preferences.</p>

    <div class="row">
        <?php
        // Include database connection
        include '../../database/dbconnect.php';

        // Fetch membership details from the database until id <= 3
        $membershipSql = "SELECT id, mem_type, mem_description FROM membership WHERE id <= 3";
        $membershipResult = mysqli_query($conn, $membershipSql) or die(mysqli_error($conn));

        while ($membershipRow = mysqli_fetch_assoc($membershipResult)):
        ?>
        <div class="col">
            <div class="membership-plan">
                <h3><?php echo $membershipRow['mem_type']; ?></h3>
                <form method="post" action="memberpurchase.php">
                    <input type="hidden" name="mem_id" value="<?php echo $membershipRow['id']; ?>">
                    <ul>
                        <li><?php echo $membershipRow['mem_description']; ?></li>
                        <!-- Add more details if needed -->
                    </ul>
                    <label for="payment_type">Payment Type:</label>
                    <select name="payment_type" required>
                        <option value="Online Banking">Credit Card</option>
                        <option value="Touch'n'Go">Debit Card</option>
                        <option value="PayPal">PayPal</option>
                        <!-- Add more payment types as needed -->
                    </select>
                    <button type="submit" class="purchase-button" onclick="return confirm('Are you sure you want to purchase this membership plan?')">Purchase</button>
                </form>
            </div>
        </div>
        <?php endwhile;

        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php include('../../footer.php'); ?>
</body>
</html>
