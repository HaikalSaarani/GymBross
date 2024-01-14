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


// Fetch user details, including role, from the database
$username = $_SESSION['username'];
$userSql = "SELECT role_id FROM users WHERE username = '$username'";
$userResult = mysqli_query($conn, $userSql) or die(mysqli_error($conn));
$userRow = mysqli_fetch_assoc($userResult);

// Check user role and restrict access if necessary
$allowedRoleID = 2; // Assuming roleID 1 corresponds to the 'Administrator' role
if ($userRow['role_id'] != $allowedRoleID) {
    // Redirect to a restricted access page or show an error message
    header("Location: restricted_access.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/stylo.css">
    <link rel="stylesheet" href="../../css/style copy.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Home</title>
</head>
<body>
    <header>
    <div class="logo">
            <img src="../../img/brosslogo.png" alt="GymBross Logo" width="200" height="200">
            
        </div>
        <nav>
            <a href="indexuser.php">Home</a>
            <a href="shopuser.php">Shop</a>
            <a href="membershipuser.php">Membership</a>
            <a href="contactuser.php">Contact</a>
            
            <a href="profileuser.php">Profile</a>
            <a href="../../logout.php">Logout</a>
            
        </nav>
    </header>


<h1 style="text-align: center;">Welcome to GymBross</h1>
<section>
<div class="container-fluid p-0 mb-0">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="../../img/gymweb.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase">Best Gym Center</h5>
                            <h1 class="display-2 text-white text-uppercase mb-md-4">Build Your Body Strong With GymBross</h1>
                            
                        </div>
                    </div>
                </div>
                
</section>

    <!-- About Start -->
    <div class="container-fluid p-5">
        <div class="row gx-5">
            <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" src="../../img/about.jpg" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="mb-4">
                    <h5 class="text-primary text-uppercase">About Us</h5>
                    <h1 class="display-3 text-uppercase mb-0">Welcome to GymBross</h1>
                </div>
                <h4 class="text-body mb-4">Building the next generations of legend</h4>
                <p class="mb-4">We provide a safe, secure and comfortable environment to train in no matter what your experience or fitness level is. We boast a strong community of like-minded people supporting and encouraging one another to achieve legendary greatness.</p>
                <div class="rounded bg-dark p-5">
                    <ul class="nav nav-pills justify-content-between mb-3">
                        <li class="nav-item w-50">
                            <a class="nav-link text-uppercase text-center w-100 active" data-bs-toggle="pill" href="#pills-1">Why Choose Us</a>
                        </li>
                        
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-1">
                            <p class="text-secondary mb-0">If you’re serious about achieving a goal- no matter what that is- then we are your gym. From the best equipment, technology and programs to the best fitness professionals around, we are serious about getting you results.</p>
                        </div>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Programe Start -->
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
                </div>
            </div>
            <div class="col-lg-12 col-md-6 text-center">
                <h1 class="text-uppercase text-light mb-4">MEMBERSHIPS with benefits</h1>
                <a href="membership.php" class="btn btn-primary py-3 px-5">Become A Member</a>
            </div>
        </div>
    </div>
    <!-- Programe Start -->


    <footer>
        <p>&copy; <?php echo date('2024'); ?> GymBross. All Rights Reserved</p>
    </footer>
</body>

</html>
