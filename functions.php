<?php 
require_once 'settings.php';
require_once 'template.php';
session_start();

$id = getDataOfUser($_SESSION['gid'], "id");

$template = new Template;
$template->assign('FILENAME', basename($_SERVER['PHP_SELF'], '.php'));
$template->assign('NAME', $_SESSION['name']);
$template->assign('COLOR', getUserColor());
$template->assign('COLORHEX', getColorHex(getUserColor()));
include 'lang/'.language() .'.php';

function encrypt($target){
    global $salt;
    $result = hash('sha256', $salt."".$target."".$salt);
    return $result;
}

function redirect($target){
    header('Location: '.$target, true, 302);
    exit;
}

function language(){
    if(isset($_SESSION['language'])){
        $lang = $_SESSION['language'];
    } else {
        $lang = "en";
    }
    return $lang;
}

function toFirstCharUppercase($name){
    return ucfirst($name); 
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
	$gid = encrypt($id);
    
    $sql = "SELECT * FROM users WHERE google_id='".$gid."'";
	$result = $conn->query($sql);
	if(!empty($result->fetch_assoc())){
		$sql2 = "UPDATE users SET ssid = '".$ssid."' WHERE google_id='".$gid."'";
	}else{
		$sql2 = "INSERT INTO users (google_id, ssid) VALUES ('".$gid."', '".$ssid."')";
    }
    
    $_SESSION['ssid'] = $ssid;
    $_SESSION['gid'] = $gid;
    $_SESSION['name'] = $name;

	$conn->query($sql2);
    
    return true;
}

function getDataOfUser($gid, $target){
    global $conn;
    if($target == "id" || $target == "google_id" || $target == "color"){
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

function getColor($color){
    switch($color){
        case 1:
            $color = "indigo";
            break;
        case 2:
            $color = "red";
            break;
        case 3:
            $color = "green";
            break;
        case 4:
            $color = "yellow";
            break;
        case 5:
            $color = "grey";
            break;
        case 6:
            $color = "blue";
            break;
        case 7:
            $color = "light-green";
            break;
        case 8:
            $color = "deep-purple";
            break;
        case 9:
            $color = "brown";
            break;
        default:
            $color = getUserColor();
            break;
    }
    return $color;
}

function getUserColor(){
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        $color = getDataOfUser($_SESSION['gid'], "color");
        return getColor($color);
    }
}

function getUserColorId(){
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        $color = getDataOfUser($_SESSION['gid'], "color");
        return $color;
    }
    return 0;
}

function getColorHex($color){
    switch($color){
        case "indigo":
            $color = "#303f9f";
            break;
        case "red":
            $color = "#d32f2f";
            break;
        case "green":
            $color = "#388e3c";
            break;
        case "yellow":
            $color = "#fbc02d";
            break;
        case "grey":
            $color = "#616161";
            break;
        case "blue":
            $color = "#1976d2";
            break;
        case "light-green":
            $color = "#689f38";
            break;
        case "deep-purple":
            $color = "#4527a0";
            break;
        case "brown":
            $color = "#5d4037";
            break;
        default:
            $color = "#303f9f";
            break;
    }
    return $color;
}

function getListcolor($id){
    global $conn;
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        if(isThisListBelongsToUser($id)){
            $sql = "SELECT color FROM lists WHERE id = '$id'";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                return getColor($row['color']);
            }
        }
    }
}

function setColor($target){
    global $conn;
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        $sql = "UPDATE users SET color = '".$target."' WHERE google_id = '".$_SESSION['gid']."'";
        if($conn->query($sql) === TRUE){
            return $target;
        } else {
            return -1;
        }
    }
    return -2;
}

function deleteMyDatas(){
    global $conn;
    if(isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
        $sql = "DELETE FROM users WHERE google_id = '".$_SESSION['gid']."' AND ssid = '".$_SESSION['ssid']."'";

        if ($conn->query($sql) === TRUE) {
            return true;
        }
    }
    return false;
}

function getIconOfItem($itemid){
    global $conn;
    
    $sql = "SELECT * FROM special_items WHERE id = $itemid";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            if(isset($row['custom_icon']) && $row['custom_icon'] != ""){
                return $row['custom_icon'];
            } else {
                $sql = "SELECT * FROM categories WHERE id = '".$row['category']."'";
                $result = $conn->query($sql);
                
                if($result->num_rows > 0){
                    while($row2 = $result->fetch_assoc()){
                        return $row2['icon'];
                    }
                }
            }
        }
    }
    return "";
}

function getSuggestions(){
    global $conn;
    
    $sql = "SELECT * FROM special_items";
    $result = $conn->query($sql);
    
    $return = array();
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $item = array(
                "name" => toFirstCharUppercase($row['name']),
                "icon" => getIconOfItem($row['id'])
            );
            array_push($return, $item);
        }
    }
    return $return;
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
        $sql = "INSERT INTO lists (name, owner, color) VALUES ('" . $name . "', '" . encrypt($id) . "', '0')";
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    
}

function searchList($name, $match, $limit){
    global $conn;
    global $id;
    if($match == 1){
        $match = '=';
    } elseif($match == 2){
        $match = 'LIKE';
    } else {
        return false;
    }
    $lists = array();
    $sql = "SELECT * FROM lists WHERE owner = '".encrypt($id)."' AND name ".$match." '".$name."' LIMIT $limit";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $list = array(
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "trash" => $row['trash']
                    ); 
            array_push($lists, $list);
        }
    }
    return $lists;
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
            $sql = "SELECT * FROM special_items WHERE LOWER(name) = LOWER('$name')";
            $result = $conn->query($sql);
            
            if($result->num_rows == 1){
                while($row = $result->fetch_assoc()){
                    $item_id = $row['id'];
                    $custom_name = "";
                }
                $sql    = "SELECT * FROM items WHERE item_id = '" . $item_id . "' AND inListById = '" . encrypt($listid) . "'";
                $result = $conn->query($sql);
                
                
                if ($result->num_rows == 1) {
                    $sql = "UPDATE items SET quantity = quantity+" . $quantity . " WHERE inListById='" . encrypt($listid) . "' AND item_id = '" . $item_id . "'";
                } else {
                    $sql = "INSERT INTO items (item_id, quantity, inListById)
                            VALUES ('" . $item_id . "', '" . $quantity . "', '" . encrypt($listid) . "')";
                }
            } else {
                $item_id = 0;
                $custom_name = $name;
                
                $sql    = "SELECT * FROM items WHERE custom_name = '" . $custom_name . "' AND inListById = '" . encrypt($listid) . "'";
                $result = $conn->query($sql);
                
                
                if ($result->num_rows == 1) {
                    $sql = "UPDATE items SET quantity = quantity+" . $quantity . " WHERE inListById='" . encrypt($listid) . "' AND custom_name = '" . $custom_name . "'";

                } else {
                    $sql = "INSERT INTO items (custom_name, quantity, inListById)
                            VALUES ('" . $custom_name . "', '" . $quantity . "', '" . encrypt($listid) . "')";

                }
            }
            
            if ($conn->query($sql) === TRUE) {
                echo $listid.";".renderItems($listid);
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

function getItem($id){
    global $conn;
    
    $sql = "SELECT * FROM items WHERE id = '$id'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $name = "";
            $category = "";
            $icon = "";
            
            if($row['item_id'] == 0){
                $name = $row['custom_name'];
            } else {
                $sql = "SELECT name FROM special_items WHERE id = '".$row['item_id']."'";
                $result = $conn->query($sql);
                
                while($row2 = $result->fetch_assoc()){
                    $name = toFirstCharUppercase($row2['name']);
                    $icon = $row2['custom_icon'];
                    
                    $sql = "SELECT name FROM categories WHERE id = '".$row2['category']."'";
                    $result = $conn->query($sql);
                    
                    while($row3 = $result->fetch_assoc()){
                        $category = $row3['name'];
                        
                        if($icon != ""){
                            $icon = $row3['icon'];
                        }
                    }
                }
            }
            
            return array(
                "id" => $id,
                "name" => $name,
                "category" => $category,
                "icon" => $icon
            );
            
        }
    }
    return false;
}

/*

    RENDER FUNCTIONS
    
*/

function renderList($id, $additem, $name){
    global $conn;
    global $template;
    
    if($additem){
        $fabs = "<a class=\"btn-floating halfway-fab waves-effect waves-light ".getListcolor($id)." darken-4\" onclick=\"trashList($id);\"><i class=\"material-icons\">delete</i></a> "; 
        
        $item_adder = '            
            <ul class="collection">
                <li class="collection-item">
                    <form method="POST" class="addItemForm" action="?additem">
                        <div class="input-field col s6">
                            <input id="name'.$id.'" name="name" type="text" required autocomplete="off" class="autocomplete no-autoinit">
                            <label for="name'.$id.'">Item name</label>
                        </div>
                        <div class="input-field col s3">
                            <input id="quantity'.$id.'" placeholder="Quantity" name="quantity" type="number" value="1" min="1" required>
                        </div>
                        <div class="input-field col s3">
                            <button name="add" class="btn col s12 waves-effect waves-light '.getListcolor($id).' darken-2" "><i class="material-icons">add_shopping_cart</i></button>
                        </div>
                        <input type="hidden" value="'.$id.'" name="listid">
                    </form>
                </li>
            </ul>';
    } else { 
        $fabs = "<a class=\"btn-floating halfway-fab waves-effect waves-light ".getListcolor($id)." darken-4\" onclick=\"deleteList($id);\"><i class=\"material-icons\" id=\"delete\">delete</i></a>
        <a class=\"btn-floating halfway-fab waves-effect waves-light ".getListcolor($id)." darken-4 left\" onclick=\"restoreList($id);\"><i class=\"material-icons\">undo</i></a>";
        $item_adder = '';
    } 
    
    return "
    <div class=\"col s12 m4 scale-transition\" id=\"list$id\">
        <div class=\"card\">
            <div class=\"card-image ".getListcolor($id)." darken-2\" id=\"card-image-$id\" style=\"min-height:80px\">
                <span class=\"card-title truncate\">".$name."</span> $fabs
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
            '.getItem($item["id"])["name"].'
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
            <li><a onclick="boughtItem('.$item["id"].',1,'.$listid.');" style="color: '.getColorHex(getListColor($listid)).' !important"><i class="material-icons">done</i> Bought one</a></li>
            <li><a onclick="boughtItem('.$item["id"].',0,'.$listid.');" style="color: '.getColorHex(getListColor($listid)).' !important"><i class="material-icons">done_all</i> Bought all</a></li>
        ';
    } else {
        $dropdown = '
            <li><a onclick="boughtItem('.$item["id"].',1,'.$listid.');" style="color: '.getColorHex(getListColor($listid)).' !important"><i class="material-icons">done</i> Bought</a></li>
        ';
    }
    
    return $dropdown;
    
}
?>
