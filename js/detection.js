/**
 *
 * @author: N3 S.r.l.
 */

$(setDetectionVisibility());
var inafdetection = new DetectionModel('V2');
var lastEditId = '';
var indexToShow = null;
var isPreviewEnabled = false;
var table1 = null;
var table2 = null;
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
var folder = null;

$(function () {
    disableForm(inafdetection);
});

function setLastEditId() {
    lastEditId = inafdetection.id;
}

function editObj(id) {
    disableForm(inafdetection, true);
    detectionLogic.get(inafdetection, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inafdetection, false);
}

function saveObj() {
    var f = function () {
        disableForm(inafdetection);
    }
    detectionLogic.save(inafdetection, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(inafdetection);
    }
    detectionLogic.remove(inafdetection, inafdetection.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inafdetection);
    detectionLogic.get(inafdetection, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inafdetection.id);
    };
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = inafdetection.id;
}

// Enable detections previews
$("#enable-detection-preview").on('change', function (event) {
    var currentPage = table2.page();
    isPreviewEnabled = $("#enable-detection-preview").is(":checked");
    $('#DetectionList').dataTable().fnDraw();
    table2.page(currentPage).draw(false);
});

// Show modal with detection preview and timestamp
function preview(row) {
    var data = table2.rows(row).data()[0];
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html(_("Detection del ") + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection dirmap and timestamp
function dirMap(row) {
    var data = table2.rows(row).data()[0];
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html(_("DirMap del ") + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[4] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection gemap and timestamp
function geMap(row) {
    var data = table2.rows(row).data()[0];
    var info = data[1].split(":");
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("GeMap del " + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[5] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Create detection zip
function getZip(row) {
    var data = table2.rows(row).data()[0];
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
    var data = table2.rows(row).data()[0];
    
    isProcessingVideo = true;
    $('#DetectionList').dataTable().fnDraw();
    defaultSuccess(_("Il download del video inizierà tra qualche minuto"));
    videoRow = row;
    

    createVideoXhr = $.ajax({
        url: "/lib/detection/V2/detection/createvideo/" + data[6],
        async: true,
        global: false,
        method: 'POST',
        success: function (json) {
            var data = JSON.parse(json);
            if(!data.result) {
                isProcessingVideo = false;
                $('#DetectionList').dataTable().fnDraw();
                defaultError(data.message);
                return;
            } else {
                isProcessingVideo = false;
                $('#DetectionList').dataTable().fnDraw();
                downloadVideo(json);
                return;
            }
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
            defaultError(_("Zip annullato"));
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

$(window).bind('beforeunload', function () {
    if (isProcessingZip || isProcessingVideo) {
        return _('Se lasci o ricarichi la pagina i download correnti verranno interrotti. Vuoi continuare lo stesso?');
    }
});

$(window).unload(function () {
    $.ajax({
        async: true,
        type: "POST",
        global: false,
        url: "/lib/detection/V2/detection/video/cancel"
    });
    $.ajax({
        async: true,
        type: "POST",
        global: false,
        url: "/lib/detection/V2/detection/zip/cancel"
    });
});

$(document).ready(function () {
    $.getJSON('/lib/detection/V2/detection/datatable/filelist', function(data) {
       // console.log(data);  // Mostra i dati restituiti dal server
    });
    // Create days datatable
    table1 = $('#DetectionDayList').DataTable({
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
                "targets": 0,
                "orderable": true
            },
            {
                "targets": "_all",
                "orderable": false
            },
            {
                "width": "5%",
                "className": "dt-center",
                "targets": [-2]
            },
            {
                "visible": false,
                "targets": [-1]
            }
        ],
        responsive: true,
        dom: 'lfrt<t>ip',
        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('date')).is(":visible"))
                aoData.push({"name": "date", "value": $('#F_date').val()});
            if ($("." + $.md5('number')).is(":visible"))
                aoData.push({"name": "number", "value": $('#F_number').val()});
            if ($("." + $.md5('folder')).is(":visible"))
                aoData.push({"name": "folder", "value": $('#F_folder').val()});
        },
        "drawCallback": function (settings) {
            if (table1.data().count()) {
                folder = table1.row(':eq(0)').data()[2];
                $('#DetectionDayList tbody tr:eq(0)').addClass('selected');
                if (table2) {
                    $('#DetectionList').dataTable().fnDraw();
                } else {
                    initDetectionsDatatable();
                }
            }
        },
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/detection/V2/detection/datatable/daylist',
        "paging": true,
        "ordering": true,
         order : [[0,"desc"]],
        "bLengthChange": false,
        "info": true,
        "searching": false
    });

    // Get last detection image and its timestamp
    $.get("/lib/detection/V2/detection/preview/lastdetection", function (json) {
        var data = JSON.parse(json).data;
        if (data) {
            var info = data[1].split(":");
            $('#last-detection-description').html("Detection del " + info[0] + " (" + data[2] + ")");
            $('#last-detection-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
        }
    });

    // Set toggle switch unchecked 
    $("#enable-detection-preview").attr("checked", false);
});

// Create datatable with detections of selected day
function initDetectionsDatatable() {
    var groupColumn = 1;
    table2 = $('#DetectionList').DataTable({
        stateSave: true,
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
                "targets": [-7],
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
                "targets": [-6],
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
                "targets": [-5],
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
                "targets": [-4],
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
                "targets": [-3],
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
                "targets": [-2],
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
                    "targets": [-1],
                    "data": null, 
                    "render": function (data, type, row, meta) {
                 
                        return data[8]; 
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

    // Change detections displayed by click on corresponding day
    $('#DetectionDayList tbody').on('click', 'tr', function () {
        var rowData = table1.row(this).data();
        folder = rowData[2];
        $('#DetectionList').dataTable().fnDraw();
        table1.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    // Hide preview columns and enable preview toggle if media preview not enabled
    $.get("/lib/ft/V2/freeturefinal/media/preview", function (json) {
        var data = JSON.parse(json).data;
        table2.column(3).visible(data);
        table2.column(4).visible(data);
        table2.column(5).visible(data);
        if (!data) {
            $("#enable-detection-preview-box").hide();
        }
    });

    // Hide zip and video column and enable preview toggle if media processing not enabled
    $.get("/lib/ft/V2/freeturefinal/media/processing", function (json) {
        var data = JSON.parse(json).data;
        table2.column(6).visible(data);
        table2.column(7).visible(data);
    });
}


$(function () {
    initFilters();
});
var setData = {
    singleDatePicker: true, opens: 'right',
    calender_style: "picker_2",
    format: 'DD/MM/YYYY'
};
function initFilters() {
    $(".filter-text").each(function (index) {
        $(this).select2({
            language: 'it',
            maximumSelectionLength: 1,
            multiple: true,
            ajax: {
                url: '/lib/detection/V2/detection/autocomplete/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 1
        });
    });
    $(".filter-date, .date").each(function (index) {
        $(this).daterangepicker(setData, function () {
            reloadAllDatatable();
        });
    });
    $(".foreign_key").each(function (index) {
        $(this).select2({
            language: 'it',
            maximumSelectionLength: 0,
            multiple: false,
            ajax: {
                url: '/lib/detection/V2/detection/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}



