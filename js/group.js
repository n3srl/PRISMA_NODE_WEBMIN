/**
 *
 * @author: N3 S.r.l.
 */

var core_group = new GroupModel('v1');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(core_group);

});

function setLastEditId() {
    lastEditId = core_group.id;
}

function editObj(id) {
    disableForm(core_group, true);
    getClass(core_group, id);
}

function allowEditObj() {
    enableForm(core_group, false);
}

function saveObj() {
    saveClass(core_group, setIndexToShow, setLastEditId, reloadAllDatatable);
    disableForm(core_group);
}

function deleteObj(id) {
    deleteClass(core_group, core_group.id, safeDelete, reloadAllDatatable);
    disableForm(core_group);
}

function newObj() {
    newForm(core_group);
    getClass(core_group);
}

function undoObj() {
    var f = function () {
        editObj(core_group.id);
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = core_group.id;
}

$(document).ready(function () {
    $('#GroupList').dataTable({
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
            if ($("." + $.md5('type')).is(":visible"))
                aoData.push({"name": "type", "value": $('#F_type').val()});
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
        sAjaxSource: '/lib/core/v1/group/datatable/list'
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
                url: '/lib/core/v1/group/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/core/v1/group/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

