/**
 *
 * @author: N3 S.r.l.
 */

var core_permission = new PermissionModel('v1');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(core_permission);

});

function setLastEditId() {
    lastEditId = core_permission.id;
}

function editObj(id) {
    disableForm(core_permission, true);
    getClass(core_permission, id);
}

function allowEditObj() {
    enableForm(core_permission, false);
}

function saveObj() {
    saveClass(core_permission, setIndexToShow, setLastEditId, reloadAllDatatable);
    disableForm(core_permission);
}

function deleteObj(id) {
    deleteClass(core_permission, core_permission.id, safeDelete, reloadAllDatatable);
    disableForm(core_permission);
}

function newObj() {
    newForm(core_permission);
    getClass(core_permission);
}

function undoObj() {
    var f = function () {
        editObj(core_permission.id);
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = core_permission.id;
}

$(document).ready(function () {
    $('#PermissionList').dataTable({
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
            if ($("." + $.md5('ext_oid')).is(":visible"))
                aoData.push({"name": "ext_oid", "value": $('#F_ext_oid').val()});
            if ($("." + $.md5('person_id')).is(":visible"))
                aoData.push({"name": "person_id", "value": $('#F_person_id').val()});
            if ($("." + $.md5('group_id')).is(":visible"))
                aoData.push({"name": "group_id", "value": $('#F_group_id').val()});
            if ($("." + $.md5('execute')).is(":visible"))
                aoData.push({"name": "execute", "value": $('#F_execute').val()});
            if ($("." + $.md5('read')).is(":visible"))
                aoData.push({"name": "read", "value": $('#F_read').val()});
            if ($("." + $.md5('write')).is(":visible"))
                aoData.push({"name": "write", "value": $('#F_write').val()});
            if ($("." + $.md5('active')).is(":visible"))
                aoData.push({"name": "active", "value": $('#F_active').val()});
            if ($("." + $.md5('username')).is(":visible"))
                aoData.push({"name": "username", "value": $('#F_username').val()});
            if ($("." + $.md5('secret_token')).is(":visible"))
                aoData.push({"name": "secret_token", "value": $('#F_secret_token').val()});
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
        sAjaxSource: '/lib/core/v1/permission/datatable/list'
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
                url: '/lib/core/v1/permission/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/core/v1/permission/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

