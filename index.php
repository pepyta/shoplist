<?php
require_once 'functions.php';

if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    redirect("auth.php");
}


$template->parse('html/head.html');
$template->parse('html/navbar.html');
$template->parse('html/index.html');
$template->parse('html/scripts.html');
?>
