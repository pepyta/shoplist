<?php
include 'functions.php';
error_reporting(1);

//AUTO LOGIN
if(isUserLoggedIn($_SESSION['name'], $_SESSION['ssid'])){
    redirect("index.php");
}

$template->assign('RECAPTCHA', $recaptcha);
$template->assign('HEADER', "SHOPLIST");
$template->assign('BOTTOM_BAR', "");
$template->parse("html/head.html");
$template->parse("html/auth.html");
$template->parse("html/scripts.html"); 

?>
