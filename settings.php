<?php
//TURN ERROR_REPORTING OFF
error_reporting(1);

//MYSQLI connection
$conn = new mysqli("localhost", "USERNAME", "PW", "DB");

//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Language set
$lang = 'en';

//Recaptcha code (sitekey)
$recaptcha = '';

//SALT for more security
$salt = '';
?>
