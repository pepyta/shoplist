$(document).ready(function () {
    fixDropdowns();
});

function deleteList(listid) {
    var elem = document.getElementById("list" + listid);
    elem.classList.add("scale-out");
    setTimeout(function () {
        elem.parentNode.removeChild(elem);
    }, 300);
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=deleteList;" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 0) {
                openPage("trash");
            }
        }
    }
    xhr.send();
}

function restoreList(listid) {

    var elem = document.getElementById("list" + listid);
    elem.classList.add("scale-out");
    setTimeout(function () {
        elem.parentNode.removeChild(elem);

    }, 300);
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=restoreList;" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 0) {
                openPage("trash");
            }
        }
    }
    xhr.send();

}
