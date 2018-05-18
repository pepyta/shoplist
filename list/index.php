<?php
include 'functions.php';

if(!isUserLoggedIn($_COOKIE['name'], $_COOKIE['ssid'])){
    Header("Location:login.php");
}

if (isset($_GET['add'])) {
	$sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND name = '" . $_POST['name'] . "'";
	$result = $conn->query($sql);
	if ($result->num_rows == 0) {
		$sql = "INSERT INTO lists (name, ownerid)
                    VALUES ('" . $_POST['name'] . "', '" . encrypt($id) . "')";
		if ($conn->query($sql) === TRUE) {
			$sql = "UPDATE users SET tutorialComplete = 1 WHERE name = '" . $_COOKIE['name'] . "'";
			if ($conn->query($sql)) {
				Header("Location:index.php");
			}
		}
		else {
			die("Error: " . $sql . "<br />" . $conn->error);
		}
	}
}

if (isset($_GET['additem'])) {
	if ($name !== "") {
		$listid = $_POST['listid'];
		$sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND id=$listid";
		$result = $conn->query($sql);
		if ($result->num_rows == 1) {
			$sql = "SELECT * FROM items WHERE name = '" . $_POST['name'] . "' AND inListById = '" . encrypt($listid) . "'";
			$result = $conn->query($sql);
			if ($result->num_rows == 1) {
				$sql = "UPDATE items SET quantity = quantity+" . $_POST['quantity'] . " WHERE inListById='" . encrypt($listid) . "' AND name = '" . $_POST['name'] . "'";
				if ($conn->query($sql) === TRUE) {
					Header("Location:index.php");
				}
				else {
					die("Error: " . $sql . "<br />" . $conn->error);
				}
			}
			else {
				$sql = "INSERT INTO items (name, quantity, inListById)
                            VALUES ('" . $_POST['name'] . "', '" . $_POST["quantity"] . "', '" . encrypt($listid) . "')";
				if ($conn->query($sql) === TRUE) {
					Header("Location:index.php");
				}
				else {
					die("Error: " . $sql . "<br />" . $conn->error);
				}
			}
		}
	}
}

initalizeHead();
initalizeNavbar();
?>

    <!-- FAB -->
    <div class="fixed-action-btn">
        <a id="add" href="#modal1" class="btn-floating btn-large indigo waves-effect waves-light modal-trigger">
            <i class="large material-icons">add</i>
        </a>
    </div>


    <!-- Tap Target Structure -->
    <div class="tap-target indigo white-text" data-target="add">
        <div class="tap-target-content">
            <h5>Let's create your first list!</h5>
            <p>It seems like you haven't created any shopping list yet. Let's make your first list now!</p>
        </div>
    </div>

    <div id="modal1" class="modal">
        <form method="POST" name="add" action="?add" id="add">
            <div class="modal-content">
                <div class="flow-text">Create new list</div>
                <div class="input-field col s12">
                    <input id="name" name="name" type="text" required autocomplete="off">
                    <label for="name">Name of the list</label>
                </div>

            </div>
            <div class="modal-footer">
                <a onclick="form.submit();">
                <button class="btn waves-effect waves-light indigo btn-flat white-text">Create</button>
                </a>
            </div>
        </form>
    </div>

    <!-- Main contents -->
    <main>
        <div class="row" id="listContainer">
            <?php 
            $sql = "SELECT * FROM lists WHERE ownerid = '".encrypt($id)."' AND thrash = 0";
            $result = $conn->query($sql);
            if($result->num_rows !== 0){
                while($list = $result->fetch_assoc()){
                    
                    echo '
                    <div class="col s12 m4 scale-transition" id="list'.$list["id"].'">
                    <div class="card">
                        <div class="card-image indigo" style="min-height:80px">
                            <span class="card-title truncate">'.$list['name'].'</span>
                            <a class="btn-floating halfway-fab waves-effect waves-light indigo darken-2" onclick="trashList('.$list["id"].');"><i class="material-icons">delete</i></a>
                        </div>
                        <div class="card-content">
                        <ul class="collection">
                        <li class="collection-item">
                        <form method="POST" action="?additem">
                        <div class="input-field col s6">
                            <input id="name'.$list['id'].'" name="name" type="text" required autocomplete="off" >
                            <label for="name'.$list['id'].'">Item name</label>
                        </div>
                        <div class="input-field col s3">
                            <input id="quantity" name="quantity" type="number" value="1" min="1" required>
                            <label for="quantity">Quantity</label>
                        </div>
                        <div class="input-field col s3">
                            <button name="add" class="btn col s12 waves-effect  waves-light indigo "><i class="material-icons">add</i></button>
                        </div>
                        <input type="hidden" value="'.$list["id"].'" name="listid">
                        </form>
                        </li>
                        </ul>
                            ';
                    
                            $sql2 = "SELECT * FROM items WHERE inListById = '".encrypt($list["id"])."' AND quantity-bought > 0";
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0) {
                                // output data of each row
                                echo '<ul class="collection" id="itemList'.$list["id"].'">';
                                while($row2 = $result2->fetch_assoc()) {
                                    $toDisplay = $row2["quantity"]-$row2["bought"];
                                    echo '
                                    <li class="collection-item scale-transition" id="item'.$row2["id"].'">
                                        <span class="truncate">'.$row2["name"].'</span>
                                        <span class="right">
                                              <a class="dropdown-trigger" href="#" data-target="dropdown'.$row2["id"].'"><i class="material-icons">more_vert</i></a>
                                            
                                              <!-- Dropdown Structure -->
                                              <ul id="dropdown'.$row2["id"].'" class="dropdown-content">
                                                ';
                                    
                                                if($row2["quantity"]-$row2["bought"] > 1){
                                                    echo '
                                                    <li><a onclick="boughtItem('.$row2["id"].',1,'.$list["id"].');">Bought one</a></li>
                                                    <li><a onclick="boughtItem('.$row2["id"].',"ALL",'.$list["id"].');">Bought all</a></li>
                                                    ';
                                                } else {
                                                    echo '<li><a onclick="boughtItem('.$row2["id"].',1,'.$list["id"].');">Bought</a></li>';
                                                }
                                                echo '
                                                <li class="divider" tabindex="-1"></li>
                                                <li><a href="?req=delete;'.$row2['id'].'">Delete</a></li>
                                              </ul>

                                        </span>
                                       <span class="right" id="itemToDisplay'.$row2["id"].'">
                                            '.$toDisplay.'
                                        </span>
                                    </li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<center id="noitem"><span class="flow-text">You have no item in your list!</span></center>';
                            }
                    
                        echo '
                        </div>
                    </div>
                    </div>';
                }
            } else {
                echo '<div class="empty-cart" id="noList">
<i class="material-icons cart" width="64px">remove_shopping_cart</i>
<div class="flow-text">Your shopping list is empty</div>
</div>';
            }
            ?>
        </div>
    </main>
    <!-- Scripts -->
    <?php 
    initalizeScripts(); 

    if($tutorial == 0){
        echo '<script type="text/javascript">$(".tap-target").tapTarget("open");</script>';
    }
?>
