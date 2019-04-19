<?php include 'functions.php';

$sql = "SELECT * FROM lists WHERE owner = '".encrypt($id)."' AND trash = 0";
$result = $conn->query($sql);
if($result->num_rows !== 0){
    $completeLists = "";
    while($list = $result->fetch_assoc()){
        $completeLists = $completeLists." ".renderList($list["id"], true, $list["name"]);
    }
    $template->assign('MY_LISTS_CONTAINER_CONTENT', $completeLists);
} else {
   $template->assign('MY_LISTS_CONTAINER_CONTENT', '
    <div class="empty-cart indigo-text" id="noList">
        <i class="material-icons cart" width="64px">remove_shopping_cart</i>
        <div class="flow-text">Your shopping list is empty</div>
    </div>');
}

$template->parse('html/mylist.html');
?>
