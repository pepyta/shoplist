function openPage(page, def) {

    var main = document.getElementById("main");
    var box = document.getElementById("box");

    var card = document.getElementById("card");

    if (def == 0) {
        card.classList.remove("scale-in");
        card.classList.add("scale-out");
    }
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', page + '.php ', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {

            M.AutoInit();
            $.getScript("js/" + page + ".js", function () {});
            if (def == 0) {
                setTimeout(function () {
                    card.classList.remove("scale-out");
                    card.classList.add("scale-in");
                    box.innerHTML = xhr.responseText;
                }, 300);
            } else {
                card.innerHTML = xhr.responseText;
            }
        }
    }
    xhr.send();

    close();
}

function renderLogin() {
    var title = document.getElementById("title");
    var link = document.getElementById("link");

    title.innerHTML = "Please, login into your account";
    link.innerHTML = "<a class='white-text' onclick='openPage('register', 0);' href='#'>Create account</a>";
}

function renderRegister() {
    var title = document.getElementById("title");
    var link = document.getElementById("link");

    title.innerHTML = "Please, login into your account";
    link.innerHTML = "<a class='white-text' onclick='openPage('register', 0);' href='#'>Create account</a>";
}
$(document).ready(function () {
    openPage("login", 1);
    $('.modal').modal();
    $('.sidenav').sidenav();
    setTimeout(function () {
        console.clear();
        console.log("Don't try to hack me please 😭");
    }, 200);
});
