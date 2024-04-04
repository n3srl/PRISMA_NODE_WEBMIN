$('body').block({
    message: $('<img src="/img/loading.gif" />'),
    overlayCSS: {backgroundColor: '#fff'},
    css: {
        top: ($(window).height() - 400) / 2 + 'px',
        left: ($(window).width() - 400) / 2 + 'px',
        width: '400px',
        border: 'none'
    }
});
$(document).ready(function () {
    $.unblockUI();
});

$(document).ajaxStart(function (event, xhr, options) {
    //console.log( "Triggered ajaxStart handler." );
    
    
}).ajaxSend(function (event, ajaxSettings) {
    var current = (window.location.href).split("/");
    current = current[3]+"/"+current[4];
    

    if (current != "docker/edit") 
    {
        $('body').block({
            message: $('<img src="/img/loading.gif" />'),
            overlayCSS: {backgroundColor: '#fff'},
            css: {
                top: ($(window).height() - 400) / 2 + 'px',
                left: ($(window).width() - 400) / 2 + 'px',
                width: '400px',
                border: 'none'
            }
        });
    }
    
}).ajaxSuccess(function () {
    $.unblockUI();
}).ajaxError(function () {
    $.unblockUI();
}).ajaxStop(function () {
    $.unblockUI();
}).ajaxComplete(function () {
    $.unblockUI();
});