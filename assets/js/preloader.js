$('#status').css({backgroundImage: "url("+base_url+"assets/images/loading.gif?p" + new Date().getTime()+")",     backgroundSize: "contain"});
$(document).ready(function () {
    $('#status').fadeOut();
    $('#preloader').delay(500).fadeOut('slow');
    $('body').delay(500).css({'overflow': 'visible'});
});