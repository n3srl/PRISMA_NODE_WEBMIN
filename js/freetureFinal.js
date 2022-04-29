/**
 *
 * @author: N3 S.r.l.
 */

$(setFreetureFinalVisibility());
var inaffreeturefinal = new FreetureFinalModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(inaffreeturefinal);

});

function setLastEditId() {
    lastEditId = inaffreeturefinal.id;
}

function editObj(id) {
    disableForm(inaffreeturefinal, true);
    freeturefinalLogic.get(inaffreeturefinal, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inaffreeturefinal, false, ["key", "description"]);
}

function saveObj() {
    var f = function () {
        disableForm(inaffreeturefinal);
    }
    freeturefinalLogic.save(inaffreeturefinal, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function editConfigurationObj() {
    uploadConfigurationFile();
}

function uploadConfigurationFile() {
    var formdata = FormData();
    var file = $("#file")[0].files[0];
    formData.append("configuration", file);
}

function removeObj() {
    var f = function () {
        disableForm(inaffreeturefinal);
    }
    freeturefinalLogic.remove(inaffreeturefinal, inaffreeturefinal.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inaffreeturefinal);
    freeturefinalLogic.get(inaffreeturefinal, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inaffreeturefinal.id);
    };
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = inaffreeturefinal.id;
}

$(document).ready(function () {
    table = $('#FreetureFinalList').DataTable({
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
        "columnDefs": [
            {
                "targets": [-1, -2],
                "visible": false
            }],
        responsive: true,
        dom: 'lfrt<t>ip',
        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});
            if ($("." + $.md5('key')).is(":visible"))
                aoData.push({"name": "key", "value": $('#F_key').val()});
            if ($("." + $.md5('value')).is(":visible"))
                aoData.push({"name": "value", "value": $('#F_value').val()});
            if ($("." + $.md5('description')).is(":visible"))
                aoData.push({"name": "description", "value": $('#F_description').val()});
            if ($("." + $.md5('show')).is(":visible"))
                aoData.push({"name": "show", "value": $('#F_show').val()});
        },
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[aData.length - 1] == lastEditId) {
                $('td', nRow).addClass('lastEditedRow');
            }
        },
        "fnDrawCallback": function (settings, json) {
            // Show page with passed index
            indexToShow = null;
            setTimeout(function () {
                if (settings.json.pageToShow !== null) {
                    if ($('.dataTable').DataTable().page.info().page !== settings.json.pageToShow) {
                        $('.dataTable').DataTable().page(settings.json.pageToShow).draw('page');
                    }
                }
            }, 100);
        },
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/ft/V2/freeturefinal/datatable/list',
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
    });


});

// Enable upload button if user has chosen a file 
$("#form-ftcfg").on('change', function (event) {
    filename = $(this).val();
    if (filename !== '') {
        $("#uploadftbtn").attr('disabled', false);
    }
});

// Enable upload button if user has chosen a file 
$("#form-mask").on('change', function (event) {
    filename = $(this).val();
    if (filename !== '') {
        $("#uploadmaskbtn").attr('disabled', false);
    }
});

// Upload new freeture configuration
$("#ftCfgFileForm").on("submit", function (e) {
    e.preventDefault();
    var file = $("#form-ftcfg")[0].files[0];
    var formData = new FormData();
    formData.append("configuration", file);

    $.ajax({
        url: "/lib/ft/V2/freeturefinal/editconfiguration",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            reloadAllDatatable();
            defaultSuccess("Configurazione caricata correttamente");
            $("#uploadftbtn").attr('disabled', true);
            $('#form-ftcfg').val('');
        }
    });

});

// Upload new freeture mask
$("#maskFileForm").on("submit", function (e) {
    e.preventDefault();
    var file = $("#form-mask")[0].files[0];
    var formData = new FormData();
    formData.append("mask", file);

    $.ajax({
        url: "/lib/ft/V2/freeturefinal/editmask",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            reloadAllDatatable();
            defaultSuccess("Maschera caricata correttamente");
            $("#uploadmaskbtn").attr('disabled', true);
            $('#form-mask').val('');
        }
    });

});


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
                url: '/lib/ft/V2/freeturefinal/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/ft/V2/freeturefinal/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

