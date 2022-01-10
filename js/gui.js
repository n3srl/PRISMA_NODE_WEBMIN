/**
 *
 * @author: N3 S.r.l.
 */

var core_gui = new GuiModel('v1');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(core_gui);

});

function setLastEditId() {
    lastEditId = core_gui.id;
}

function editObj(id) {
    disableForm(core_gui, true);
    getClass(core_gui, id);
}

function allowEditObj() {
    enableForm(core_gui, false);
}

function saveObj() {
    saveClass(core_gui, setIndexToShow, setLastEditId, reloadAllDatatable);
    disableForm(core_gui);
}

function deleteObj(id) {
    deleteClass(core_gui, core_gui.id, safeDelete, reloadAllDatatable);
    disableForm(core_gui);
}

function newObj() {
    newForm(core_gui);
    getClass(core_gui);
}

function undoObj() {
    var f = function () {
        editObj(core_gui.id);
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = core_gui.id;
}

$(document).ready(function () {
    $('#GuiList').dataTable({
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
        "columnDefs": [{
                "targets": [-2, -3],
                "orderable": false
            },
            {
                "targets": [-1],
                "visible": false
            }],
        responsive: true,
        dom: 'lfrt<t>ip',
        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});
            if ($("." + $.md5('oid')).is(":visible"))
                aoData.push({"name": "oid", "value": $('#F_oid').val()});
            if ($("." + $.md5('name')).is(":visible"))
                aoData.push({"name": "name", "value": $('#F_name').val()});
            if ($("." + $.md5('description')).is(":visible"))
                aoData.push({"name": "description", "value": $('#F_description').val()});
            if ($("." + $.md5('parent_id')).is(":visible"))
                aoData.push({"name": "parent_id", "value": $('#F_parent_id').val()});
            if ($("." + $.md5('menu_item')).is(":visible"))
                aoData.push({"name": "menu_item", "value": $('#F_menu_item').val()});
            if ($("." + $.md5('sorting')).is(":visible"))
                aoData.push({"name": "sorting", "value": $('#F_sorting').val()});
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[aData.length - 1] == lastEditId) {
                $('td', nRow).css('background-color', '#ffe4c4');
            }
        },
        "fnDrawCallback": function (settings, json) {
            // Show page with passed index
            indexToShow = null;
            setTimeout(function () {
                if (settings.json.pageToShow != null) {
                    if ($('.dataTable').DataTable().page.info().page !== settings.json.pageToShow) {
                        $('.dataTable').DataTable().page(settings.json.pageToShow).draw('page');
                    }
                }
            }, 100);
        },
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/core/v1/gui/datatable/list'
    });
});

$(function () {
    initFilters();
});
var setData = {
    showDropdowns: true,
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
                url: '/lib/core/v1/gui/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/core/v1/gui/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

