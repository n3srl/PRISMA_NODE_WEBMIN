/**
 *
 * @author: N3 S.r.l.
 */

$(setPersonVisibility());
var core_person = new PersonModel('v1');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(core_person);

});

function setLastEditId() {
    lastEditId = core_person.id;
}

function editObj(id) {
    $("input[type=password]").each(function (key, value) {
        $(this).addClass("required");
        $(this).removeClass("optional");
    });

    core_person.new_password = null;
    core_person.confirm_password = null;
    disableForm(core_person, true);
    personLogic.get(core_person, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {

    const my_level = parseInt($("#my_level").val());
    const person_level = parseInt(core_person.level.replaceAll("\n", ""));

    if(my_level < person_level) {
        alert(_("Non puoi modificare l'utente")+[[user]]+_("in quanto ha un livello superiore al tuo").replace("[[user]]", core_person.username));
        return;
    }

    enableForm(core_person,false);
}

function saveObj() {
    var f = function () {
        disableForm(core_person);
        core_person.new_password = null;
        core_person.confirm_password = null;
    }
    personLogic.save(core_person, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(core_person);
        core_person.new_password = null;
        core_person.confirm_password = null;
    }
    personLogic.remove(core_person, core_person.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    $("input[type=password]").each(function (key, value) {
        $(this).removeClass("optional");
        $(this).addClass("required");
    });
    core_person.new_password = null;
    core_person.confirm_password = null;
    newForm(core_person);
    personLogic.get(core_person, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(core_person.id);
    };
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = core_person.id;
}

$(document).ready(function () {
    table = $('#PersonList').DataTable({
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
        "columnDefs": [{
                "targets": "_all",
                "orderable": false
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) { 
                    if(data === "2"){
                        return "Super user";
                    } else if(data === "1"){
                        return "Admin";
                    }
                     else {
                        return "Agent";
                    }
                }
            },
            {
                "targets": [-1, -3, -4, -5],
                "visible": false
            }
        ],
        responsive: true,
        dom: 'lfrt<t>ip',
        
        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});            
            if ($("." + $.md5('username')).is(":visible"))
                aoData.push({"name": "username", "value": $('#F_username').val()});
            if ($("." + $.md5('password')).is(":visible"))
                aoData.push({"name": "password", "value": $('#F_password').val()});            
            if ($("." + $.md5('timezone')).is(":visible"))
                aoData.push({"name": "timezone", "value": $('#F_timezone').val()});
            if ($("." + $.md5('erased')).is(":visible"))
                aoData.push({"name": "erased", "value": $('#F_erased').val()});
            if ($("." + $.md5('level')).is(":visible"))
                aoData.push({"name": "level", "value": $('#F_level').val()});
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
        sAjaxSource: '/lib/core/v1/person/datatable/list'
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
                url: '/lib/core/v1/person/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/core/v1/person/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}
$("[name=new_password]").keyup(function () {
    checkPasswordStrenght();
});
$("[name=new_password]").change(function () {
    checkPasswordStrenght();
});
function checkPasswordStrenght() {
    var pass = $("[name=new_password]").val();
    var strength = 0;
    var arr = [/.{5,}/, /[a-z]+/, /[0-9]+/, /[A-Z]+/];
    jQuery.map(arr, function (regexp) {
        if (pass.match(regexp))
            strength++;
    });
    $("#password-strength-meter").val(strength);
}
