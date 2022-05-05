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
var isPreviewEnabled = false;
var table = null;

function setIndexToShow() {
    indexToShow = inafdetection.id;
}

$("#enable-detection-preview").on('change', function (event) {
    isPreviewEnabled = $("#enable-detection-preview").is(":checked");
    $('#DetectionList').dataTable().fnDraw();
});

// Show modal with detection preview and timestamp
function preview(row) {
    var data = table.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("Detection del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection dirmap and timestamp
function dirMap(row) {
    var data = table.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("DirMap del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[4] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection gemap and timestamp
function geMap(row) {
    var data = table.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("GeMap del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[5] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Download detection zip
function download(value) {

    defaultSuccess("Il tuo download inizierà tra qualche minuto");
    $.ajax({
        url: "/lib/detection/V2/detection/createzip/" + value,
        success: function (data) {
            window.location.href = "/lib/detection/V2/detection/download/" + data;
        }
    });
}

// Refresh every 5 seconds storage info (cpu, ram, disk)
function storageInfo() {
    $.ajax({
        url: "/lib/ft/V2/freeturefinal/storage/info",
        type: "GET",
        dataType: "json",
        global: false,
        timeout: 5000,
        complete: storageInfo,
        success: function (res) {
            var info = res.data;
            $("#cpu-percentage").attr("aria-valuenow", info[0]);
            $("#ram-percentage").attr("aria-valuenow", info[1]);
            $("#disk-percentage").attr("aria-valuenow", info[2]);
            $("#cpu-percentage").attr("style", "width:" + info[0] + "%");
            $("#ram-percentage").attr("style", "width:" + info[1] + "%");
            $("#disk-percentage").attr("style", "width:" + info[2] + "%");
            $("#cpu-percentage").html(Math.round(info[0]) + "%");
            $("#ram-percentage").html(Math.round(info[1]) + "%");
            $("#disk-percentage").html(Math.round(info[2]) + "%");
        }
    });
}


// Show modal with freeture mask
$("#btn-show-mask").click(function () {
    $('#mask-preview-modal').modal('show');
});

// Create map to locate the station
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

// Create table with station info values
function loadStationInfoValues() {
    var keys = ["Station Name", "Station Code", "Observer", "Elevation Observatory", "Longitude Observatory", "Latitude Observatory"];
    keys.forEach(key => {
        var k = "";
        switch (key) {
            case "Station Name":
                k = "STATION_NAME";
                break;
            case "Station Code":
                k = "ACQ_REGULAR_PRFX";
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
        // First get the id, then use id to get value
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

// Create datatable with detections of last day
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
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick='preview(" + meta.row + ")' ><i class='fa fa-file'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-3],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'dirMap(" + meta.row + ")'><i class='fa fa-map'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'geMap(" + meta.row + ")'><i class='fa fa-globe'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<button class='btn btn-success' onclick= 'download(" + meta.row + ")'><i class='fa fa-download'></i></button>" +
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
            aoData.push({"name": "enablePreview", "value": isPreviewEnabled});
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
                        .append('<td colspan="6">' + info[0] + ' (' + info[1] + ' detection)' + '</td>')
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

    // Get last day folder name to get last day detections
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

    // Get number of all detections
    $.get("/lib/detection/V2/detection/counter/all", function (json) {
        var data = JSON.parse(json).data;
        $("#all-detections-number").html(data);
    });

    // Get number of last month detections
    $.get("/lib/detection/V2/detection/counter/lastmonth", function (json) {
        var data = JSON.parse(json).data;
        $("#month-detections-number").html(data);
    });

    // Get number of last day detections
    $.get("/lib/detection/V2/detection/counter/lastday", function (json) {
        var data = JSON.parse(json).data;
        $("#day-detections-number").html(data);
    });

    // Get station latitude and longitude to create map
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

    // Get last image base64 encoded and its timestamp (last stack)
    $.get("/lib/stack/V2/stack/preview/laststack", function (json) {
        var data = JSON.parse(json).data;
        var info = data[1].split(":");
        $('#last-image-description').html("Stack del " + info[0] + " (" + data[2] + ")");
        $('#last-image-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
    });

    // Set toggle switch unchecked 
    $("#enable-detection-preview").attr("checked", false);

    // Get freeture mask image base64 encoded
    $.get("/lib/ft/V2/freeturefinal/preview/mask", function (json) {
        var data = JSON.parse(json).data;
        if (data) {
            $('#mask-preview-modal-body').html("<img class='img-responsive' src='" + data + "'/>");
            $('#download-mask').attr('href', data);
        } else {
            $('#btn-show-mask').hide();
        }
    });

    loadStationInfoValues();

    storageInfo();



});



