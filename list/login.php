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

    
//TRY TO LOGIN
if(isset($_GET['login'])){ 
    $sql = "SELECT * FROM users WHERE name = '".$_POST['name']."' AND pass  = '".hash('sha256', $_POST['pass'])."'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows > 0) { 
        $ssid = md5(time());
        $sql = "UPDATE users SET ssid='".$ssid."' WHERE name='".$_POST['name']."'";
        setcookie("name", $_POST['name']);
        setcookie("ssid", $ssid);
        if ($conn->query($sql) === TRUE) {
            setcookie("logininfo", 1);
            Header("Location:index.php");
        } else {
            echo "Error while logging in: " . $conn->error;
        }
    } else {
        setcookie("logininfo", 2);
        Header("Location:login.php");
    }
}



if(isset($_GET['register'])){
    $sql = "SELECT * FROM users WHERE name='".$_POST['name']."'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $ssid = md5(time());
        $sql = "INSERT INTO users (name, email, pass, ssid)
        VALUES ('".$_POST['name']."', '".$_POST['email']."', '".hash('sha256', $_POST['pass'])."', '".$ssid."')";

        if ($conn->query($sql) === TRUE) {
            setcookie("name", $_POST['name']);
            setcookie("ssid", $ssid);
            setcookie("registerinfo", 3);
            Header("Location:index.php");
        } else {
            setcookie("registerinfo", 2);
            Header("Location:login.php");
        }
    } else {
        setcookie("registerinfo", 1);
        Header("Location:login.php");
    }
}

if(isset($_COOKIE['logininfo'])){
    if($_COOKIE['logininfo'] == 1){
        setcookie("logininfo", "");
    } elseif($_COOKIE['logininfo'] == 2){
        echo '
        <div id="logininfo" class="modal">
            <div class="modal-content">
              <h4>Failed to login</h4>
              <p>Bad username or password.</p>
            </div>
          </div>
          '; 
        setcookie("logininfo", "");
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
              <h4>There were some errors</h4>
              <p>Try again later!</p>
            </div>
          </div>
          '; 
        setcookie("registerinfo", "");
    }elseif($_COOKIE['registerinfo'] == 2){
        echo '
        <div id="logininfo" class="modal">
            <div class="modal-content">
              <h4>Successful registration</h4>
              <p>Now you have a ShopList.ml account!</p>
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
            <div class="grey lighten-4 row box middle " style="padding: 10px; ">

                <!-- Login -->
                <form class="col s12 m6 l6 " method="post" action="?login " id="login " style="display:none; ">
                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='text' name='name' id='username' />
                            <label for='username'>Username</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='password' name='pass' id='password' />
                            <label for='password'>Password</label>
                        </div>
                    </div>

                    <br />
                    <center>
                        <div class='row'>
                            <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect waves-light indigo darken-2'>Login</button>
                        </div>
                    </center>
                </form>

                <!-- Register -->
                <form class="col s12 m6 l6 " method="post" action="?register " id="register " style="display:none; ">
                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='text' name='name' id='username' />
                            <label for='username'>Username</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='password' name='pass' id='password' />
                            <label for='password'>Password</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='password' name='pass2' id='password2' />
                            <label for='password2'>Password again</label>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='input-field col s12'>
                            <input type='email' name='email' id='email' />
                            <label for='email'>Email</label>
                        </div>
                    </div>
                    <br />
                    <center>
                        <div class='row'>
                            <button type='submit' name='btn_register' class='col s12 btn btn-large waves-effect waves-light indigo darken-2'>Register</button>
                        </div>
                    </center>
                </form>

                <!-- Default -->
                <div class="col s12 l6 m6 flow-text center-align " id="openlogin ">
                    <h4>Login</h4>If you already have an account on Shoplist.ml you can log in.<br>
                    <button class="btn btn-large indigo darken-2 waves-effect waves-light " onclick="openLogin(); ">Login</button>
                </div>
                <div class="col s12 l6 m6 flow-text center-align " id="openregister ">
                    <h4>Register</h4>If you don't have an account you must register to use our service.<br>
                    <button class="btn btn-large indigo darken-2 waves-effect waves-light " onclick="openRegister(); ">Register</button>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <?php initalizeScripts(); ?>
        <script type=" text/javascript ">
            function openLogin() {
                document.getElementById("openlogin ").style = "display:none ";
                document.getElementById("login ").style = " ";
                document.getElementById("register ").style = "display:none ";
                document.getElementById("openregister ").style = " ";
            }

            function openRegister() {
                document.getElementById("openregister ").style = "display:none ";
                document.getElementById("register ").style = " ";
                document.getElementById("login ").style = "display:none ";
                document.getElementById("openlogin ").style = " ";
            }

        </script>
