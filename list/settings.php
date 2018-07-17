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

<<<<<<< HEAD
//Recaptcha code (sitekey)
=======
//Recaptcha code
>>>>>>> 1109d13fd9320a8b9a2b7e9e42f10f7a7b7f833e
$recaptcha = '';

//SALT for more security
$salt = '';
?>
