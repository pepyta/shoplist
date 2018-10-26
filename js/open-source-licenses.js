function openLicense(license) {
    var client = new XMLHttpRequest();
    client.open('GET', 'licenses/' + license);
    client.onreadystatechange = function () {
        document.getElementById('title').innerHTML = license;
        document.getElementById('content').innerHTML = client.responseText;
        $('.modal').modal('open');

    }
    client.send();
}

$(document).ready(function () {
    $('.modal').modal();
});
