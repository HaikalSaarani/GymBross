<?php
session_start();

// Include your database connection file
include('../../database/dbconnect.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if pro_id is set in the URL
if (isset($_GET['pro_id'])) {
    // Sanitize the input to prevent SQL injection
    $pro_id = mysqli_real_escape_string($conn, $_GET['pro_id']);

    // Fetch product data from the database for the specified pro_id
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $pro_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the product exists
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);

        // Retrieve total_points_gained from the URL
        $total_points_gained = isset($_GET['total_points_gained']) ? $_GET['total_points_gained'] : 0;
    }
}

// Retrieve user details from the database
$username = $_SESSION['username'];

// Retrieve user_id based on username
$user_query = "SELECT u.id as user_id, mem_id, address, points FROM users u WHERE username = ?";
$user_stmt = mysqli_prepare($conn, $user_query);

if ($user_stmt) {
    mysqli_stmt_bind_param($user_stmt, 's', $username);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user_row = mysqli_fetch_assoc($user_result);
        $user_id = $user_row['user_id'];
        $address = $user_row['address'];
        $mem_id = $user_row['mem_id'];
        $points = $user_row['points'];
    } else {
        // Handle the case where no user is found
        die("Error: User not found");
    }

    mysqli_stmt_close($user_stmt);
} else {
    // Handle the case where the statement preparation failed
    die("Error in statement preparation: " . mysqli_error($conn));
}

// Check if redeem toggle is checked
$redeemPoints = isset($_POST['redeemToggle']) ? $points : 0;

include('headeruser.php');

// Process the form data
$pur_quantity = isset($_POST['pur_quantity']) ? $_POST['pur_quantity'] : 0;
$delivery_address = isset($_POST['delivery_address']) ? $_POST['delivery_address'] : '';
$voucher = isset($_POST['voucher']) ? $_POST['voucher'] : '';
$delivery_choice = isset($_POST['delivery_choice']) ? $_POST['delivery_choice'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$purpay_type = isset($_POST['purpay_type']) ? $_POST['purpay_type'] : '';

// Calculate total price based on quantity and product price
$product_price = $product['pro_price'];
$total_price = $pur_quantity * $product_price;

// Assuming you have the necessary variables available
$membership_rate = 1; // Default rate

// Check the membership type and set the rate accordingly
if ($mem_id == 1) {
    $membership_rate = 1.5;
} elseif ($mem_id == 2) {
    $membership_rate = 2;
} elseif ($mem_id == 3) {
    $membership_rate = 2.5;
}

// Display user-entered data
echo "<h2 class='title'>Product: " . $product['pro_name'] . "</h2>";
echo "<p class='text'>Price: RM" . number_format($product['pro_price'], 2) . "</p>";
echo "<p class='text'>Quantity: " . $pur_quantity . "</p>";
echo "<p class='text'>Delivery Address: " . $delivery_address . "</p>";
echo "<p class='text'>Delivery Choice: " . $delivery_choice . "</p>";
echo "<p class='text'>Message to Seller: " . $message . "</p>";
echo "<p class='text'>Payment Method: " . $purpay_type . "</p>";

// Calculate the total points gained based on the rate
$total_points_gained = $total_price * $membership_rate;

// Display the total points gained
echo "<p>Total Points Gained: " . $total_points_gained . " points</p>";

// Payment Details
echo "<div class='form-group'>";
echo "<h4>Payment Details</h4>";

// Calculate the total product price
$totalProductPrice = $product['pro_price'] * $pur_quantity;

// Display the total product price
echo "<p>Product Price: RM " . number_format($totalProductPrice, 2) . "</p>";

// Assuming you have the product price and membership type available
$membership_discount_percent = 0; // Default discount percent

// Check the membership type and set the discount percent accordingly
if ($mem_id == 1) {
    $membership_discount_percent = 5;
} elseif ($mem_id == 2) {
    $membership_discount_percent = 10;
} elseif ($mem_id == 3) {
    $membership_discount_percent = 15;
}

// Calculate the discount amount
$discount_amount = ($membership_discount_percent / 100) * $total_price;

// Display the total discount
echo "<p>Total Discount: RM " . number_format($discount_amount, 2) . " (" . $membership_discount_percent . "% discount)" . "</p>";

// Display the total points redeemed
echo "<p id='redeemPointsTotal'>Total Amount Converted: RM " . ($redeemPoints / 100) . "</p>";

// Assuming you have updated $points when redeeming
$total_points_redeemed = $redeemPoints;
$redeemPointsAmount = $redeemPoints / 100;

// Calculate the total to pay
$pur_total = $total_price - $discount_amount - $redeemPointsAmount;

// Update user points after purchase
$new_points = $points - $redeemPoints + $total_points_gained;

// Update user points in the "users" table
$update_points_sql = "UPDATE users SET points = ? WHERE id = ?";
$update_points_stmt = mysqli_prepare($conn, $update_points_sql);

if ($update_points_stmt) {
    mysqli_stmt_bind_param($update_points_stmt, 'is', $new_points, $user_id);
    mysqli_stmt_execute($update_points_stmt);

    // Check if the update was successful
    if (mysqli_stmt_affected_rows($update_points_stmt) > 0) {
        // Points update was successful
    } else {
        // Points update failed, handle accordingly
    }

    mysqli_stmt_close($update_points_stmt);
} else {
    // Handle the case where the statement preparation failed
    die("Error in statement preparation: " . mysqli_error($conn));
}

// Display the total to pay
echo "<h3>Total to Pay: RM " . number_format($pur_total, 2) . "</h3>";
echo "</div>";

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data

    // Insert purchase data into the "purchases" table
    $insert_sql = "INSERT INTO purchases (pro_id, pur_quantity, pur_date, pur_total, user_id) VALUES (?, ?, NOW(), ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);

    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, 'sdis', $pro_id, $pur_quantity, $pur_total, $user_id);
        mysqli_stmt_execute($insert_stmt);

        mysqli_stmt_close($insert_stmt);
    } else {
        // Handle the case where the statement preparation failed
        die("Error in statement preparation: " . mysqli_error($conn));
    }
}
?>
<div class="no-print">
    <input type="button" class="btn btn-success" value="Print" onclick="window.print();">
    <a href="shopuser.php" class="btn btn-primary">Done</a>
</div>
<br>
