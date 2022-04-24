/**
 *
 * @author: N3 S.r.l.
 */

$(setDetectionVisibility());
var inafdetection = new DetectionModel('V2');
var lastEditId = '';
var indexToShow = null;
var latitude = 0;
var longitude = 0;

function setIndexToShow() {
    indexToShow = inafdetection.id;
}

function preview(value) {
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("Detection del " + getFileDate(value) + " (" + getFileHour(value) + ")");
    var body = '<img class="img-responsive" src="/lib/detection/V2/detection/preview/' + value + '"/>';
    $('#detection-preview-modal-body').html(body);
}

function showMask(value) {
    $('#mask-preview-modal').modal('show');
}

function getFileDate(file) {
    var info = file.split("_");
    return info[1].substring(0, 4) + "-" + info[1].substring(4, 6) + "-" + info[1].substring(6, 8);

}

function getFileHour(file) {
    var info = file.split("_");
    return info[1].substring(9, 11) + ":" + info[1].substring(11, 13) + ":" + info[1].substring(13);
}

function geMap(value) {
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("GeMap " + value);
    var body = '<img class="img-responsive" src="/lib/detection/V2/detection/gemap/' + value + '"/>';
    $('#detection-preview-modal-body').html(body);
}

function dirMap(value) {
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("DirMap " + value);
    var body = '<img class="img-responsive" src="/lib/detection/V2/detection/dirmap/' + value + '"/>';
    $('#detection-preview-modal-body').html(body);
}

function download(value) {

    defaultSuccess("Il tuo download inizierà tra qualche minuto");
    $.ajax({
        url: "/lib/detection/V2/detection/createzip/" + value,
        async: false,
        success: function (data) {
            window.location.href = "/lib/detection/V2/detection/download/" + data;
        }
    });
}

function initMap() {
    const station = {lat: latitude, lng: longitude};
    const map = new google.maps.Map(document.getElementById("station-map"), {
        zoom: 6,
        center: station
    });
    const marker = new google.maps.Marker({
        position: station,
        map: map
    });
}

function loadStationInfoValues() {
    var keys = ["Station Name", "Station Code", "Observer", "Elevation Observatory", "Longitude Observatory", "Latitude Observatory"];
    keys.forEach(key => {
        var k = "";
        switch (key) {
            case "Station Name":
                k = "STATION_NAME";
                break;
            case "Station Code":
                k = "STATION_CODE";
                break;
            case "Observer":
                k = "OBSERVER";
                break;
            case "Longitude Observatory":
                k = "SITELONG";
                break;
            case "Latitude Observatory":
                k = "SITELAT";
                break;
            case "Elevation Observatory":
                k = "SITEELEV";
                break;
        }
        $.get("/lib/ft/V2/freeturefinal/id/" + k, function (json1) {
            var id = JSON.parse(json1).data;
            $.get("/lib/ft/V2/freeturefinal/" + id, function (json2) {
                var value = JSON.parse(json2).data.value;
                var row = "<tr><td>" + key + "</td><td>" + value + "</td></tr>";
                $('#StationInfoBody').append(row);
            });
        });
    });

}

function initDetectionsDatatable(folder) {

    var groupColumn = 1;
    table = $('#DetectionList').DataTable({
        "oLanguage": {
            "sZeroRecords": "Nessun risultato",
            "sSearch": "Cerca:",
            "oPaginate": {
                "sPrevious": "Indietro",
                "sNext": "Avanti"
            },
            "sInfo": "Mostra pagina _PAGE_ di _PAGES_",
            "sInfoFiltered": "",
            "sInfoEmpty": "Mostra pagina 0 di 0 elementi",
            "sEmptyTable": "Nessun risultato",
            "sLengthMenu": "Mostra _MENU_ elementi"
        },

        columnDefs: [
            {
                "targets": "_all",
                "orderable": false
            },
            {"width": "10%",
                "className": "dt-center",
                "targets": [-1, -2, -3, -4]
            },
            {
                "targets": [-4],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<button class='btn btn-success' value='" + data + "' onclick= 'preview(this.value)'><i class='fa fa-file'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-3],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<button class='btn btn-success' value='" + data + "' onclick= 'dirMap(this.value)'><i class='fa fa-map'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<button class='btn btn-success' value='" + data + "' onclick= 'geMap(this.value)'><i class='fa fa-globe'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<button class='btn btn-success' value='" + data + "' onclick= 'download(this.value)'><i class='fa fa-download'></i></button>" +
                            "</center>";
                }
            },
            {
                "visible": false,
                "targets": groupColumn
            }
        ],

        responsive: true,
        dom: 'lfrt<t>ip',

        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            aoData.push({"name": "dayDir", "value": folder});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});
            if ($("." + $.md5('name')).is(":visible"))
                aoData.push({"name": "name", "value": $('#F_name').val()});
            if ($("." + $.md5('date')).is(":visible"))
                aoData.push({"name": "date", "value": $('#F_date').val()});
            if ($("." + $.md5('hour')).is(":visible"))
                aoData.push({"name": "hour", "value": $('#F_hour').val()});
        },
        rowGroup: {
            startRender: function (rows, group) {
                var info = group.split(":");
                return $('<tr class="group" style="background-color:#C6CAD4;">')
                        .append('<td colspan="5">' + info[0] + '</td>')
                        .append('<td><center>' + info[1] + '</center></td>')
            },
            endRender: null,
            dataSrc: groupColumn
        },

        "order": [[groupColumn, 'desc']],
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        "lengthMenu": [10, 25, 50],
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/detection/V2/detection/datatable/filelist',
        "paging": true,
        "bLengthChange": false,
        "ordering": true,
        "info": true,
        "searching": false
    });

}

$(document).ready(function () {
    $.get("/lib/ft/V2/freeturefinal/id/ACQ_REGULAR_PRFX", function (json1) {
        var id = JSON.parse(json1).data;
        $.get("/lib/ft/V2/freeturefinal/" + id, function (json2) {
            var prefix = JSON.parse(json2).data.value;
            var dateObj = new Date();
            var month = dateObj.getUTCMonth() + 1;
            var day = dateObj.getUTCDate();
            var year = dateObj.getUTCFullYear();
            if (day < 10)
                day = '0' + day;
            if (month < 10)
                month = '0' + month;
            var folder = prefix + "_" + year + month + day;
            initDetectionsDatatable(folder);
        });
    });

    $.get("/lib/detection/V2/detection/counter/all", function (json) {
        var data = JSON.parse(json).data;
        $("#all-detections-number").html(data);
    });
    $.get("/lib/detection/V2/detection/counter/lastday", function (json) {
        var data = JSON.parse(json).data;
        $("#day-detections-number").html(data);
    });
    $.get("/lib/detection/V2/detection/counter/lastmonth", function (json) {
        var data = JSON.parse(json).data;
        $("#month-detections-number").html(data);
    });
    $.get("/lib/ft/V2/freeturefinal/id/SITELAT", function (json1) {
        var id1 = JSON.parse(json1).data;
        $.get("/lib/ft/V2/freeturefinal/" + id1, function (json2) {
            var lat = parseInt(JSON.parse(json2).data.value);
            $.get("/lib/ft/V2/freeturefinal/id/SITELONG", function (json3) {
                var id2 = JSON.parse(json3).data;
                $.get("/lib/ft/V2/freeturefinal/" + id2, function (json4) {
                    var lng = parseInt(JSON.parse(json4).data.value);
                    latitude = lat;
                    longitude = lng;
                    initMap();
                });
            });
        });
    });

    loadStationInfoValues();

});



