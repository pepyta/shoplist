<?php

//TURN ERROR_REPORTING OFF
error_reporting(0);

//MYSQLI connection
$conn = new mysqli("SERVER", "USERNAME", "PW", "DB");

//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//SALT for more security
$salt = '';
?>
