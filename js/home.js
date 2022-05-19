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
var isProcessingZip = false;
var isProcessingVideo = false;
var zipRow = null;
var videoRow = null;
var createZipXhr = null;
var createVideoXhr = null;
var zipName = null;
var zipDownload = false;
var videoName = null;
var videoDownload = false;

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
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("Detection del " + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection dirmap and timestamp
function dirMap(row) {
    var data = table.rows(row).data()[0];
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("DirMap del " + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[4] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection gemap and timestamp
function geMap(row) {
    var data = table.rows(row).data()[0];
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("GeMap del " + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[5] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Create detection zip
function getZip(row) {
    var data = table.rows(row).data()[0];
    defaultSuccess("Il tuo download inizierà tra qualche minuto");
    isProcessingZip = true;
    zipRow = row;
    $('#DetectionList').dataTable().fnDraw();

    createZipXhr = $.ajax({
        url: "/lib/detection/V2/detection/createzip/" + data[6],
        async: true,
        global: false,
        method: 'POST',
        success: function (json) {
            downloadZip(json);
        }
    });
}

// Create detection video
function getVideo(row) {
    var data = table.rows(row).data()[0];
    defaultSuccess("Il download del video inizierà tra qualche minuto");
    isProcessingVideo = true;
    videoRow = row;
    $('#DetectionList').dataTable().fnDraw();

    createVideoXhr = $.ajax({
        url: "/lib/detection/V2/detection/createvideo/" + data[6],
        async: true,
        global: false,
        method: 'POST',
        success: function (json) {
            downloadVideo(json);
        }
    });
}

// Download detection video
function downloadVideo(json) {
    isProcessingVideo = false;
    videoRow = null;
    videoDownload = true;
    videoName = JSON.parse(json).data;
    $('#DetectionList').dataTable().fnDraw();
}

// Download detection zip
function downloadZip(json) {
    isProcessingZip = false;
    zipRow = null;
    zipDownload = true;
    zipName = JSON.parse(json).data;
    $('#DetectionList').dataTable().fnDraw();
}

// Abort detection zip
function cancelZip() {
    createZipXhr.abort();
    isProcessingZip = false;
    zipRow = null;
    $.ajax({
        async: true,
        type: "POST",
        global: false,
        url: "/lib/detection/V2/detection/zip/cancel",
        success: function (json) {
            defaultError("Zip annullato");
        }
    });
    $('#DetectionList').dataTable().fnDraw();
}

// Abort detection video
function cancelVideo() {
    createVideoXhr.abort();
    isProcessingVideo = false;
    videoRow = null;
    $.ajax({
        async: true,
        type: "POST",
        global: false,
        url: "/lib/detection/V2/detection/video/cancel",
        success: function (json) {
            defaultError("Video annullato");
        }
    });
    $('#DetectionList').dataTable().fnDraw();
}

// Show data usage progress bars
function storageInfo() {
    $.ajax({
        url: "/lib/ft/V2/freeturefinal/storage/cores",
        type: "GET",
        dataType: "json",
        success: function (res) {
            var cores = res.data;
            var cpuHtml = "";
            for (let i = 0; i < cores; i++) {
                cpuHtml += '<div class="col-md-12 col-sm-12 col-xs-12">' +
                        '<label>CPU ' + i + '</label>' +
                        '</div>' +
                        '<div class="col-md-12 col-sm-12 col-xs-12">' +
                        '<div class="progress">' +
                        '<div class="progress-bar progress-bar-success" id="cpu' + i + '" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">' +
                        '</div>' +
                        '</div>' +
                        '</div>';
            }
            $("#cores").html(cpuHtml);
        }
    });
    updateDataUsage();
}

// Refresh every 5 seconds storage info (cpu, ram, disk)
function updateDataUsage() {
    setTimeout(function () {
        $.ajax({
            url: "/lib/ft/V2/freeturefinal/storage/info",
            type: "GET",
            dataType: "json",
            global: false,
            timeout: 5000,
            complete: updateDataUsage,
            success: function (res) {
                var info = res.data;
                $("#ram-percentage").attr("aria-valuenow", info[1]);
                $("#disk-percentage").attr("aria-valuenow", info[2]);
                $("#ram-percentage").attr("style", "width:" + info[1] + "%");
                $("#disk-percentage").attr("style", "width:" + info[2] + "%");
                $("#ram-percentage").html(Math.round(info[1]) + "%");
                $("#disk-percentage").html(Math.round(info[2]) + "%");
                var cores = info[0];
                i = 0;
                cores.forEach(core => {
                    $("#cpu" + i).attr("aria-valuenow", core);
                    $("#cpu" + i).attr("style", "width:" + core + "%");
                    $("#cpu" + i).html(Math.round(core) + "%");
                    i++;
                });
            }
        });
    }, 5000);
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
            {
                "width": "10%",
                "className": "dt-center",
                "targets": [-1, -2, -3, -4, -5]
            },
            {
                "targets": [-8],
                render: function (data, type, row, meta) {
                    if (isProcessingZip && meta.row === zipRow) {
                        return "<div class='col-md-12'>" + data + "</div>" + "<div class='col-md-1'><div class='loader'></div></div><div class='col-md-11'> Preparazione zip in corso...</div>";
                    }
                    if (isProcessingVideo && meta.row === videoRow) {
                        return "<div class='col-md-12'>" + data + "</div>" + "<div class='col-md-1'><div class='loader'></div></div><div class='col-md-11'> Preparazione video in corso...</div>";
                    }
                    return data;
                }
            },
            {
                "targets": [-5],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick='preview(" + meta.row + ")' ><i class='fa fa-eye'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-4],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'dirMap(" + meta.row + ")'><i class='fa fa-map-marker'></i></button>" +
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
                            "<button class='btn btn-success' " + disabled + " onclick= 'geMap(" + meta.row + ")'><i class='fa fa-area-chart'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) {
                    if (isProcessingVideo && meta.row === videoRow) {
                        return "<center>" +
                                "<button class='btn btn-danger' onclick='cancelVideo()'><i class='fa fa-close'></i></button>" +
                                "</center>";
                    }
                    var disabled = "";
                    if (isProcessingVideo || isProcessingZip) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'getVideo(" + meta.row + ")'><i class='fa fa-video-camera'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    if (isProcessingZip && meta.row === zipRow) {
                        return "<center>" +
                                "<button class='btn btn-danger' onclick='cancelZip()'><i class='fa fa-close'></i></button>" +
                                "</center>";
                    }
                    var disabled = "";
                    if (isProcessingZip || isProcessingVideo) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'getZip(" + meta.row + ")'><i class='fa fa-download'></i></button>" +
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
                        .append('<td colspan="7">' + info[0] + ' (' + info[1] + ' detection)' + '</td></tr>');
            },
            endRender: null,
            dataSrc: groupColumn
        },
        "drawCallback": function (oSettings) {
            if (zipDownload) {
                window.location.href = "/lib/detection/V2/detection/download/" + zipName;
                zipName = null;
                zipDownload = false;
            }
            if (videoDownload) {
                window.location.href = "/lib/detection/V2/detection/downloadvideo/" + videoName;
                videoName = null;
                videoDownload = false;
            }
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
    }
    );

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
            var lat = Number(JSON.parse(json2).data.value);
            $.get("/lib/ft/V2/freeturefinal/id/SITELONG", function (json3) {
                var id2 = JSON.parse(json3).data;
                $.get("/lib/ft/V2/freeturefinal/" + id2, function (json4) {
                    var lng = Number(JSON.parse(json4).data.value);
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



