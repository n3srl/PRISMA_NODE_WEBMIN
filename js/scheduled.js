/**
 *
 * @author: N3 S.r.l.
 */

$(setScheduledVisibility());
var inafscheduled = new ScheduledModel('V2');
var lastEditId = '';
var indexToShow = null;
var isPreviewEnabled = false;
var table1 = null;
var table2 = null;
var folder = null;

$(function () {
    disableForm(inafscheduled);

});

function setLastEditId() {
    lastEditId = inafscheduled.id;
}

function editObj(id) {
    disableForm(inafscheduled, true);
    scheduledLogic.get(inafscheduled, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inafscheduled, false);
}

function saveObj() {
    var f = function () {
        disableForm(inafscheduled);
    };
    scheduledLogic.save(inafscheduled, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(inafscheduled);
    };
    scheduledLogic.remove(inafscheduled, inafscheduled.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inafscheduled);
    scheduledLogic.get(inafscheduled, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inafscheduled.id);
    };
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = inafscheduled.id;
}

// Show modal with scheduled acquisition preview and timestamp
function preview(row) {
    var data = table2.rows(row).data()[0];
    var info = data[1].split(":");
    $('#scheduled-preview-modal').modal('show');
    $('#scheduled-preview-modal-label').html(_("Acquisizione del ") + info[0] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#scheduled-preview-modal-body').html(body);
}

// Enable scheduled acquisitions previews
$("#enable-scheduled-preview").on('change', function (event) {
    var currentPage = table2.page();
    isPreviewEnabled = $("#enable-scheduled-preview").is(":checked");
    $('#ScheduledList').dataTable().fnDraw();
    table2.page(currentPage).draw(false);
});

$(document).ready(function () {

    // Create days datatable
    table1 = $('#ScheduledDayList').DataTable({
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
                $('#ScheduledDayList tbody tr:eq(0)').addClass('selected');
                if (table2) {
                    $('#ScheduledList').dataTable().fnDraw();
                } else {
                    initScheduledDatatable();
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
            data._scheduledDayDefaultsV2 = true;
        },
        "stateLoadParams": function (settings, data) {
            if (!data._scheduledDayDefaultsV2) {
                data.order = [[0, 'desc']];
            }
        },
        sAjaxSource: '/lib/scheduled/V2/scheduled/datatable/daylist',
        "paging": true,
        "ordering": true,
        "bLengthChange": false,
        "info": true,
        "searching": false
    });

    // Get last scheduled acquisition image and its timestamp
    $.get("/lib/scheduled/V2/scheduled/preview/lastscheduled", function (json) {
        var data = JSON.parse(json).data;
        if (data) {
            var info = data[1].split(":");
            $('#last-scheduled-description').html(_("Acquisizione del ") + info[0] + " (" + data[2] + ")");
            $('#last-scheduled-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
        }
    });

    // Set toggle switch unchecked
    $("#enable-scheduled-preview").attr("checked", false);

});

// Create datatable with scheduled acquisitions of selected day
function initScheduledDatatable() {
    var groupColumn = 1;
    var collapsedGroups = {};
    table2 = $('#ScheduledList').DataTable({
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
                            "<button class='btn btn-success btn-scheduled-preview' " + disabled + " onclick='preview(" + meta.row + ")' ><i class='fa fa-eye'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<a href='/lib/scheduled/V2/scheduled/download/" + data + "'>" +
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
                        .append('<td colspan="4">' + info[0] + ' (' + info[1] + ' acquisizioni)' + '</td>')
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
        sAjaxSource: '/lib/scheduled/V2/scheduled/datatable/filelist',
        "paging": true,
        "bLengthChange": false,
        "ordering": true,
        "info": true,
        "searching": false
    });

    // Change scheduled acquisitions displayed by click on corresponding day
    $('#ScheduledDayList tbody').on('click', 'tr', function () {
        var rowData = table1.row(this).data();
        folder = rowData[2];
        $('#ScheduledList').dataTable().fnDraw();
        table1.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    // Hide preview column and enable preview toggle if media not enabled
    $.get("/lib/ft/V2/freeturefinal/media/preview", function (json) {
        var data = JSON.parse(json).data;
        table2.column(3).visible(data);
        if (!data) {
            $("#enable-scheduled-preview-box").hide();
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
                url: '/lib/scheduled/V2/scheduled/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/scheduled/V2/scheduled/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}
