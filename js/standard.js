function defaultSubmitAjax(e) {
    e.preventDefault();
    var method = $(e.target).closest("form").attr("method");
    var url = $(e.target).closest("form").attr("action");
    var dataForm = getForm(e);
    $.ajax({
        url: "/lib/core/v1/csfr",
        method: "GET",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (data.result) {
                var obj = {};
                obj.token = data.data.token;
                obj.data = dataForm;
                $.ajax({
                    url: url,
                    method: method,
                    type: method,
                    data: JSON.stringify(obj),
                    contentType: "application/json; charset=utf-8",
                    dataType: "JSON",
                    success: function (data) {
                        defaultSuccessAjax(data);
                    },
                    error: function () {
                        errorMessage();
                    }
                });
            }
        },
        error: function () {
            errorMessage();
        }
    });
}

function defaultSubmitAjaxWithReload(e) {
    e.preventDefault();
    var method = $(e.target).closest("form").attr("method");
    var url = $(e.target).closest("form").attr("action");
    var data = getForm(e);
    $.ajax({
        url: url,
        method: method,
        type: method,
        data: data,
        dataType: "JSON",
        success: function (data) {
            defaultSuccessAjax(data);
            location.reload();
        },
        error: function () {
            alert(_("Si è verificato un errore"));
        }
    });
}
var ObjectResult;
function unblockWithCookie() {
    unblock();
    if (Cookies.get(window.location.href) == 1 || typeof ObjID === 'undefined' || (ObjID != undefined && ObjID != 0) || (Cookies.get(window.location.href) != 0 && Cookies.get(window.location.href) != undefined)) {
        location.reload();
    } else if (Cookies.get(window.location.href) == 0 || Cookies.get(window.location.href) == undefined) {
        location.href = window.location.href + "/" + ObjectResult.reloaded_id;
    } else {
        location.reload();
    }
}

function unblock() {
    $('.right_col').unblock();
}

function defaultSuccessAjax(data) {

    if (data.result) {
        ObjectResult = data.data;
        //Mostrare dialog con operazione avvenuta con successo
        setTimeout(function () {
            $('.right_col').block({
                message: '<div class"row spostamiqui" ><div class"col-sm-12"><h6>Operazione avvenuta con successo</h6></div><button type=\'button\' onclick=\'unblockWithCookie()\' class="btn btn-success">OK</button></div>',
                overlayCSS: {backgroundColor: '#fff'},
                centerY: false,
                centerX: false,
                css: {
                    position: 'fixed',
                    margin: 'auto',
                    border: 'none'
                },

            });
        }, 50)



    } else {
        errorMessage();
    }
}

function errorMessage() {
    setTimeout(function () {
        $('.right_col').block({
            message: '<div class"row spostamiqui" ><div class"col-sm-12"><h6>Attenzione! Si è verificato un errore imprevisto</h6></div><button type=\'button\' onclick=\'unblock()\' class="btn btn-danger">OK</button></div>',
            overlayCSS: {backgroundColor: '#fff'},
            centerY: false,
            centerX: false,
            css: {
                position: 'fixed',
                margin: 'auto',
                border: 'none'
            },

        });
    }, 50)
}

function defaultDelete(service) {
    if (!confirm(_("Vuoi proseguire?"))) {
        return;
    }
    $("#" + service).submit();
}
function schedeDelete(service) {
    if (!confirm(_("Vuoi proseguire?"))) {
        return;
    }
    $.ajax({
        url: service,
        dataType: 'JSON',
        success: function (data) {
            drawTables();
        }, error: function (jqXHR, textStatus, errorThrown) {
            alert(_("Si è verificato un errore"));
        }
    });
}

function defaultSubmitCustom(service) {
    $.ajax({
        url: service,
        dataType: 'JSON',
        success: function (data) {
            defaultSuccessAjax(data);
            location.reload();
        }, error: function (jqXHR, textStatus, errorThrown) {
            errorMessage();
        }
    });
}

let minToHm = (m) => {
    let h = Math.floor(m / 60);
    h += (h < 0) ? 1 : 0;
    let m2 = Math.abs(m % 60);
    m2 = (m2 < 10) ? '0' + m2 : m2;
    return (h < 0 ? '' : '+') + (h >= 10 ? '' : (h <= -10 ? '' : '0')) + h + ':' + m2;
}

function getForm(e) {

    var form = $(e.target).closest("form").serializeArray();
    var obj = {}
    $(form).each(function () {
        var name;
        if (this.name.endsWith("[]")) {
            name = this.name.slice(0, -2);
        } else {
            name = this.name;
        }

        var sel = "#" + name;
        if ($(sel).attr("type") == "date" || $(sel).attr("date") == "date") {
            var date = $(sel).daterangepicker("getDate").val().split("/");
            var dateIso = new Date(parseInt(date[2]), parseInt(date[1]) - 1, parseInt(date[0]), 12);
            obj[name] = dateIso.toISOString().substring(0, 19) + minToHm(-(new Date().getTimezoneOffset()));
        } else {
            obj[name] = this.value;
        }
    });
    return obj;
}



function logout() {
    $.ajax({
        type: "GET",
        url: "/lib/core/v1/logout",
        success: function (data) {
            window.location.href = "/";
        },
        error: function () {
            alert("There was an error. Try again please!");
        }
    });
}


Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
function setCheckBox(selector, value) {
    if (value == 0) {
        $(selector).removeAttr("checked");
    } else {
        $(selector).attr("checked", "");
    }
}

