<?php
include 'functions.php';

if(!isUserLoggedIn($_SESSION['name'], $_SESSION['ssid'])){
    redirect("login.php");
}
?>
    <div class="row">
        <?php
            $sql = "SELECT * FROM lists WHERE ownerid = '".encrypt($id)."' AND thrash = 1";
            $result = $conn->query($sql);
            if($result->num_rows !== 0){
                while($list = $result->fetch_assoc()){
                    renderList($list["id"], false);
                }
            } else {
            echo '
            <div class="empty-cart">
                <i class="material-icons cart" width="64px">delete_forever</i>
                <div class="flow-text">Your trash bin is empty</div>
            </div>
            ';
            }
            ?>

    </div>
