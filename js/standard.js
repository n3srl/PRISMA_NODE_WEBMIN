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


/* ==================================================================
 *  BANNER STATO ACQUISIZIONI (capture / stack / detection / scheduled)
 * ================================================================== */

// Legge lo stato camera dal modulo ft (policy temperatura): ritorna al
// callback null se non disponibile, altrimenti un oggetto normalizzato
// {controlEnabled, overheated, connected, temperature, threshold}.
function fetchCameraTemperatureStatus(callback) {
    $.get('/lib/ft/V2/freeturefinal/camera/status', function (json) {
        try {
            var resp = JSON.parse(json);
            var d = (resp && resp.result && resp.data) ? resp.data : null;
            if (!d) {
                callback(null);
                return;
            }
            var ctrl = d.controlEnabled;
            callback({
                controlEnabled: (ctrl === true || String(ctrl).toLowerCase() === 'true'),
                overheated: d.overheated === true,
                connected: d.connected,
                temperature: (d.currentTemperature !== undefined && d.currentTemperature !== null) ? d.currentTemperature : null,
                threshold: (d.runtimeThreshold !== undefined && d.runtimeThreshold !== null) ? d.runtimeThreshold : (d.threshold !== undefined ? d.threshold : null)
            });
        } catch (e) {
            callback(null);
        }
    }).fail(function () {
        callback(null);
    });
}

// Compone il banner di stato di un tipo di acquisizione:
//  - flag di config disabilitato               -> warning disabledMsg
//  - flag abilitato ma camera in overheat      -> danger suspendedMsg (+ temperature)
//  - flag non trovato in configurazione        -> warning unknownMsg
//  - flag abilitato e nessuna sospensione      -> nessun banner
function renderAcquisitionStatusBanner(boxSelector, statusUrl, disabledMsg, suspendedMsg, unknownMsg) {
    $.get(statusUrl, function (json) {
        var resp;
        try {
            resp = JSON.parse(json);
        } catch (e) {
            return;
        }
        var $box = $(boxSelector);
        if (!$box.length || !resp || !resp.result || !resp.data) {
            return;
        }
        var enabled = resp.data.enabled;
        var key = resp.data.key || '';
        if (enabled === false) {
            $box.html('<div class="alert alert-warning" style="margin:0;">' +
                    '<i class="fa fa-exclamation-triangle"></i> <b>' + disabledMsg + '</b> (' + key + ' = false).' +
                    '</div>');
            return;
        }
        if (enabled === null) {
            $box.html('<div class="alert alert-warning" style="margin:0;">' + unknownMsg + '</div>');
            return;
        }
        // Flag abilitato: verifica se la policy temperatura sta sospendendo le acquisizioni.
        fetchCameraTemperatureStatus(function (cam) {
            if (!cam || !cam.overheated) {
                return;
            }
            var detail = '';
            if (cam.temperature !== null) {
                detail = ' (' + _('temperatura attuale') + ' ' + cam.temperature + ' °C' +
                        (cam.threshold !== null ? ', ' + _('soglia') + ' ' + cam.threshold + ' °C' : '') + ')';
            }
            $box.html('<div class="alert alert-danger" style="margin:0;">' +
                    '<i class="fa fa-thermometer-full"></i> <b>' + suspendedMsg + '</b>' + detail + '.' +
                    '</div>');
        });
    });
}
