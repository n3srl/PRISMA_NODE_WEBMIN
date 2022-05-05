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
var zipRow = null;
var stopZip = false;
var createZipXhr = null;

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
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = inafdetection.id;
}

// Enable detections previews
$("#enable-detection-preview").on('change', function (event) {
    isPreviewEnabled = $("#enable-detection-preview").is(":checked");
    $('#DetectionList').dataTable().fnDraw();
});

// Show modal with detection preview and timestamp
function preview(row) {
    var data = table2.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("Detection del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection dirmap and timestamp
function dirMap(row) {
    var data = table2.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("DirMap del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[4] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Show modal with detection gemap and timestamp
function geMap(row) {
    var data = table2.rows(row).data()[0];
    $('#detection-preview-modal').modal('show');
    $('#detection-preview-modal-label').html("GeMap del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[5] + '"/>';
    $('#detection-preview-modal-body').html(body);
}

// Download detection zip
function download(row) {
    var data = table2.rows(row).data()[0];
    defaultSuccess("Il tuo download inizierà tra qualche minuto");
    isProcessingZip = true;
    zipRow = row;
    $('#DetectionList').dataTable().fnDraw();

    /*
     $.when($.ajax("/lib/detection/V2/detection/createzip/" + data[6])).done(function (json) {
     var data = JSON.parse(json).data;
     window.location.href = "/lib/detection/V2/detection/download/" + data;
     });*/

    //$.when(createZip(data[6])).done(downloadZip);

    //createZip(data[6]).done(downloadZip);

    createZipXhr = $.ajax({
        url: "/lib/detection/V2/detection/createzip/" + data[6],
        async: true,
        global: false,
        success: function (json) {
            downloadZip(json);
        },
        error: function (jqXHR, error, errorThrown) {
            $.ajax({
                async: true,
                type: "POST",
                global: false,
                url: "/lib/detection/V2/detection/zip/cancel",
                success: function (json) {
                    defaultError("Zip annullato");
                }
            });
            isProcessingZip = false;
            zipRow = null;
            $('#DetectionList').dataTable().fnDraw();
        }
    });

}

/*
 function createZip(data) {
 createZipXhr = $.ajax({
 url: "/lib/detection/V2/detection/createzip/" + data,
 global: false,
 async: true
 });
 return createZipXhr;
 }*/

function downloadZip(json) {
    isProcessingZip = false;
    zipRow = null;
    $('#DetectionList').dataTable().fnDraw();
    // if (!stopZip) {
    var data = JSON.parse(json).data;
    window.location.href = "/lib/detection/V2/detection/download/" + data;
    // }
}

function cancelZip() {
    createZipXhr.abort();
    stopZip = true;
    /*
     isProcessingZip = false;
     
     zipRow = null;
     $('#DetectionList').dataTable().fnDraw();
     $.ajax({
     async: true,
     type: "POST",
     global: false,
     url: "/lib/detection/V2/detection/zip/cancel",
     success: function (json) {
     defaultError("Zip annullato");
     }        
     });*/

}

$(document).ready(function () {

    // Create days datatable
    table1 = $('#DetectionDayList').DataTable({
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
                "targets": [-1, -2, -3],
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
                var folder = table1.row(':eq(0)').data()[2];
                $('#DetectionDayList tbody tr:eq(0)').addClass('selected');
                initDetectionsDatatable(folder);
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
        "bLengthChange": false,
        "info": true,
        "searching": false
    });

    // Get last detection image and its timestamp
    $.get("/lib/detection/V2/detection/preview/lastdetection", function (json) {
        var data = JSON.parse(json).data;
        var info = data[1].split(":");
        $('#last-detection-description').html("Detection del " + info[0] + " (" + data[2] + ")");
        $('#last-detection-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
    });

    // Set toggle switch unchecked 
    $("#enable-detection-preview").attr("checked", false);


});

// Create datatable with detections of selected day
function initDetectionsDatatable(folder) {
    var groupColumn = 1;
    table2 = $('#DetectionList').DataTable({
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
                "targets": [-1, -2, -3, -4]
            },
            {
                "targets": [-7],
                render: function (data, type, row, meta) {
                    if (isProcessingZip && meta.row === zipRow) {
                        return "<div class='col-md-12'>" + data + "</div>" + "<div class='col-md-1'><div class='loader'></div></div><div class='col-md-11'> Scaricamento zip in corso...</div>";
                    }
                    return data;
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
                    if (isProcessingZip && meta.row === zipRow) {
                        return "<center>" +
                                "<button class='btn btn-danger' onclick='cancelZip()'><i class='fa fa-close'></i></button>" +
                                "</center>";
                    }
                    var disabled = "";
                    if (isProcessingZip) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success' " + disabled + " onclick= 'download(" + meta.row + ")'><i class='fa fa-download'></i></button>" +
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
                        .append('<td colspan="6">' + info[0] + ' (' + info[1] + ' detection)' + '</td></tr>');

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



