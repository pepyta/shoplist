<?php
include 'functions.php';
//AUTO LOGIN
if(isUserLoggedIn($_SESSION['name'], $_SESSION['ssid'])){
    redirect("index.php");
}

$template->parse("html/head.html");
$template->parse("html/login.html");
$template->parse("html/scripts.html"); 

?>
