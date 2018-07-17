<?php include 'functions.php';?>

<!-- FAB -->
<div class="fixed-action-btn">
    <a id="add" href="#modal1" class="btn-floating btn-large indigo waves-effect waves-light modal-trigger">
            <i class="large material-icons">add</i>
        </a>
</div>


<div class="row" id="listContainer">
    <?php 
            $sql = "SELECT * FROM lists WHERE ownerid = '".encrypt($id)."' AND thrash = 0";
            $result = $conn->query($sql);
            if($result->num_rows !== 0){
                while($list = $result->fetch_assoc()){
                    renderList($list["id"], true);
                }
            } else {
                echo '
                <div class="empty-cart" id="noList">
                    <i class="material-icons cart" width="64px">remove_shopping_cart</i>
                    <div class="flow-text">Your shopping list is empty</div>
                </div>';
            }
    ?>
</div>
