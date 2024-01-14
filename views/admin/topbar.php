<?php
session_start();
include '../../database/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}
?>
<style>
    .logo {
        margin: auto;
        font-size: 20px;
        background: white;
        padding: 7px 11px;
        border-radius: 50% 50%;
        color: #000000b3;
    }
</style>

<nav class="navbar navbar-light fixed-top bg-primary" style="padding:0;min-height: 3.5rem">
    <div class="container-fluid mt-2 mb-2">
        <div class="col-lg-12">
            <div class="col-md-1 float-left" style="display: flex;">
            </div>
            <div class="col-md-4 float-left text-white">
                <large><b>GymBross Management System</b></large>
            </div>
            <div class="float-right">
                <div class="mr-4">
                    <a href="../../logout.php" class="text-white">
                        <button class="btn btn-danger">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
