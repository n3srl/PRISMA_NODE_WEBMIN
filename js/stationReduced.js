/**
 *
 * @author: N3 S.r.l.
 */

$(setFreetureFinalVisibility());
var freetureObj = new FreetureFinalModel('V2');

$(function () {
    disableStationForm();
});


function editStation() {
    disableStationForm();
}

function allowEditStation() {
    enableStationForm();
}

// For each element to change assign the new value and send a post request
function saveStation() {
    var f = function () {
        disableStationForm();
    };
    freetureObj.value = $('#observer').val();
    freetureObj.insert();
}

// For each field load the actual value and creates the objects
function loadValues() {

    $.get("/lib/ft/V2/freeturefinal/id/OBSERVER", function (json1) {
        var id = JSON.parse(json1).data;
        $.get("/lib/ft/V2/freeturefinal/" + id, function (json2) {
            var obj = JSON.parse(json2).data;
            freetureObj.id = obj.id;
            freetureObj.key = obj.key;
            freetureObj.value = obj.value;
            freetureObj.description = obj.description;
            $('#observer').val(obj.value);
        });
    });
}

function undoStation() {
    var f = function () {
        editStation();
        loadValues();
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

// Enable form fields
function enableStationForm() {

    hideAllForm("");
    $("form#StationForm input").each(function () {
        $(this).addClass("input-enabled");
        $(this).removeClass("input-disabled");
        $(this).prop('disabled', false);
    });
    $("#ftsavebtn").show();
    $("#ftundobtn").show();
    $("#ftmodifybtn").hide();

}

// Disable form fields
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
    $("#ftsavebtn").hide();
    $("#ftundobtn").hide();
    $("#ftmodifybtn").show();
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
            saveStation();
        }
    }
    return false;
});

$(document).ready(function () {
    loadValues();
});






