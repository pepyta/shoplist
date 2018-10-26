function trashList(listid) {
    var elem = document.getElementById("list" + listid);

    elem.classList.add("scale-out");

    setTimeout(function () {
        elem.parentNode.removeChild(elem);
        if (xhr.responseText == 0) {
            openPage("mylists");
        }
    }, 300);
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=trashList;" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 0) {
                openPage("mylists");
            }
        }
    }
    xhr.send();

}

$(function () {
    $('.addItemForm').on('submit', function (e) {
        $.ajax({
            type: 'post',
            url: 'req.php?itemadd',
            data: $(this).serialize(),
            success: function (data) {
                var newList = data.replace(data.split(";")[0] + ";", "");
                document.getElementById("item_list" + data.split(";")[0]).innerHTML = newList;
                M.AutoInit();
                fixDropdowns();
                grecaptcha.reset();
            }
        });
        e.preventDefault();
    });
});

function boughtItem(itemid, quantity, listid) {
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
                setTimeout(function () {
                    var itemList = document.getElementById("itemList" + listid);
                    if (itemList.childElementCount == 0) {
                        itemList.classList.add('hide');
                        console.log(itemList.classList);
                    }
                }, 310);
            } else if (xhr.responseText == 1) {
                var ul = document.getElementById("dropdown" + itemid);
                var liToKill = ul.childNodes[2];
                liToKill.parentNode.removeChild(liToKill);

                ul.innerHTML = '<li tabindex="0"><a onclick="boughtItem(' + itemid + ',1,' + listid + ');">Bought</a></li>';


                document.getElementById("itemToDisplay" + itemid).innerHTML = 1;
            } else {
                document.getElementById("itemToDisplay" + itemid).innerHTML = xhr.responseText;
            }
        }
    }

    xhr.send();
}
