function deleteList(listid) {
    var elem = document.getElementById("list" + listid);

    $.get("req.php?req=deleteList;" + listid);
    elem.classList.add("scale-out");
    setTimeout(function () {
        elem.parentNode.removeChild(elem);
    }, 300);
}

function restoreList(listid) {
    var elem = document.getElementById("list" + listid);

    $.get("req.php?req=restoreList;" + listid);
    elem.classList.add("scale-out");
    setTimeout(function () {
        elem.parentNode.removeChild(elem);
    }, 300);
}
