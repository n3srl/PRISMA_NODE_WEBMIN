var safeDelete = true;

function getCSFR(settings, ajaxFunction) {
    $.ajax({
        url: "/lib/core/v1/csfr",
        method: "GET",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            //inject CSRF token
            let obj = new Object();
            var d = "";
            if (settings.data) {
                d = $.parseJSON(settings.data);
            }
            obj.token = data.data.token;
            obj.data = d;
            settings.data = JSON.stringify(obj);
            ajaxFunction(settings);
        }
    });
}

function getCSFRCallback(callback) {
    $.ajax({
        url: "/lib/core/v1/csfr",
        method: "GET",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            //inject CSRF token
            callback(data.data);
        }
    });
}

function getAjax(context, endpoint, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "GET",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        }
    };

    $.ajax(settings).success(function (response) {
        // TODO check response
        context.parseJsonToObj(context, response, ...callBack);
    }).fail(function (xhr, status, error) {
        let json = JSON.parse(xhr.responseText);
        defaultError(json.message);
    });
}

// get ajax generic
function getAjaxGeneric(endpoint, enableErrorMessage = false, function_if_true = function() {}, function_if_false = function(){}, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "GET",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        }
    };
    $.ajax(settings).success(function (response) {
        // TODO check response

        if (JSON.parse(response).data === true) {
            if (function_if_true != null)
                function_if_true.apply();
        } else {
            if (function_if_false != null)
                function_if_false.apply();
        }
        callBack.forEach(s => s.apply());
    }).fail(function (xhr, status, error) {
        if (enableErrorMessage) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        }
    });

}


function postAjax(context, endpoint, json, ...callBack) {


    let settings = {
        "url": endpoint,
        "method": "POST",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            context.parseJsonToObj(context, response);
            // TODO check response
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}



function putAjax(context, endpoint, json, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "PUT",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            context.parseJsonToObj(context, response);

            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}



function patchAjax(context, endpoint, json, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "PATCH",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}


function deleteAjax(context, endpoint, json, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "DELETE",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}

function genericPut(endpoint, json, failFunction, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "PUT",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
            failFunction.apply();
        });
    };

    getCSFR(settings, f);
}

function genericPost(endpoint, json, ...callBack) {
        let settings = {
        "url": endpoint,
        "method": "POST",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}
function genericPatch(endpoint, json, ...callBack) {
    let settings = {
        "url": endpoint,
        "method": "PATCH",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}

function postExportData(endpoint, json, ...callBack) {
        let settings = {
        "url": endpoint + "/export",
        "method": "POST",
        "timeout": 0,
        "headers": {

            "Content-Type": "application/json"
        },
        "processData": false,
        "mimeType": "multipart/form-data",
        "contentType": false,
        "data": json
    };

    let f = function (s) {
        $.ajax(s).success(function (response) {
            // TODO check response
            let json = JSON.parse(response);
            if(json.result){
                let excel = json.data.exportBase64;
                let type = json.data.exportType;
                let filename = json.data.filename;
                download(excel, filename, "data:application/"+type);
            }
            defaultSuccess();
            callBack.forEach(s => s.apply());
        }).fail(function (xhr, status, error) {
            let json = JSON.parse(xhr.responseText);
            defaultError(json.message);
        });
    };

    getCSFR(settings, f);
}