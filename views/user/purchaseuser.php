<!DOCTYPE html>
<html lang="en">

<?php
session_start();

// Include your database connection file
include('../../database/dbconnect.php');

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Initialize $user as an empty array
$user = [];

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
    }
}
// Retrieve user details from the database
$username = $_SESSION['username'];  // Use $_SESSION['username'] to get the current username

$user_query = "SELECT username, mem_id, address, points FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_row = mysqli_fetch_assoc($user_result);
    $address = $user_row['address'];
    $mem_id = $user_row['mem_id'];
    $points = $user_row['points'];
}

// Check if redeem toggle is checked

$redeemPoints = isset($_POST['redeemToggle']) ? $points : 0;

?>

<head>
    <meta charset="UTF-8">
    <title>Purchase</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/stylo.css" rel="stylesheet">
    <script>
    function submitForm() {
        // Get the product ID from your PHP variable (make sure it's available)
        var productId = <?php echo $product['id']; ?>;

        // Set the product ID in the hidden input field
        document.getElementById('pro_id').value = productId;

        // Submit the form
        document.getElementById('purchaseForm').submit();
    }
</script>

</head>

<body>
    <?php include('headeruser.php'); ?>
    <div class="container mt-4">
        <h2>Product Purchase</h2>

        <div class="container">
            <img src="<?= $product['pro_img']; ?>" class="pro.img" alt="Product Image">
            
                <h2 class="title"><?= $product['pro_name']; ?></h2>
                <p class="text">Price: RM<?= number_format($product['pro_price'], 2); ?></p>

        </div>

        <input type="hidden" id="redeemPointsAmount" name="redeemPointsAmount" value="0">

        <form id="purchaseForm" method="POST" action="purchasedetails.php?pro_id=<?php echo $product['id']; ?>">
            <div class="form-group">
                <label for="pur_quantity">Quantity:</label>
                <input type="number" class="form-control" id="pur_quantity" name="pur_quantity" required>
            </div>

            <div class="form-group">
                <label for="delivery_address">Delivery Address:</label>
                <textarea class="form-control" id="delivery_address" name="delivery_address" required><?php echo $address; ?></textarea>
            </div>

            <div class="form-group">
                <label for="delivery_choice">Delivery Choice:</label>
                <select class="form-control" id="delivery_choice" name="delivery_choice" required>
                    <option value="Send to Home">Send to Home</option>
                    <option value="Self-Pickup">Self-Pickup</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Message to Seller:</label>
                <textarea class="form-control" id="message" name="message"></textarea>
            </div>

            <div class="form-group">
                <label for="redeem_points">Points Available:</label>
                <p id="pointsAvailable">
                    <?php echo $points; ?>
                    (RM <?php echo $points/100; ?> can be redeem)
                </p>
                <?php if ($points >= 100): ?>
                    <label class="switch">
                        <input type="checkbox" id="redeemToggle" name="redeemToggle">
                        <span class="slider"></span>
                    </label>
                    <span id="redeemStatus">Redeem Points</span>
                <?php else: ?>
                    <span id="redeemStatus" style="color: grey;">Insufficient Points to Redeem</span>
                <?php endif; ?>
            </div>

            <script>
                redeemToggle.addEventListener("change", function() {
                    if (this.checked) {
                        // If toggle is on, show redeem status
                        redeemStatus.textContent = "Redeem Points";
                        redeemPoints = <?php echo $points; ?>; // Update redeemPoints when redeeming
                    } else {
                        // If toggle is off, show not redeeming status
                        redeemStatus.textContent = "Not Redeeming";
                        redeemPoints = 0; // Update redeemPoints when not redeeming
                    }

                    // Update the display of total points redeemed
                    redeemPointsDisplay.textContent = "Total Points Redeemed: " + redeemPoints + " points";

                    // Update the display of total amount converted
                    redeemPointsTotal.textContent = "Total Amount Converted: RM " + redeemPoints/100 ;

                    // Update the display of total amount deduct
                    redeemPointsDeduct.textContent = redeemPoints / 100;

                    // Set the value of redeemPointsDeduct in the hidden input field
                    var redeemPointsAmountElement = document.getElementById("redeemPointsAmount");
                    redeemPointsAmountElement.value = redeemPoints / 100;
                });

                // Disable the toggle if points are less than 100
                if (<?php echo $points; ?> < 100) {
                    redeemToggle.disabled = true;
                }

                document.getElementById('pur_quantity').addEventListener('input', updateProductPrice);

                function updateProductPrice() {
                    var quantity = $('#pur_quantity').val();
                    var productPrice = <?= $product['pro_price']; ?>;
                    var totalPrice = quantity * productPrice;
                    $('#productQuantity').text(quantity);
                    $('#productPrice').text('RM ' + totalPrice.toFixed(2));
                }

                function proceedToPayment() {
                // Get the pro_id value from the hidden input
                var proId = document.getElementById('pro_id').value;

                // Construct the URL with pro_id as a query parameter
                var url = 'purchasedetails.php?pro_id=' + proId;

                // Redirect to the URL
                window.location.href = url;
                }
            </script>

            <p id="redeemPointsDisplay">Total Points Redeemed: <?php echo isset($_POST['redeemToggle']) ? $points : 0; ?> points</p>

            <div class="form-group">
                <label for="purpay_type">Payment Method:</label>
                <select class="form-control" id="purpay_type" name="purpay_type" required>
                    <option value="Online Banking">Online Banking</option>
                    <option value="Credit/Debit Card">Credit/Debit Card</option>
                    <option value="Touch 'n Go">Touch 'n Go</option>
                </select>
            </div>

            <?php
                $product_price = $product['pro_price']; 

                $membership_rate = 1; // Default rate

                // Check the membership type and set the rate accordingly
                if ($mem_id == 1) {
                    $membership_rate = 1.5;
                } elseif ($mem_id == 2) {
                    $membership_rate = 2;
                } elseif ($mem_id == 3) {
                    $membership_rate = 2.5;
                }

                // Calculate the total points gained based on the rate
                $total_points_gained = $product_price  * $membership_rate ;

                // Display the total points gained
                echo "<p>Total Points Gained: " . $total_points_gained . " points</p>";
            ?>

<input type="hidden" id="pro_id" name="pro_id" value="">


<a href="#" onclick="submitForm();" class="btn btn-secondary">Proceed to Payment</a>

        </form>
    </div>
    <br>
    <?php include('../../footer.php'); ?>

</body>

</html>
