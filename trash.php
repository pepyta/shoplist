<?php
include 'functions.php';

if(!isUserLoggedIn($_SESSION['gid'], $_SESSION['ssid'])){
    redirect("login.php");
}

$sql = "SELECT * FROM lists WHERE owner = '".encrypt($id)."' AND trash = 1";
$result = $conn->query($sql);
if($result->num_rows !== 0){
    $completeLists = "";
    while($list = $result->fetch_assoc()){
        $completeLists = $completeLists." ".renderList($list["id"], false);
    }
    $template->assign('TRASH_CONTAINER_CONTENT', $completeLists);
    
} else {
$template->assign('TRASH_CONTAINER_CONTENT', '
<div class="empty-cart">
    <i class="material-icons cart" width="64px">delete_forever</i>
    <div class="flow-text">Your trash bin is empty</div>
</div>
');
}

$template->parse('html/trash.html');
?>
