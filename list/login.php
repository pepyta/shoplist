<?php
include 'functions.php';
initalizeHead();
//AUTO LOGIN W/ COOKIES
if(isUserLoggedIn($_COOKIE['name'], $_COOKIE['ssid'])){
    Header("Location:index.php");
}
    
//TRY TO LOGIN
if(isset($_GET['login'])){ 
    $sql = "SELECT * FROM users WHERE name = '".$_POST['name']."' AND pass  = '".encrypt($_POST['pass'])."'"; 
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
?>
    <div class="row ">
        <div class="row box middle " style="padding: 10px; ">

            <center>

                <h5 class="white-text">Please, login into your account</h5>
                <div class="section"></div>

                <div class="container">
                    <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                        <form class="col s12" method="post" action="?login">
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
                                <label style='float: right;'>
								<a class='indigo-text' href='#!'><b>Forgot Password?</b></a>
							</label>
                            </div>

                            <br />
                            <center>
                                <div class='row'>
                                    <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect indigo'>Login</button>
                                </div>
                            </center>
                        </form>
                    </div>
                </div>
                <a class="white-text" href="register.php">Create account</a>
            </center>

            <!-- Scripts -->
            <?php initalizeScripts(); ?>
