<?php
include 'functions.php';

if(!isUserLoggedIn($_COOKIE['name'], $_COOKIE['ssid'])){
    Header("Location:login.php");
}

initalizeHead();
initalizeNavbar();
?>

    <!-- Main contents -->
    <main>
        <div class="row">
            <?php
            $sql = "SELECT * FROM lists WHERE ownerid = '".encrypt($id)."' AND thrash = 1";
            $result = $conn->query($sql);
            if($result->num_rows == 0){
            echo '
            <div class="empty-cart">
                <i class="material-icons cart" width="64px">delete_forever</i>
                <div class="flow-text">Your trash bin is empty</div>
            </div>
            ';
            }else{
                while($list = $result->fetch_assoc()){
                    echo '
                    <div class="col s12 m4 scale-transition" id="list'.$list["id"].'">
                        <div class="card">
                            <div class="card-content">
                                <span class="card-title">
                                    <span class="truncate">
                                        '.$list['name'].'
                                    </span>
                                    <a href="#"onclick="deleteList('.$list["id"].');"><i class="material-icons right">delete_forever</i></a>
                                    <a href="#" onclick="restoreList('.$list["id"].');"><i class="material-icons right">undo</i></a>
                                </span>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>

        </div>
    </main>

    <!-- Scripts -->
    <?php initalizeScripts(); ?>
