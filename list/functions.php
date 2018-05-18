<?php 
include 'settings.php';
function initalizeHead(){ 
    $filename = basename($_SERVER['PHP_SELF'], '.php');
    echo "
    <!DOCTYPE html>
    <!-- I know, HTML and BODY aren't closed. Don't care about it. It works! -->
    <HTML>
    <HEAD>
        <title>
            Shopping List
        </title>
        
        <!-- Meta tags -->
        <meta name='theme-color' content='#3f51b5'>
        <meta charset='UTF-8'>
        <meta name='description' content='Shopping List'>
        <meta name='keywords' content='shopping list, shop, list, shoplist'>
        <meta name='author' content='Gál Péter'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        
        <link rel='shortcut icon' href='img/web_hi_res_512.png'>
        
        <!-- CSS files -->
        <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/css/materialize.min.css'>
        <link rel='stylesheet' href='css/global.css'>
        <link rel='stylesheet' href='css/$filename.css'>

    </HEAD>
    <BODY>
    ";
}

function initalizeScripts(){
    $filename = basename($_SERVER['PHP_SELF'], '.php');
    echo "
    <script src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
    <script src='https://apis.google.com/js/platform.js' async defer></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.1/js/materialize.min.js'></script>
    <script src='js/global.js' type='text/javascript'></script>
    <script src='js/$filename.js' type='text/javascript'></script>
    ";
}

function initalizeNavbar(){
    $filename = basename($_SERVER['PHP_SELF'], '.php');
    
    echo '
  <nav class="indigo hide-on-large-only">
  
    <div class="nav-wrapper">
      <a href="#!" class="brand-logo">Shoplist.ml</a>
      <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="sass.html">Sass</a></li>
        <li><a href="badges.html">Components</a></li>
        <li><a href="collapsible.html">Javascript</a></li>
        <li><a href="mobile.html">Mobile</a></li>
      </ul>
    </div>
  </nav>

  <ul id="slide-out" class="sidenav sidenav-fixed">

        <li class="center no-padding">
            <div class="indigo white-text">
                <div class="row center" style="position:relative;height: 100px;">
                    <span class="flow-text" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">'.ucfirst($_COOKIE['name']).'</span>

                </div>
            </div>
        </li>
    <li><a class="waves-effect" href="index.php"><i class="material-icons left">content_paste</i>My lists</a></li>
    <li><a class="waves-effect" href="trash.php"><i class="material-icons left">delete</i>Thrash</a></li>
    <li><a class="waves-effect" href="about.php"><i class="material-icons left">info_outline</i>About</a></li>
    <li><a class="waves-effect" href="logout.php"><i class="material-icons left">exit_to_app</i>Log out</a></li>
  </ul>

        
    ';
}


function encrypt($target){
    global $salt;
    $result = hash('sha256', $salt."".$target."".$salt);
    return $result;
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

function getDataOfUser($name, $target){
    global $conn;
    if($target == "id" || $target == "tutorialComplete"){
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

$id = getDataOfUser($_COOKIE['name'], "id");
$tutorial = getDataOfUser($_COOKIE['name'], "tutorialComplete");

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
    //THIS QUERY IS ALWAYs 0 idk why
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
            return true;
        } else {
            return false;
        }
    }
}

function deleteList($listid){
    global $conn;
    global $id;
    if(isThisListBelongsToUser($listid)){
        $sql = "DELETE FROM lists WHERE id=$listid ";
        
        if ($conn->query($sql) === TRUE) {
            return true;
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
            return true;
        } else {
            return false;
        }
    }
}


function boughtItem($itemid, $quantity, $listid){
    global $conn;
    if(isset($quantity)) {
        if(isThisItemBelongsToUser($itemid, $listid)){
            if ($quantity == "ALL") {
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
                    return true;
                } else {
                    return false;
                }
            } else {
                $sql = "INSERT INTO items (name, quantity, inListById)
                        VALUES ('" . $name . "', '" . $quantity . "', '" . encrypt($listid) . "')";
                
                if ($conn->query($sql) === TRUE) {
                    return true;
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

?>
