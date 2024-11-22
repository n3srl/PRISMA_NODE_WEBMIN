$('#LoginForm').submit(function (event) {
    // prevent default browser behaviour
    event.preventDefault();
    //var data ={username : $('#username').val() , password : $('#password').val()};

    $.ajax({
        type: "GET",
        url: "/lib/core/v1/login?",
        dataType: "json",
        data: "username=" + $('#username').val() + "&password=" + $('#password').val(),

        success: function (data) {
            if (data.result) {
                window.location.reload();
            } else {
                alert("Autenticazione fallita");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 401) {
                alert(_("Autenticazione fallita"));
            } else {
                alert(_("Attenzione! Si è verificato un errore"));
            }
        }
    });

});
