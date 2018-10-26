<?php
include 'functions.php';

if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    redirect('login.php');
}
if(getDataOfUser($_SESSION['name'], 'google_analytics') == 'on'){
    $checkbox_google_analytics = 'checked="checked"';
} else {
    $checkbox_google_analytics = "";
}
$template->assign('EMAIL', getDataOfUser($_SESSION['name'], 'email'));
$template->assign('USERNAME', getDataOfUser($_SESSION['name'], 'name'));
$template->assign('NAME', getDataOfUser($_SESSION['name'], 'nick'));
$template->assign('CHECKBOX_GOOGLE_ANALYTICS', $checkbox_google_analytics);

$template->parse('html/account.html');
?>
