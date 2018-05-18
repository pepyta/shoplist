function trashList(listid) {
    var elem = document.getElementById("list" + listid);

    $.get("req.php?req=trashList;" + listid);
    elem.classList.add("scale-out");
    setTimeout(function () {
        elem.parentNode.removeChild(elem);
    }, 300);

    if (document.getElementById("listContainer").childElementCount == 0) {
        document.getElementById('noList').style = '';
    }
}

function boughtItem(itemid, quantity, listid) {
    //document.getElementById('reqresponse').innerHTML = $.get("req.php?req=boughtItem;" + itemid + ";" + quantity + ";" + listid);

    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=boughtItem;" + itemid + ";" + quantity + ";" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 0) {
                var elem = document.getElementById("item" + itemid);

                elem.classList.add("scale-out");
                setTimeout(function () {
                    elem.parentNode.removeChild(elem);
                }, 300);
            } else {
                document.getElementById("itemToDisplay" + itemid).innerHTML = xhr.responseText;
            }
        }
    }
    xhr.send();
    var itemList = document.getElementById("itemList" + listid);
    if (itemList.childElementCount == 0) {
        itemList.classList.add = 'hidden';
    }
}
