<?php
//TURN ERROR_REPORTING OFF
error_reporting(1);

//MYSQLI connection
//$conn = new mysqli("localhost", "list", "67cd827a3e3e9971ca7977344d", "list");
$conn = new mysqli("localhost", "root", "", "list");

//Make sure website works properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//Language set
$lang = 'en';

//SALT for more security
$salt = 'c0a991a24663988cf7b267cd827a3e3e9971ca7977344dc0c3ce7716c549c4f2';
?>
