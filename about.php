<?php include 'functions.php';
if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    Header("Location:auth.php");
}


$template->parse('html/about.html');

?>
