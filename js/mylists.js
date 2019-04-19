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

function getDropdownColor(listid){
    var ddc = document.getElementById("card-image-"+listid).backgroundColor;
    return ddc;
}

function autocomplete_init() {
    $.ajax({
        type: 'GET',
        url: '../req.php?getSuggestions',
        success: function (response) {
            var countryArray = response;
            var dataCountry = {};
            for (var i = 0; i < countryArray.length; i++) {
                //console.log(countryArray[i].name);k
                var icon = "";
                if (countryArray[i].icon != "") {
                    icon = "../img/special_items/" + countryArray[i].icon;
                }
                dataCountry[countryArray[i].name] = icon; //countryArray[i].flag or null
            }
            $('input.autocomplete').autocomplete({
                data: dataCountry,
                limit: 5, // The max amount of results that can be shown at once. Default: Infinity.
            });
        }
    });
}
$(document).ready(function () {
    //Autocomplete
    autocomplete_init();
});

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


$(function () {
    $('#createList').on('submit', function (e) {
        $.ajax({
            type: 'post',
            dataType: 'html',
            url: 'req.php?createlist',
            data: $(this).serialize(),
            success: function (data, textStatus, XMLHttpRequest) {
                var parentDiv = document.getElementById("listContainer");
                if (parentDiv.contains(document.getElementById("noList"))) {
                    document.getElementById("listContainer").innerHTML = "";
                }
                document.getElementById("listContainer").innerHTML += data;
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
            }
        });
        e.preventDefault();
    });
});

function boughtItem(itemid, quantity, listid) {
    var currentQuantity = document.getElementById("itemToDisplay"+itemid).innerHTML;
    if (currentQuantity-1 == 0 || quantity == 0) {
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
    } else if (currentQuantity-1 == 1) {
        var ul = document.getElementById("dropdown" + itemid);
        var liToKill = ul.childNodes[1];
        liToKill.parentNode.removeChild(liToKill);
        
        document.getElementById("dropdown" + itemid).innerHTML = ul.innerHTML.replace("done_all", "done");
        document.getElementById("dropdown" + itemid).innerHTML = ul.innerHTML.replace("Bought all", "Bought");
        
        //ul.color = getDropdownColor(listid);
        //ul.innerHTML = '<li><a onclick="boughtItem('+itemid+',1,'+listid+');"><i class="material-icons">done</i> Bought</a></li>';

        document.getElementById("itemToDisplay" + itemid).innerHTML = 1;
    } else {
        document.getElementById("itemToDisplay" + itemid).innerHTML = currentQuantity-1;
    }
    
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=boughtItem;" + itemid + ";" + quantity + ";" + listid, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Successful item buying!")
        }
    }

    xhr.send();
}
