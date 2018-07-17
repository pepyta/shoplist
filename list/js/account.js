function sendToSave(token) {
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;

    $.get("req.php?req=sendPersonalInformations;" + name + ";" + email);
}
