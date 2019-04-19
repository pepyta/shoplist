<?php
include 'functions.php';

if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    redirect('login.php');
}

//$colors = '<div class="input-field col s12 m6"><select class="icons"><option value="" disabled selected>Choose your option</option>';
$colors = "";
for($i = 1; $i < 10; $i++){
    
    if(getDataOfUser($_SESSION['gid'], "color") == $i){
        $selected = '<i class="material-icons">done</i>';
    } else {
        $selected = '';
    }
    $colors = $colors.'<a class="btn-floating btn-large waves-effect waves-light '.getColor($i).' darken-2" onclick="setColor('.$i.');">
    '.$selected.'
    </a> ';
    
    
    //$colors = $colors.'<option value="" data-icon="images/sample-1.jpg" class="left" onclick="setColor('.$i.');">'.getColor($i).'</option>';
}
//$colors = $colors.'</select><label></label></div>';
$template->assign('COLOR_SELECTION_COLORS', $colors);

$template->assign('MY_GOOGLE_ID', $_SESSION['gid']);
$template->assign('MY_SSID', $_SESSION['ssid']);

$template->parse('html/account.html');
?>
