/**
 *
 * @author: N3 S.r.l.
 */

$(setFreetureFinalVisibility());
var freetureObjects = [];
var keys = ["ACQ_REGULAR_PRFX", "DATA_PATH", "STATION_NAME", "STATION_CODE", "TELESCOP", "OBSERVER", "SITEELEV", "SITELONG", "SITELAT"];

$(function () {
    disableStationForm();
});


function editObj() {
    disableStationForm();
}

function allowEditObj() {
    enableStationForm();
}

function saveObj() {
    var f = function () {
        disableStationForm();
    };

    freetureObjects.forEach(ft => {
        switch (ft.key) {
            case "STATION_NAME":
                ft.value = $('#station-name').val().toUpperCase();
                break;
            case "STATION_CODE":
                ft.value = $('#station-code').val().toUpperCase();
                break;
            case "OBSERVER":
                ft.value = $('#observer').val();
                break;
            case "SITELONG":
                ft.value = $('#longitude-observatory').val();
                break;
            case "SITELAT":
                ft.value = $('#latitude-observatory').val();
                break;
            case "SITEELEV":
                ft.value = $('#elevation-observatory').val();
                break;
            case "TELESCOP":
                ft.value = $('#station-name').val().toUpperCase();
                break;
            case "ACQ_REGULAR_PRFX":
                ft.value = $('#station-name').val().toUpperCase();
                break;
            case "DATA_PATH":
                ft.value = "/freeture/" + $('#station-name').val().toUpperCase(); + "/";
                break;
        }
        ft.insert();
    });

    uploadMask();

}

//Caricamento nuova maschera
function uploadMask() {
    if ($("#station-mask-upload").get(0).files.length !== 0) {
        var file = $("#station-mask-upload").files[0];
        if (file)
            var formData = new FormData();
        formData.append("mask", file);

        $.ajax({
            url: "/lib/ft/V2/freeturefinal/editmask",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                //defaultSuccess("Maschera caricata correttamente");
                $("#uploadmaskbtn").attr('disabled', true);
                $('#form-mask').val('');
            }
        });
    }
}


function undoObj() {
    var f = function () {
        editObj();
        loadValues();
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}


function enableStationForm() {

    hideAllForm("");
    $("form#StationForm input").each(function () {
        $(this).addClass("input-enabled");
        $(this).removeClass("input-disabled");
        $(this).prop('disabled', false);
    });
    $("#savebtn").show();
    $("#undobtn").show();
    $("#modifybtn").hide();

}

function disableStationForm() {

    hideAllForm("");
    $(".custom-enable-on-new").removeClass("input-enabled");
    $(".custom-enable-on-new").addClass("input-disabled");
    $("form#StationForm input").each(function () {
        $(this).addClass("input-disabled");
        $(this).removeClass("input-enabled");
        $(this).prop('disabled', true);
        validator.unmark($(this)); // Per rimuovere alert precedenti
    });
    $("#savebtn").hide();
    $("#undobtn").hide();
    $("#modifybtn").show();
}



function loadValues() {

    keys.forEach(key => {
        $.get("/lib/ft/V2/freeturefinal/id/" + key, function (json1) {
            var id = JSON.parse(json1).data;
            $.get("/lib/ft/V2/freeturefinal/" + id, function (json2) {
                var obj = JSON.parse(json2).data;
                var ft = new FreetureFinalModel('V2');
                ft.id = obj.id;
                ft.key = obj.key;
                ft.value = obj.value;
                ft.description = obj.description;
                freetureObjects.push(ft);
                switch (key) {
                    case "STATION_NAME":
                        $('#station-name').val(obj.value);
                        break;
                    case "STATION_CODE":
                        $('#station-code').val(obj.value);
                        break;
                    case "OBSERVER":
                        $('#observer').val(obj.value);
                        break;
                    case "SITELONG":
                        $('#longitude-observatory').val(obj.value);
                        break;
                    case "SITELAT":
                        $('#latitude-observatory').val(obj.value);
                        break;
                    case "SITEELEV":
                        $('#elevation-observatory').val(obj.value);
                        break;
                }
            });

        });
    });
}

$('#StationForm').submit(function (e) {
        e.preventDefault();
        var submit = true;

        if (!validator.checkAll($(this))) {
            submit = false;
        }
        if (submit) {

            if ($(this).attr("callback") !== undefined) {
                window[$(this).attr("callback")]();
            } else {
                saveObj();
            }
        }
        return false;
    });

$(document).ready(function () {

    loadValues();
    /*
    var locationPicker = new locationPicker('map', {
        setCurrentPosition: true, // You can omit this, defaults to true
    }, {
        zoom: 15 // You can set any google map options here, zoom defaults to 15
    });*/
});



