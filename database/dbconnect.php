<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymbross";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname) or die('Error connecting to database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

