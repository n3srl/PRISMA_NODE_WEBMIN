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

$(document).ajaxStart(function () {
    //console.log( "Triggered ajaxStart handler." );

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

}).ajaxSend(function () {
    //console.log( "Triggered ajaxSend handler." );
}).ajaxSuccess(function () {
    //console.log( "Triggered ajaxSuccess handler." );
}).ajaxError(function () {
    //console.log( "Triggered ajaxError handler." );
}).ajaxStop(function () {
    //console.log( "Triggered ajaxStop handler." );
    $.unblockUI();
}).ajaxComplete(function () {
    //console.log( "Triggered ajaxComplete handler." );
});