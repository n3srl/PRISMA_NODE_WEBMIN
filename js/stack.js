/**
 *
 * @author: N3 S.r.l.
 */

$(setStackVisibility());
var inafstack = new StackModel('V2');
var lastEditId = '';
var indexToShow = null;
var isPreviewEnabled = false;
var table1 = null;
var table2 = null;

$(function () {
    disableForm(inafstack);

});

function setLastEditId() {
    lastEditId = inafstack.id;
}

function editObj(id) {
    disableForm(inafstack, true);
    stackLogic.get(inafstack, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inafstack, false);
}

function saveObj() {
    var f = function () {
        disableForm(inafstack);
    };
    stackLogic.save(inafstack, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(inafstack);
    };
    stackLogic.remove(inafstack, inafstack.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inafstack);
    stackLogic.get(inafstack, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inafstack.id);
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = inafstack.id;
}

// Show modal with stack preview and timestamp
function preview(row) {
    var data = table2.rows(row).data()[0];
    $('#stack-preview-modal').modal('show');
    $('#stack-preview-modal-label').html("Stack del " + data[1] + " (" + data[2] + ")");
    var body = '<img class="img-responsive" src="' + data[3] + '"/>';
    $('#stack-preview-modal-body').html(body);
}

// Enable stacks previews
$("#enable-stack-preview").on('change', function (event) {
    isPreviewEnabled = $("#enable-stack-preview").is(":checked");
    $('#StackList').dataTable().fnDraw();
});

$(document).ready(function () {

    // Create days datatable
    table1 = $('#StackDayList').DataTable({
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
                $('#StackDayList tbody tr:eq(0)').addClass('selected');
                initStacksDatatable(folder);
            }
        },

        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/stack/V2/stack/datatable/daylist',
        "paging": true,
        "ordering": true,
        "bLengthChange": false,
        "info": true,
        "searching": false
    });

    // Get last stack image and its timestamp
    $.get("/lib/stack/V2/stack/preview/laststack", function (json) {
        var data = JSON.parse(json).data;
        var info = data[1].split(":");
        $('#last-stack-description').html("Stack del " + info[0] + " (" + data[2] + ")");
        $('#last-stack-preview').html("<img class='img-responsive' src='" + data[3] + "'/>");
    });

    // Set toggle switch unchecked 
    $("#enable-stack-preview").attr("checked", false);
});

// Create datatable with captures of selected day
function initStacksDatatable(folder) {
    var groupColumn = 1;
    var collapsedGroups = {};
    table2 = $('#StackList').DataTable({
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
                            "<button class='btn btn-success btn-stack-preview' " + disabled + " onclick='preview(" + meta.row + ")' ><i class='fa fa-eye'></i></button>" +
                            "</center>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    return "<center>" +
                            "<a href='/lib/stack/V2/stack/download/" + data + "'>" +
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
                        .append('<td colspan="4">' + info[0] + ' (' + info[1] + ' stack)' + '</td>')
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
        sAjaxSource: '/lib/stack/V2/stack/datatable/filelist',
        "paging": true,
        "bLengthChange": false,
        "ordering": true,
        "info": true,
        "searching": false
    });

    // Change stacks displayed by click on corresponding day
    $('#StackDayList tbody').on('click', 'tr', function () {
        var rowData = table1.row(this).data();
        folder = rowData[2];
        $('#StackList').dataTable().fnDraw();
        table1.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    });

    // Hide preview column and enable preview toggle if media not enabled
    $.get("/lib/ft/V2/freeturefinal/media/preview", function (json) {
        var data = JSON.parse(json).data;
        table2.column(3).visible(data);
        if (!data) {
            $("#enable-stack-preview-box").hide();
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
                url: '/lib/stack/V2/stack/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/stack/V2/stack/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}



