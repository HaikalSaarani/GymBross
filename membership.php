<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/stylo.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/style copy.css">
    <title>GymBross Membership</title>
</head>
<body>
<?php include 'header.php'; ?>

        <h3 class="display-5 text-uppercase mb-0" style="text-align: center;">Membership Plans</h3>
        <h4 class="text-body mb-4" style="text-align: center;">Choose the membership plan that best suits your fitness goals and preferences.</h4>

        <div class="container-fluid programe position-relative px-5 mt-5" style="margin-bottom: 135px;">
        <div class="row g-5 gb-5">
            <div class="col-lg-4 col-md-6">
                <div class="bg-light rounded text-center p-5">
                    <h3 class="text-uppercase my-4">Sigma</h3>
                    <ul>
                    <li>Access to gym facilities</li>
                    <li>Use of basic equipment</li>
                    <li>Discount 5% for any item</li>
                    <li>Monthly fee: RM50</li>
                </ul>
                <button class="purchase-button" disabled>
                            Please <a href="login.php">login</a> or <a href="registeruser.php">sign up</a> to purchase.
                        </button>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bg-secondary rounded text-center p-5">
                    <h3 class="text-uppercase my-4">Beta</h3>
                    <ul>
                    <li>Access to all gym facilities</li>
                    <li>Use of advanced equipment</li>
                    <li>Discount 10% for any item</li>
                    <li>Monthly fee: RM80</li>
                </ul>
                <button class="purchase-button" disabled>
                            Please <a href="login.php">login</a> or <a href="registeruser.php">sign up</a> to purchase.
                        </button>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bg-warning rounded text-center p-5">
                    <h3 class="text-uppercase my-4">Alpha</h3>
                    <ul>
                    <li>Access to all gym facilities</li>
                    <li>Use of all equipment</li>
                    <li>Discount 15% for any item</li>
                    <li>Monthly fee: RM120</li>
                    
                </ul>
                <button class="purchase-button" disabled>
                            Please <a href="login.php">login</a> or <a href="registeruser.php">sign up</a> to purchase.
                        </button>
                </div>
            </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</div>
    <?php include('footer.php'); ?>


