function onSubmit(token) {
    var name = document.getElementById("name").value;
    var password = document.getElementById("password").value;
    var password2 = document.getElementById("password2").value;
    var email = document.getElementById("email").value;
    var cb1 = document.querySelector('#cb1').checked
    var cb2 = document.querySelector('#cb2').checked
    if (password != password2) {
        M.toast({
            html: 'Your passwords doesn\'t match'
        });
    } else if (cb1 != true) {
        M.toast({
            html: 'Your must be at least 16 years old or at least 13 years old to use this site'
        });
    } else if (cb2 != true) {
        M.toast({
            html: 'You must accept our privacy policy'
        });
    } else {
        var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        xhr.open('get', "req.php?req=registerUser;" + name + ";" + password + ";" + email, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText == 1) {
                    location.reload();
                } else if (xhr.responseText == 0) {
                    M.toast({
                        html: 'A user with this name or email already exists.'
                    });
                } else if (xhr.responseText == -1) {
                    M.toast({
                        html: 'An error occured. Try again later!'
                    });
                }
            }
        }
        xhr.send();
    }
    grecaptcha.reset();
}
