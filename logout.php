<?php
session_start();
$_SESSION['ssid'] = "";
$_SESSION['gid'] = "";
$_SESSION['name'] = "";
Header("Location:auth.php");
?>
