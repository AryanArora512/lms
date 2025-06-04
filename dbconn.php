<?php
// This file should NOT start sessions!

$dbservername = "localhost";
$dbusername = "root";
$dbpassword = ""; // change this if using default XAMPP
$dbname = "lms";

// Create connection
$conn = mysqli_connect($dbservername, $dbusername, $dbpassword, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
