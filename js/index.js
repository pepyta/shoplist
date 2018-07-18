function openPage(page) {

    var main = document.getElementById("main");
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', page + '.php ', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            main.innerHTML = xhr.responseText;
            M.AutoInit();
            $.getScript("js/" + page + ".js", function () {});

            fixDropdowns();
        }
    }
    xhr.send();

    close();
}


$(document).ready(function () {
    openPage("mylists");
    $('.modal').modal();
    $('.sidenav').sidenav();
    setTimeout(function () {
        console.clear();
        console.log("Don't try to hack me please 😭");
    }, 200);
});
