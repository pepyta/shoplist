$(window).on('load', function () { // makes sure the whole site is loaded 
    $('#status').fadeOut(); // will first fade out the loading animation 
    $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
    $('body').delay(350).css({
        'overflow': 'visible'
    });
    fixReCaptcha();
})

function fixDropdowns() {
    jQuery(document).ready(function () {
        $('.dropdown-content').appendTo("body"); //fix recaptcha positioning to body  
    });
    $.initialize(".dropdown-content", function () {
        $(this).appendTo("body"); //fix recaptcha positioning to body, even if it loads after the page  
    });
}

function fixReCaptcha() {
    jQuery(document).ready(function () {
        $('.grecaptcha-badge').appendTo("body"); //fix recaptcha positioning to body  
    });
    $.initialize(".grecaptcha-badge", function () {
        $(this).appendTo("body"); //fix recaptcha positioning to body, even if it loads after the page  
    });
}