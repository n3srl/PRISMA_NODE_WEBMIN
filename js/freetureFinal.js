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
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = inaffreeturefinal.id;
}

$(document).ready(function () {
    table = $('#FreetureFinalList').DataTable({
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
        "columnDefs": [
            {
                "targets": [-1, -2, -3],
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
        "iDisplayLength": 10,
        "iDisplayStart": 0,
        "pageLength": 10,
        bProcessing: true,
        bServerSide: true,
        bStateSave: true,
        sAjaxSource: '/lib/ft/V2/freeturefinal/datatable/list',
        "paging": true,
        "ordering": false,
        "info": true,
        "searching": false
    });
    
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

});

// Show modal with freeture mask
$("#btn-show-mask").click(function () {
    $('#mask-preview-modal').modal('show');
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
            defaultSuccess(_("Configurazione caricata correttamente"));
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

    // Make a POST request to update mask file
    $.ajax({
        url: "/lib/ft/V2/freeturefinal/editmask",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            try {
                var parsed = JSON.parse(res);
                if(parsed.result) {
                    if (parsed.warning) {
                        alert(parsed.warning); 
                    }
                    reloadAllDatatable();
                    defaultSuccess(_("Maschera caricata correttamente"));
                    $("#uploadmaskbtn").attr('disabled', true);
                    $('#form-mask').val('');

                    // Make a POST request to enable mask
                    $.get("/lib/ft/V2/freeturefinal/id/ACQ_MASK_ENABLED", function (json1) {
                        var id = JSON.parse(json1).data;
                        $.get("/lib/ft/V2/freeturefinal/" + id, function (json2) {
                            var obj = JSON.parse(json2).data;
                            //var ft = new FreetureFinalModel('V2');
                            inaffreeturefinal.id = obj.id.toString();
                            inaffreeturefinal.key = obj.key;
                            inaffreeturefinal.value = "true";
                            inaffreeturefinal.description = obj.description;
                            inaffreeturefinal.insert(reloadAllDatatable);
                        });

                    });

                } else {
                    defaultError(_("Errore durante il caricamento della maschera"));
                }
            } catch (e) {
                defaultError(_("Errore durante il caricamento della maschera"));
            }
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

