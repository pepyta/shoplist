<?php 
require_once 'settings.php';
require_once 'template.php';
session_start();

$id = getDataOfUser($_SESSION['gid'], "id");

$template = new Template;
$template->assign('FILENAME', basename($_SERVER['PHP_SELF'], '.php'));
$template->assign('NAME', $_SESSION['name']);
$template->assign('GOOGLE_ANALYTICS', renderGA());
include 'lang/en.php';



function encrypt($target){
    global $salt;
    $result = hash('sha256', $salt."".$target."".$salt);
    return $result;
}

function redirect($target){
    header('Location: '.$target, true, 302);
    exit;
}

function isUserLoggedIn($gid, $ssid){
    global $conn;
    $sql = "SELECT * FROM users WHERE google_id = '".$gid."' AND ssid  = '".$ssid."'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows == 1) { 
          return true;
    } else {
        return false;
    }
}

function loginUser($name, $email, $id){
    global $conn;
    
    $ssid = encrypt(md5(time()));
	
    $sql = "SELECT * FROM users WHERE google_id='".$_POST["id"]."'";
	$result = $conn->query($sql);
	if(!empty($result->fetch_assoc())){
		$sql2 = "UPDATE users SET ssid = '".$ssid."' WHERE google_id='".$_POST["id"]."'";
	}else{
		$sql2 = "INSERT INTO users (google_id, ssid) VALUES ('".$_POST["id"]."', '".$ssid."')";
    }
    
    $_SESSION['ssid'] = $ssid;
    $_SESSION['gid'] = $id;
    $_SESSION['name'] = $name;

	$conn->query($sql2);
    
    return true;
}

function getDataOfUser($gid, $target){
    global $conn;
    if($target == "id" || $target == "tutorialComplete" || $target == "name" || $target == "nick" || $target = "google_analytics"){
        $sql = "SELECT * FROM users WHERE google_id='".$gid."'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                $user = $row;

                return $user[$target];
            }
        }
    } else {
        return false;
    }
}

function sendPersonalInformations($name, $email){
    if(!empty($name) && !empty($email)){
        $sql2 = "UPDATE users SET name = '$name' AND email = '$email' WHERE id=$id";
        if ($conn->query($sql2) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}

function isThisListBelongsToUser($itemid){
    global $conn;
    global $id;
    $sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND id = '" . $itemid . "'";
    $result = $conn->query($sql);
    if ($result->num_rows >> 0) {
        return true;
    } else{
        return false;
    }
}

function isThisItemBelongsToUser($itemid, $listid){
    global $conn;
    $sql    = "SELECT * FROM items WHERE id = '" . $itemid . "' AND inListById = '" . encrypt($listid) . "'";
    $result = $conn->query($sql);
    if ($result->num_rows >= 1) {
        if(isThisListBelongsToUser($listid)){
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}
    
function createList($name){
    global $conn;
    global $id;
    $sql    = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND name = '" . $name . "'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO lists (name, owner) VALUES ('" . $name . "', '" . encrypt($id) . "')";
        if ($conn->query($sql) === TRUE) {
            $sql2 = "UPDATE users SET tutorialComplete = 1 WHERE id=$id";
            if ($conn->query($sql2) === TRUE) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}
function trashList($listid){
    global $conn;
    global $id;
    if(isThisListBelongsToUser($listid)){
        $sql = "UPDATE lists SET trash = 1 WHERE owner='" . encrypt($id) . "' AND id = '" . $listid . "' ";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND trash = 0";
            $result = $conn->query($sql);
            echo $result->num_rows;
        } else {
            return false;
        }
    }
}

function deleteList($listid){
    global $conn;
    global $id;
    if(isThisListBelongsToUser($listid)){
        $sql = "DELETE FROM lists WHERE id=$listid";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND trash = 1";
            $result = $conn->query($sql);
            echo $result->num_rows;
        } else {
            return false;
        }
    }
}

function restoreList($listid){
    global $conn;
    global $id;
    if(isThisListBelongsToUser($listid)){
        $sql = "UPDATE lists SET trash = 0 WHERE owner='" . encrypt($id) . "' AND id = '" . $listid . "' ";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT * FROM lists WHERE owner='" . encrypt($id) . "' AND trash = 1";
            $result = $conn->query($sql);
            echo $result->num_rows;
        } else {
            return false;
        }
    }
}


function boughtItem($itemid, $quantity, $listid){
    global $conn;
    if(isset($quantity)) {
        if(isThisItemBelongsToUser($itemid, $listid)){
            if ($quantity == 0) {
                $bought = "quantity";
            } else {
                $bought = "bought+".$quantity;
            }
            $sql = "UPDATE items SET bought = $bought WHERE id = $itemid";

            if ($conn->query($sql) === TRUE) {

                $sql = "SELECT * FROM items WHERE id=$itemid";
                $result = $conn->query($sql);

                while($item = $result->fetch_assoc()) {
                    echo $item["quantity"]-$item["bought"];
                }

            } else {
                echo "Something went wrong :/";
            }
        }
    }
}

function addItem($name, $quantity, $listid){
    global $conn;
    if($name !== '' && $quantity > 0){
        if(isThisListBelongsToUser($listid)){
            $sql    = "SELECT * FROM items WHERE name = '" . $name . "' AND inListById = '" . encrypt($listid) . "'";
            $result = $conn->query($sql);
            if ($result->num_rows == 1) {
                $sql = "UPDATE items SET quantity = quantity+" . $quantity . " WHERE inListById='" . encrypt($listid) . "' AND name = '" . $name . "'";
                if ($conn->query($sql) === TRUE) {
                    echo $listid.";".renderItems($listid);
                } else {
                    return false;
                }
            } else {
                $sql = "INSERT INTO items (name, quantity, inListById)
                        VALUES ('" . $name . "', '" . $quantity . "', '" . encrypt($listid) . "')";
                
                if ($conn->query($sql) === TRUE) {
                    echo $listid.";".renderItems($listid);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function renderList($id, $additem){
    global $list;
    global $conn;
    global $template;
    
    if($additem){
        $fabs = "<a class=\"btn-floating halfway-fab waves-effect waves-light indigo darken-2\" onclick=\"trashList($id);\"><i class=\"material-icons\">delete</i></a> "; 
        
        $item_adder = '            
            <ul class="collection">
                <li class="collection-item">


                    <form method="POST" class="addItemForm" action="?additem">
                        <div class="input-field col s6">
                            <input id="name'.$id.'" name="name" type="text" required autocomplete="off">
                            <label for="name'.$id.'">Item name</label>
                        </div>
                        <div class="input-field col s3">
                            <input id="quantity'.$id.'" placeholder="Quantity" name="quantity" type="number" value="1" min="1" required>

                        </div>
                        <div class="input-field col s3">
                            <button name="add" class="btn col s12 waves-effect  waves-light indigo "><i class="material-icons">add</i></button>
                        </div>
                        <input type="hidden" value="'.$id.'" name="listid">
                    </form>

                </li>
            </ul>';
    } else { 
        $fabs = "<a class=\"btn-floating halfway-fab waves-effect waves-light indigo darken-2\" onclick=\"deleteList($id);\"><i class=\"material-icons\" id=\"delete\">delete</i></a>
        <a class=\"btn-floating halfway-fab waves-effect waves-light indigo darken-2 left\" onclick=\"restoreList($id);\"><i class=\"material-icons\">undo</i></a>";
        $item_adder = '';
    } 
    
    return "
    <div class=\"col s12 m4 scale-transition\" id=\"list$id\">
        <div class=\"card\">
            <div class=\"card-image indigo\" style=\"min-height:80px\">
                <span class=\"card-title truncate\">".$list['name']."</span> $fabs
            </div>
            <div class=\"card-content\">
                $item_adder
                <div id=\"item_list$id\">".renderItems($id)."</div>
            </div>
        </div>
    </div>
    ";
}

function renderItems($listid){
    global $conn;
    $return = '';
    $sql = "SELECT * FROM items WHERE inListById = '".encrypt($listid)."' AND quantity-bought > 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $return = $return.'<ul class="collection" id="itemList'.$listid.'">';
        while($item = $result->fetch_assoc()) {
            $return = $return.''.renderItem($item, $listid, $item['quantity'], $item['bought']);
        }
        $return = $return.'</ul>';
    }
    return $return;
}

function renderItem($item, $listid){
    $toDisplay = $item['quantity']-$item['bought'];
    
    $item = '
    <li class="collection-item scale-transition" id="item'.$item["id"].'">
        <span class="truncate">
            '.$item["name"].'
        </span>
        <span class="right">
                <a class="dropdown-trigger" href="#" data-target="dropdown'.$item["id"].'"><i class="material-icons">more_vert</i></a>
                <ul id="dropdown'.$item["id"].'" class="dropdown-content">'.renderDropdown($item, $listid).'</ul>
            </span>
        <span class="right" id="itemToDisplay'.$item["id"].'">
            '.$toDisplay.'
        </span>
    </li>
    ';
    
    return $item;
}

function renderDropdown($item, $listid){
    if($item["quantity"]-$item["bought"] > 1){
        $dropdown = '
            <li><a onclick="boughtItem('.$item["id"].',1,'.$listid.');">Bought one</a></li>
            <li><a onclick="boughtItem('.$item["id"].',0,'.$listid.');">Bought all</a></li>
        ';
    } else {
        $dropdown = '
            <li><a onclick="boughtItem('.$item["id"].',1,'.$listid.');">Bought</a></li>
        ';
    }
    
    return $dropdown;
    
}


function changePrivacySettings($cb){
    global $conn;
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        $sql = "UPDATE users SET google_analytics='$cb' WHERE name='".$_SESSION['name']."'";

        if ($conn->query($sql) === TRUE) {
            echo '1';
        } else {
            echo '0';
        }
        
    }
}

function renderGA(){
    if(getDataOfUser($_SESSION['gid'], 'google_analytics') == 'on'){
        $ga = "
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src='https://www.googletagmanager.com/gtag/js?id=UA-120924669-1'></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'UA-120924669-1');

        </script>
        ";
    } else {
        $ga = "";
    }
    return $ga;
}
?>
