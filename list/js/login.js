function onSubmit(token) {
    var name = document.getElementById("name").value;
    var password = document.getElementById("password").value;

    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=loginUser;" + name + ";" + password, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 1) {
                location.reload();
            } else if (xhr.responseText == 0) {
                M.toast({
                    html: 'Bad username or password'
                });
            } else if (xhr.responseText == -1) {
                M.toast({
                    html: 'An error occured. Try again later!'
                });
            }
        }
    }

    xhr.send();
    grecaptcha.reset();
}

function validateEmailDB() {
    var email = document.getElementById("email").value;
    var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    xhr.open('get', "req.php?req=validateEmailDB;" + email, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == 1) {
                document.getElementById("resetpwcontent").innerHTML = "The reset link is successfully sent to " + email + ".<br>Check your inbox and also your spam folder!";
                document.getElementById("sendbtn").style.display = "none";
                sendmail(email);
            } else {
                document.getElementById("resetpwcontent").innerHTML = "The reset link is can not be sent because you didn't register with that email.";
                document.getElementById("sendbtn").style.display = "none";
            }
        }
    }

    xhr.send();
    return false;
}

function sendmail(email) {
    console.log(email);
    $.get("req.php?req=resetPwSendMail;" + email);
    return false;
}
