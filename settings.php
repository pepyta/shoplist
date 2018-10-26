<?php
//TURN ERROR_REPORTING OFF
error_reporting(E_ALL);

//MYSQLI connection
$conn = new mysqli("mysql.nethely.hu", "root", "", "list");

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
