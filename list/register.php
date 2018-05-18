<?php
include 'functions.php';
initalizeHead();
//AUTO LOGIN W/ COOKIES
if(isset($_COOKIE['name']) && isset($_COOKIE['ssid'])){
    $sql = "SELECT * FROM users WHERE name = '".$_COOKIE['name']."' AND ssid  = '".$_COOKIE['ssid']."'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows == 1) { 
        $loggedin = TRUE;
    } else {
        $loggedin = FALSE;
    }
} else{
    $loggedin = FALSE;
}

//REDIRECT IF LOGGED IN
if($loggedin == TRUE){
    Header("Location:index.php");
}

    


if(isset($_GET['register'])){
    $sql = "SELECT * FROM users WHERE name='".$_POST['name']."'";
    $result = $conn->query($sql);
    if($_POST['pass'] == $_POST['pass2']){
        if ($result->num_rows == 0) {
            $ssid = md5(time());
            $sql = "INSERT INTO users (name, email, pass, ssid)
            VALUES ('".$_POST['name']."', '".$_POST['email']."', '".encrypt($_POST['pass'])."', '".$ssid."')";

            if ($conn->query($sql) === TRUE) {
                setcookie("name", $_POST['name']);
                setcookie("ssid", $ssid);
                Header("Location:index.php");
            } else {
                setcookie("registerinfo", 3);
                Header("Location:register.php");
            }
        } else {
            setcookie("registerinfo", 1);
            Header("Location:register.php");
        }
    } else {
        setcookie("registerinfo", 2);
        Header("Location:register.php");
    }
}

if(isset($_COOKIE['registerinfo'])){
    if($_COOKIE['registerinfo'] == 1){
        echo '
        <div id="logininfo" class="modal">
            <div class="modal-content">
              <h4>Failed to register</h4>
              <p>Username already taken</p>
            </div>
          </div>
          '; 
        setcookie("registerinfo", "");
    } elseif($_COOKIE['registerinfo'] == 2){
        echo '
        <div id="logininfo" class="modal">
            <div class="modal-content">
              <h4>Falied to register</h4>
              <p>Your password are not the same.</p>
            </div>
          </div>
          '; 
        setcookie("registerinfo", "");
    }elseif($_COOKIE['registerinfo'] == 3){
        echo '
        <div id="logininfo" class="modal">
            <div class="modal-content">
              <h4>There were some errors</h4>
              <p>Try again later!</p>
            </div>
          </div>
          '; 
        setcookie("registerinfo", "");
    }
}

?>

    <!DOCTYPE html>
    <HTML lang="en">
    <!-- Include head -->
    <?php include 'includes/head.php'; ?>

    <BODY>

        <div class="row ">
            <div class="row box middle " style="padding: 10px; ">

                <center>

                    <h5 class="white-text">Let's register an account!</h5>
                    <div class="section"></div>

                    <div class="container">
                        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                            <form class="col s12" method="post" action="?register">
                                <div class='row'>
                                    <div class='col s12'>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='input-field col s12'>
                                        <input type='text' name='name' id='name' required>
                                        <label for='name'>Enter your username</label>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='input-field col s12'>
                                        <input type='password' name='pass' id='password' required>
                                        <label for='password'>Enter your password</label>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='input-field col s12'>
                                        <input type='password' name='pass2' id='pass2' required>
                                        <label for='pass2'>Enter your password again</label>
                                    </div>
                                </div>

                                <div class='row'>
                                    <div class='input-field col s12'>
                                        <input type='email' name='email' id='email' required>
                                        <label for='email'>Enter your email</label>
                                    </div>
                                </div>

                                <br />
                                <center>
                                    <div class='row'>
                                        <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect indigo'>Register</button>
                                    </div>
                                </center>
                            </form>
                        </div>
                    </div>
                    <a class="white-text" href="login.php">I already have an account</a>
                </center>

                <!-- Scripts -->
                <?php initalizeScripts(); ?>
