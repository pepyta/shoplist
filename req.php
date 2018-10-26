<?php
include 'functions.php';
if(isset($_GET['landing'])){
    
} elseif(isset($_GET['itemadd'])){
    additem($_POST['name'], $_POST['quantity'], $_POST['listid']);
} elseif(isset($_GET['nick'])){
    changeNickname($_POST['name']);
} elseif(isset($_GET['privacy_settings'])){
    changePrivacySettings($_POST['checkbox_google_analytics']);
} elseif(isset($_GET['password_change'])){
    changePassword($_POST['old_password'], $_POST['new_password']);
} elseif(isset($_GET['login'])){
    loginUser($_POST["name"], $_POST["email"], $_POST["id"]);
} elseif(isset($_GET['req']) && $_GET['req'] !== ''){
    //Syntax is: req.php?req=function;param1;param2;param3
    $req = explode(';', $_GET['req']);
    
    //Function's name
    $func = $req[0];
    
    //Remove the function's name from $req handler
    unset($req[0]);
    
    $arg1 = $req[1];
    $arg2 = $req[2];
    $arg3 = $req[3];
    
    if($arg1 !== "" && $arg2 == "" && $arg3 == ""){
        $func($arg1);
    } elseif($arg1 !== "" && $arg2 !== "" && $arg3 == ""){
        $func($arg1, $arg2);
    } elseif($arg1 !== "" && $arg2 !== "" && $arg3 !== ""){
        $func($arg1, $arg2, $arg3);
    }
    
    
    
} else {
    die("Error sending request to server.");
}
?>
