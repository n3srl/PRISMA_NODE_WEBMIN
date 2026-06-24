/**
 *
 * @author: N3 S.r.l.
 */

$(setCaptureVisibility());
var inafcapture = new CaptureModel('V2');
var lastEditId = '';
var indexToShow = null;
var isPreviewEnabled = false;
var table1 = null;
var table2 = null;
var folder = null;

$(function () {
    disableForm(inafcapture);

});

function setLastEditId() {
    lastEditId = inafcapture.id;
}

function editObj(id) {
    disableForm(inafcapture, true);
    captureLogic.get(inafcapture, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inafcapture, false);
}

function saveObj() {
    var f = function () {
        disableForm(inafcapture);
    };
    captureLogic.save(inafcapture, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(inafcapture);
    };
    captureLogic.remove(inafcapture, inafcapture.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inafcapture);
    captureLogic.get(inafcapture, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inafcapture.id);
    };
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = inafcapture.id;
}

// Show modal with capture preview and timestamp
function preview(row) {
    var data = table2.rows(row).data()[0];
    var info = data[1].split(":");
    $('#capture-preview-modal').modal('show');
    $('#capture-preview-modal-label').html(_("Calibrazione del ") + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#capture-preview-modal-body').html(body);
}

// Enable captures previews
$("#enable-capture-preview").on('change', function (event) {
    var currentPage = table2.page();
    isPreviewEnabled = $("#enable-capture-preview").is(":checked");
    $('#CaptureList').dataTable().fnDraw();
    table2.page(currentPage).draw(false);
});

$(document).ready(function () {

    // Create days datatable
    table1 = $('#CaptureDayList').DataTable({
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
                $('#CaptureDayList tbody tr:eq(0)').addClass('selected');
                if (table2) {
                    $('#CaptureList').dataTable().fnDraw();
                } else {
                    initCapturesDatatable();
                }
            }
        },

        "order": [[0, 'desc']],
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        "stateSaveParams": function (settings, data) {
            data._captureDayDefaultsV2 = true;
        },
        "stateLoadParams": function (settings, data) {
            if (!data._captureDayDefaultsV2) {
                data.order = [[0, 'desc']];
            }
        },
        sAjaxSource: '/lib/capture/V2/capture/datatable/daylist',
        "paging": true,
        "ordering": true,
        "bLengthChange": false,
        "info": true,
        "searching": false
    });

    // Get last capture image and its timestamp
    $.get("/lib/capture/V2/capture/preview/lastcapture", function (json) {
        var data = JSON.parse(json).data;
        if (data) {
            var info = data[1].split(":");
            $('#last-capture-description').html(_("Calibrazione del ") + info[0] + " (" + data[2] + ")");
            $('#last-capture-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
        }
    });

    // Set toggle switch unchecked 
    $("#enable-capture-preview").attr("checked", false);

});

// Create datatable with captures of selected day
function initCapturesDatatable() {
    var groupColumn = 1;
    var collapsedGroups = {};
    table2 = $('#CaptureList').DataTable({
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
                "targets": [-1, -2, -3, -4, -5],
                "orderable": false
            },
            {
                "width": "5%",
                "className": "dt-center",
                "targets": [-1, -2]
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (!isPreviewEnabled) {
                        disabled = "disabled";
                    }
                    return "<center>" +
                            "<button class='btn btn-success btn-capture-preview' " + disabled + " onclick='preview(" + meta.row + ")' ><i class='fa fa-eye'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<a href='/lib/capture/V2/capture/download/" + data + "'>" +
                            "<button class='btn btn-success'><i class='fa fa-download'></i></button>" +
                            "</a>" +
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
                var collapsed = !!collapsedGroups[group];
                rows.nodes().each(function (r) {
                    r.style.display = collapsed ? 'none' : '';
                });
                return $('<tr class="group" style="background-color:#C6CAD4;">')
                        .append('<td colspan="4">' + info[0] + ' (' + info[1] + ' calibrazioni)' + '</td>')
                        .attr('data-name', group)
                        .toggleClass('collapsed', collapsed);
            },
            endRender: null,
            dataSrc: groupColumn
        },

        "order": [[groupColumn, 'desc']],
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/capture/V2/capture/datatable/filelist',
        "paging": true,
        "bLengthChange": false,
        "ordering": true,
        "info": true,
        "searching": false
    });

    // Change captures displayed by click on corresponding day
    $('#CaptureDayList tbody').on('click', 'tr', function () {
        var rowData = table1.row(this).data();
        folder = rowData[2];
        $('#CaptureList').dataTable().fnDraw();
        table1.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        loadCaptureCompleteness(folder);
    });

    // Hide preview column and enable preview toggle if media not enabled
    $.get("/lib/ft/V2/freeturefinal/media/preview", function (json) {
        var data = JSON.parse(json).data;
        table2.column(3).visible(data);
        if (!data) {
            $("#enable-capture-preview-box").hide();
        }
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
                url: '/lib/capture/V2/capture/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/capture/V2/capture/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

// Carica il completeness report per il giorno selezionato e lo renderizza
// sopra la tabella delle capture (alert verde se completo, warning con range
// di ore mancanti se ci sono buchi).
function loadCaptureCompleteness(dayDir) {
    var $box = $('#capture-completeness-status');
    if (!dayDir) { $box.empty(); return; }
    $box.html('<div class="alert alert-info" style="margin:0;">' + _('Controllo completezza...') + '</div>');
    $.ajax({
        url: '/lib/capture/V2/capture/completeness',
        method: 'GET',
        data: { dayDir: dayDir },
        dataType: 'json',
        cache: false
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) {
            $box.html('<div class="alert alert-warning" style="margin:0;">' +
                _('Impossibile calcolare la completezza per questo giorno') + '.</div>');
            return;
        }
        $box.html(renderCompletenessHtml(resp.data, _('capture')));
    }).fail(function () {
        $box.html('<div class="alert alert-warning" style="margin:0;">' +
            _('Errore nel controllo di completezza') + '.</div>');
    });
}

// Costruisce l'HTML del banner completeness.
function renderCompletenessHtml(d, label) {
    var period = d.periodSeconds;
    var freq;
    if (period >= 3600) {
        freq = (period / 3600).toFixed(1) + ' h';
    } else if (period >= 60) {
        freq = (period / 60).toFixed(1) + ' min';
    } else {
        freq = period + ' s';
    }
    if (d.complete) {
        return '<div class="alert alert-success" style="margin:0;">' +
            '<i class="fa fa-check"></i> ' +
            _('Giorno completo') + ': <b>' + d.foundCount + ' / ' + d.expectedCount + '</b> ' + label + ' ' +
            '(' + _('uno ogni') + ' ' + freq + ').' +
            '</div>';
    }
    var ranges = d.missingRanges || [];
    var rangesHtml = ranges.slice(0, 50).map(function (r) {
        if (r.count > 1) {
            return '<li><code>' + r.start + ' &mdash; ' + r.end + '</code> ' +
                '<small class="text-muted">(' + r.count + ' ' + _('slot') + ')</small></li>';
        }
        return '<li><code>' + r.start + '</code></li>';
    }).join('');
    var extra = (ranges.length > 50)
        ? '<li><em>' + _('e altri') + ' ' + (ranges.length - 50) + ' ' + _('range') + '...</em></li>'
        : '';
    return '<div class="alert alert-warning" style="margin:0;">' +
        '<i class="fa fa-exclamation-triangle"></i> ' +
        _('Giorno incompleto') + ': <b>' + d.foundCount + ' / ' + d.expectedCount + '</b> ' + label +
        ' (' + _('mancano') + ' <b>' + d.missingCount + '</b>, ' + _('uno ogni') + ' ' + freq + ').' +
        '<div style="margin-top:6px;"><b>' + _('Intervalli mancanti') + ':</b>' +
        '<ul style="margin:4px 0 0 0; padding-left: 20px;">' + rangesHtml + extra + '</ul></div>' +
        '</div>';
}



