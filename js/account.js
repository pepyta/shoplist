function sendToSave(token) {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;

    $.get("req.php?req=sendPersonalInformations;" + name + ";" + email);
}

$(document).ready(function () {
    $('.scrollspy').scrollSpy();
});

$(function () {
    $('#how_we_should_call_you').on('submit', function (e) {
        $.ajax({
            type: 'post',
            url: 'req.php?nick',
            data: $(this).serialize(),
            success: function (data) {
                if (data == 0) {
                    M.toast({
                        html: 'An error occured',
                        classes: 'custom'
                    });
                } else {
                    M.toast({
                        html: 'Successful update of nickname',
                        classes: 'custom'
                    });
                }
                document.getElementById('nick').innerHTML = data;
            }
        });
        e.preventDefault();
    });
});

function setColor(color) {

    $.ajax({
        type: 'get',
        url: "req.php?req=setColor;" + color,
        data: null,
        success: function (data) {
            location.reload();
        }
    });
    //location.reload();
}


$(function () {
    $('#privacy_settings').on('submit', function (e) {
        $.ajax({
            type: 'post',
            url: 'req.php?privacy_settings',
            data: $(this).serialize(),
            success: function (data) {
                if (data == 1) {
                    M.toast({
                        html: 'Successful update of privacy settings',
                        classes: 'custom'
                    });
                } else {
                    M.toast({
                        html: 'An error occured',
                        classes: 'custom'
                    });
                }
            }
        });
        e.preventDefault();
    });
});
$(function () {
    $('#password_change').on('submit', function (e) {
        var oldPassword = document.getElementById('old_password').value;
        var newPassword = document.getElementById('new_password').value;
        var newPasswordAgain = document.getElementById('new_password_again').value;
        if (newPassword != newPasswordAgain) {
            M.Toast({
                html: 'Your passwords doesn\'t match',
                classes: 'custom'
            });
        } else {
            $.ajax({
                type: 'post',
                url: 'req.php?password_change',
                data: $(this).serialize(),
                success: function (data) {
                    if (data == 1) {
                        M.toast({
                            html: 'Successful password change',
                            classes: 'custom'
                        });
                    } else if (data == 0) {
                        M.toast({
                            html: 'Bad old password',
                            classes: 'custom'
                        });
                    } else {
                        M.toast({
                            html: 'An error occured',
                            classes: 'custom'
                        });
                    }
                }
            });
        }
        e.preventDefault();
    });
});
