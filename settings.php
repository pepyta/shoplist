<?php
//TURN ERROR_REPORTING OFF
error_reporting(E_ALL);

//MYSQLI connection
$conn = new mysqli("localhost", "list", "17154ACBA0ED3B3C472355E452DA5EB0C084C1CB676E3319A2D668197B57986B", "list");

//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Language set
$lang = 'en';

//Recaptcha code (sitekey)
$recaptcha = '6LeRHF0UAAAAAA6hI6xnAYuKpE_8-LTwCTtzLPt4';

//SALT for more security
$salt = '7B43746159BBAAB36D3A3F5D475A231190596FB7795F0FE8AB71A9F424CB360F';
?>
