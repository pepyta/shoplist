$(document).ready(function () {
    fixDropdowns();
});

function deleteList(listid) {
    var elem = document.getElementById("list" + listid);
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=deleteList;" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            elem.classList.add("scale-out");
            setTimeout(function () {
                elem.parentNode.removeChild(elem);
                if (xhr.responseText == 0) {
                    openPage("trash");
                }
            }, 300);
        }
    }
    xhr.send();
}

function restoreList(listid) {

    var elem = document.getElementById("list" + listid);

    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=restoreList;" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            elem.classList.add("scale-out");
            setTimeout(function () {
                elem.parentNode.removeChild(elem);
                if (xhr.responseText == 0) {
                    openPage("trash");
                }
            }, 300);
        }
    }
    xhr.send();

}
