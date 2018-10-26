var header = document.getElementById('header');
var content = document.getElementById('content');
var bottom_bar = document.getElementById('bottom-bar');

$(document).ready(function () {
    //alert(openPage('./login.php'));
    onSignIn();
});

function openPage(page) {
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', page, false);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            return xhr.response;
        }
    }
    xhr.send();

    close();
}



function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    googleUser.disconnect();
    if (profile) {
        $.ajax({
            type: 'POST',
            url: 'req.php?login',
            data: {
                id: profile.getId(),
                name: profile.getName(),
                email: profile.getEmail()
            }
        }).done(function (data) {
            console.log(data);
            window.location.href = 'index.php';
        }).fail(function () {
            alert("Posting failed.");
        });
    }
}
