<?php
include 'functions.php';

//Make sure ?req isn't empty
if(isset($_GET['req']) && $_GET['req'] !== ''){
    //Syntax is: req.php?req=function;param1;param2;param3...
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
