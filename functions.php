<?php 
require_once 'settings.php';
require_once 'template.php';
session_start();
$template = new Template;
$template->assign('FILENAME', basename($_SERVER['PHP_SELF'], '.php'));
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

function isUserLoggedIn($name, $ssid){
    global $conn;
    $sql = "SELECT * FROM users WHERE name = '".$name."' AND ssid  = '".$ssid."'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows == 1) { 
          return true;
    } else {
        return false;
    }
}

function loginUser($name, $password){
    global $conn;
    $sql = "SELECT * FROM users WHERE name = '$name' AND pass = '".encrypt($password)."'";
    $result = $conn->query($sql);
    
    if($result->num_rows == 1){
        $ssid = encrypt(md5(time()));
        
        $sql = "UPDATE users SET ssid = '$ssid' WHERE name = '$name'";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['name'] = $name;
            $_SESSION['ssid'] = $ssid;
            
            echo "1";
        } else {
            echo "-1";
        }
    } else {
        echo "0";
    }
}

function registerUser($name, $password, $email){
    global $conn;
    $sql = "SELECT * FROM users WHERE name = '$name' OR email = '$email'";
    $result = $conn->query($sql);
    
    if($result->num_rows == 0){
        $ssid = encrypt(md5(time()));
        
        $sql = "INSERT INTO users (name, pass, email, ssid)
        VALUES ('$name', '".encrypt($password)."', '$email', '$ssid')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['name'] = $name;
            $_SESSION['ssid'] = $ssid;
            echo '1';
        } else {
            echo '-1';
        }
    } else {
        echo '0';
    }
}
function getDataOfUser($name, $target){
    global $conn;
    if($target == "id" || $target == "tutorialComplete" || $target == "email" || $target == "name"){
        $sql = "SELECT * FROM users WHERE name='".$name."'";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            $user = $row;
        }
        return $user[$target];
    } else {
        return false;
    }
}

function sendPersonalInformations($name, $email){
    if(!empty($name) && !empty($email)){
        $sql2 = "UPDATE users SET name = '$name' AND email = '$email' WHERE id=$id";
        if ($conn->query($sql2) === TRUE) {
            echo "Saved";
        } else {
            echo "Not saved(?!)";
        }
    }
}

function isThisListBelongsToUser($itemid){
    global $conn;
    global $id;
    $sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND id = '" . $itemid . "'";
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
    $sql    = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND name = '" . $name . "'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO lists (name, ownerid) VALUES ('" . $name . "', '" . encrypt($id) . "')";
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
        $sql = "UPDATE lists SET thrash = 1 WHERE ownerid='" . encrypt($id) . "' AND id = '" . $listid . "' ";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND thrash = 0";
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
            $sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND thrash = 1";
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
        $sql = "UPDATE lists SET thrash = 0 WHERE ownerid='" . encrypt($id) . "' AND id = '" . $listid . "' ";
        
        if ($conn->query($sql) === TRUE) {
            $sql = "SELECT * FROM lists WHERE ownerid='" . encrypt($id) . "' AND thrash = 1";
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
    
    $template->assign("LIST_NAME", $list["name"]);
    $template->assign("LIST_ID", $id);
    $template->assign("LIST_TOP_FABS", $fabs);
    $template->assign("LIST_ADD_ITEM", $item_adder);
    $template->assign("ITEM_LIST", renderItems($id));
    $template->parse("html/list.html");
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
function validateEmailDB($email){
    global $conn;
    
    $sql = "SELECT * FROM users WHERE email = '".$email."'";
    $result = $conn->query($sql);

    echo $result->num_rows;
}
?>
