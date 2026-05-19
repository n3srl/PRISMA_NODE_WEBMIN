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
    defaultSuccess(_("Il tuo download inizierà tra qualche minuto"));
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
    defaultSuccess(_("Il download del video inizierà tra qualche minuto"));
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
            defaultError(_("Video annullato"));
        }
    });
    $('#DetectionList').dataTable().fnDraw();
}

// Refresh storage info (cpu, ram, disk). Single in-flight request; reschedules
// 5s after the previous one completes, so a slow backend never piles up.
function updateDataUsage() {
    $.ajax({
        url: "/lib/ft/V2/freeturefinal/storage/info",
        type: "GET",
        dataType: "json",
        global: false,
        success: function (res) {
            var info = res.data;
            var cpu = Number(info[0]) || 0;
            var ram = Number(info[1]) || 0;
            var disk = Number(info[2]) || 0;

            var setBar = function (id, pct) {
                pct = Math.max(0, Math.min(100, pct));
                $(id).attr("aria-valuenow", pct)
                     .attr("style", "width:" + pct + "%")
                     .html(Math.round(pct) + "%");
            };
            setBar("#cpu-percentage", cpu);
            setBar("#ram-percentage", ram);
            setBar("#disk-percentage", disk);
        },
        complete: function () {
            setTimeout(updateDataUsage, 5000);
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
            "sZeroRecords": _("Nessun risultato"),
            "sSearch": _("Cerca:"),
            "oPaginate": {
                "sPrevious": _("Indietro"),
                "sNext": _("Avanti")
            },
            "sInfo": _("Mostra pagina _PAGE_ di _PAGES_"),
            "sInfoFiltered": "",
            "sInfoEmpty": _("Mostra pagina 0 di 0 elementi"),
            "sEmptyTable": _("Nessun risultato"),
            "sLengthMenu": _("Mostra _MENU_ elementi")
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
                        return "<div class='col-md-12'>" + data + "</div>" + _("<div class='col-md-1'><div class='loader'></div></div><div class='col-md-11'> Preparazione zip in corso...</div>");
                    }
                    if (isProcessingVideo && meta.row === videoRow) {
                        return "<div class='col-md-12'>" + data + "</div>" + _("<div class='col-md-1'><div class='loader'></div></div><div class='col-md-11'> Preparazione video in corso...</div>");
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

    StatusVPN();
    // Get last day folder name to get last day detections
    $.get("/lib/ft/V2/freeturefinal/id/STATION_CODE", function (json1) {
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

    // Get the most recent between the last stack and the last capture, render
    // it. Two endpoints are queried in parallel; whichever has the newer
    // timestamp wins. The data shape from both endpoints is identical:
    //   [filename, "YYYY-MM-DD:N", "HH:MM:SS", base64, id]
    loadLastImage();

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

    updateDataUsage();

    refreshCameraTemperature();
    setInterval(refreshCameraTemperature, 20000);
});

// Last image (stack vs capture) ----------------------------------------------

var lastImageState = { stackDone: false, captureDone: false, stack: null, capture: null };

function loadLastImage() {
    lastImageState.stackDone = false;
    lastImageState.captureDone = false;
    lastImageState.stack = null;
    lastImageState.capture = null;

    $.get('/lib/stack/V2/stack/preview/laststack')
        .done(function (json) { lastImageState.stack = parseLastImageResp(json); })
        .fail(function () { lastImageState.stack = null; })
        .always(function () { lastImageState.stackDone = true; renderLastImage(); });

    $.get('/lib/capture/V2/capture/preview/lastcapture')
        .done(function (json) { lastImageState.capture = parseLastImageResp(json); })
        .fail(function () { lastImageState.capture = null; })
        .always(function () { lastImageState.captureDone = true; renderLastImage(); });
}

function parseLastImageResp(json) {
    try {
        var parsed = (typeof json === 'string') ? JSON.parse(json) : json;
        return parsed && parsed.data ? parsed.data : null;
    } catch (e) { return null; }
}

// Build a sortable "YYYY-MM-DD HH:MM:SS" key from the [_, "day:N", "hour", ...] tuple.
function lastImageTimestamp(data) {
    if (!data || !data[1] || !data[2]) return null;
    var day = String(data[1]).split(':')[0];
    return day + ' ' + data[2];
}

function renderLastImage() {
    if (!lastImageState.stackDone || !lastImageState.captureDone) {
        return; // wait for both
    }
    var sTs = lastImageTimestamp(lastImageState.stack);
    var cTs = lastImageTimestamp(lastImageState.capture);

    var pick = null;
    var label = '';
    if (sTs && cTs) {
        if (sTs >= cTs) { pick = lastImageState.stack;   label = 'Stack'; }
        else            { pick = lastImageState.capture; label = 'Capture'; }
    } else if (sTs) {
        pick = lastImageState.stack;   label = 'Stack';
    } else if (cTs) {
        pick = lastImageState.capture; label = 'Capture';
    }

    if (!pick) {
        $('#last-image-description').html(_('Nessuna immagine disponibile'));
        $('#last-image-preview').html('');
        return;
    }
    var info = String(pick[1]).split(':');
    $('#last-image-description').html(label + ' del ' + info[0] + ' (' + pick[2] + ')');
    $('#last-image-preview').html("<img class='img-responsive' src='" + pick[3] + "'/>");
}

// Camera temperature gauge ---------------------------------------------------

var cameraTempChart = null;

function refreshCameraTemperature() {
    $.get('/lib/ft/V2/freeturefinal/camera/status', function (json) {
        var data = null;
        try {
            var resp = (typeof json === 'string') ? JSON.parse(json) : json;
            data = resp && resp.data ? resp.data : null;
        } catch (e) { data = null; }
        renderCameraStatusBadges(data);
        renderCameraTempGauge(data);
    }).fail(function () {
        renderCameraStatusBadges(null);
        renderCameraTempGauge(null);
    });
}

function renderCameraStatusBadges(data) {
    var $conn = $('#camera-status-connection');
    var $over = $('#camera-status-overheated');
    var $fps  = $('#camera-status-fps');

    var connected = data ? data.connected : null;
    if (connected === true) {
        $conn.removeClass('label-default label-danger').addClass('label-success')
             .text(_('Camera connessa'));
    } else if (connected === false) {
        $conn.removeClass('label-default label-success').addClass('label-danger')
             .text(_('Camera disconnessa'));
    } else {
        $conn.removeClass('label-success label-danger').addClass('label-default')
             .text(_('Stato connessione: N/D'));
    }

    var overheated = data ? data.overheated : null;
    if (overheated === true) {
        $over.removeClass('label-default').addClass('label-danger')
             .text(_('Sensore surriscaldato')).show();
    } else {
        $over.hide();
    }

    var fps = data ? data.fps : null;
    if (typeof fps === 'number' && isFinite(fps)) {
        $fps.removeClass('label-default').addClass('label-info')
            .text(fps.toFixed(1) + ' fps').show();
    } else {
        $fps.hide();
    }
}

function renderCameraTempGauge(data) {
    var el = document.getElementById('camera-temp-gauge');
    if (!el || typeof echarts === 'undefined') {
        return;
    }
    if (!cameraTempChart) {
        cameraTempChart = echarts.init(el);
        $(window).on('resize.cameraTempGauge', function () { cameraTempChart.resize(); });
    }

    var asNum = function (v) { return (typeof v === 'number' && isFinite(v)) ? v : null; };
    // Prefer runtime thresholds (what the camera is actually using) over the
    // configured ones; fall back to the config when node_exporter is offline.
    var threshold = data ? (asNum(data.runtimeThreshold)  != null ? asNum(data.runtimeThreshold)  : asNum(data.threshold))  : null;
    var hyst      = data ? (asNum(data.runtimeHysteresis) != null ? asNum(data.runtimeHysteresis) : (asNum(data.hysteresis) || 0)) : 0;
    var p1        = data ? asNum(data.policyParam1) : null;
    var p2        = data ? (asNum(data.policyParam2) || 0) : 0;
    var current   = data ? asNum(data.currentTemperature) : null;

    var minV = 0;
    var maxV = 100;
    if (threshold !== null) {
        maxV = Math.max(maxV, Math.ceil((threshold + Math.max(hyst, 0) + 10) / 10) * 10);
    }

    // Color bands as eCharts gauge axisLine.lineStyle.color: [[fraction, color], ...]
    var stops = [];
    var pushStop = function (atValue, color) {
        if (atValue === null) return;
        var frac = (atValue - minV) / (maxV - minV);
        if (frac < 0) frac = 0;
        if (frac > 1) frac = 1;
        stops.push([frac, color]);
    };
    pushStop(p1 !== null ? (p1 - p2) : null, '#4CAF50');         // safe
    pushStop(p1 !== null ? (p1 + p2) : null, '#FFC107');         // policy band
    pushStop(threshold !== null ? (threshold - hyst) : null, '#FF9800'); // approaching
    pushStop(threshold !== null ? (threshold + hyst) : null, '#F44336'); // threshold band
    stops.push([1, '#7A1313']);                                  // overheated tail
    stops.sort(function (a, b) { return a[0] - b[0]; });
    // Drop duplicate fractions keeping the last color
    var dedup = [];
    for (var i = 0; i < stops.length; i++) {
        if (i > 0 && Math.abs(stops[i][0] - stops[i - 1][0]) < 1e-6) {
            dedup[dedup.length - 1] = stops[i];
        } else {
            dedup.push(stops[i]);
        }
    }

    var displayValue = current !== null ? current : 0;
    var detailFormatter = current !== null
        ? function (v) { return v.toFixed(1) + ' °C'; }
        : function () { return _('N/D'); };

    var option = {
        tooltip: {
            formatter: function () {
                var lines = [];
                if (threshold !== null) lines.push('<b>' + _('Soglia') + ':</b> ' + threshold + ' °C ± ' + hyst);
                if (p1 !== null) lines.push('<b>' + _('Policy') + ':</b> ' + p1 + ' °C ± ' + p2);
                if (current !== null) lines.push('<b>' + _('Attuale') + ':</b> ' + current.toFixed(1) + ' °C');
                return lines.length ? lines.join('<br/>') : _('Nessun dato');
            }
        },
        series: [{
            type: 'gauge',
            min: minV,
            max: maxV,
            startAngle: 210,
            endAngle: -30,
            splitNumber: 10,
            axisLine: { lineStyle: { width: 18, color: dedup } },
            axisTick: { length: 6, lineStyle: { color: '#999' } },
            splitLine: { length: 14, lineStyle: { color: '#666' } },
            axisLabel: { color: '#333', fontSize: 11, distance: 22 },
            pointer: { length: '65%', width: 5 },
            detail: {
                formatter: detailFormatter,
                fontSize: 22,
                offsetCenter: [0, '72%'],
                color: '#222'
            },
            title: { show: false },
            data: [{ value: displayValue, name: '' }]
        }]
    };

    cameraTempChart.setOption(option, true);

    var caption = [];
    if (threshold !== null) caption.push(_('Soglia') + ' ' + threshold + '°C ± ' + hyst);
    if (p1 !== null) caption.push(_('Policy') + ' ' + p1 + '°C ± ' + p2);
    if (data && data.policy) caption.push(data.policy);
    if (current === null) caption.push('(' + _('temperatura non disponibile') + ')');
    $('#camera-temp-info').text(caption.join(' — '));
}



// Show ovpn status
function StatusVPN() {
    $.ajax({
        url: "/lib/ovpn/V2/ovpn/status",
        type: "GET",
        success: function (res) {
            var vpnStatus = JSON.parse(res).data;
            var vpnIP = '';

            if (vpnStatus === '') {
                //NON ATTIVA
                $('#home-ovpn').css({'color': '#b52c1d', 'font-weight': 'bold'});
                $('#home-ovpn').text(_("VPN non attiva"));
                $('#home-ovpn-description').html(''); //se non attiva non ho ip
            } else {
                var ipMatch = vpnStatus.match(/inet\s+([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/);
                if (ipMatch) {
                    vpnIP = ipMatch[1]; //trovato ip
                }
                if(vpnIP.startsWith("11")){
                    $('#ip-status').text(_("Accesso VPN come GUEST"));
                }else if(vpnIP.startsWith("10")){
                    $('#ip-status').text(_("Accesso VPN come PRISMA"));
                }

                //ATTIVA
                $('#home-ovpn').css({'color': '#35b85a', 'font-weight': 'bold'});
                $('#home-ovpn').text(_("VPN Attiva"));
                $('#home-ovpn-description').html(vpnIP ? 'IP: ' + vpnIP : '');
            }
        }
    });
}




