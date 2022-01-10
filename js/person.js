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
        $(this).removeClass("required");
        $(this).removeClass("optional");
        $(this).addClass("optional");
    });

    core_person.new_password = null;
    core_person.confirm_password = null;
    disableForm(core_person, true);
    personLogic.get(core_person, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(core_person, false);
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
    alertConfirm("Conferma", "Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse", f);
}

function setIndexToShow() {
    indexToShow = core_person.id;
}

$(document).ready(function () {
    table = $('#PersonList').DataTable({
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
            if ($("." + $.md5('username')).is(":visible"))
                aoData.push({"name": "username", "value": $('#F_username').val()});
            if ($("." + $.md5('password')).is(":visible"))
                aoData.push({"name": "password", "value": $('#F_password').val()});
            if ($("." + $.md5('title')).is(":visible"))
                aoData.push({"name": "title", "value": $('#F_title').val()});
            if ($("." + $.md5('first_name')).is(":visible"))
                aoData.push({"name": "first_name", "value": $('#F_first_name').val()});
            if ($("." + $.md5('middle_name')).is(":visible"))
                aoData.push({"name": "middle_name", "value": $('#F_middle_name').val()});
            if ($("." + $.md5('last_name')).is(":visible"))
                aoData.push({"name": "last_name", "value": $('#F_last_name').val()});
            if ($("." + $.md5('suffix')).is(":visible"))
                aoData.push({"name": "suffix", "value": $('#F_suffix').val()});
            if ($("." + $.md5('company')).is(":visible"))
                aoData.push({"name": "company", "value": $('#F_company').val()});
            if ($("." + $.md5('job_title')).is(":visible"))
                aoData.push({"name": "job_title", "value": $('#F_job_title').val()});
            if ($("." + $.md5('email')).is(":visible"))
                aoData.push({"name": "email", "value": $('#F_email').val()});
            if ($("." + $.md5('web_page_address')).is(":visible"))
                aoData.push({"name": "web_page_address", "value": $('#F_web_page_address').val()});
            if ($("." + $.md5('im_address')).is(":visible"))
                aoData.push({"name": "im_address", "value": $('#F_im_address').val()});
            if ($("." + $.md5('phone')).is(":visible"))
                aoData.push({"name": "phone", "value": $('#F_phone').val()});
            if ($("." + $.md5('address')).is(":visible"))
                aoData.push({"name": "address", "value": $('#F_address').val()});
            if ($("." + $.md5('postcode')).is(":visible"))
                aoData.push({"name": "postcode", "value": $('#F_postcode').val()});
            if ($("." + $.md5('number')).is(":visible"))
                aoData.push({"name": "number", "value": $('#F_number').val()});
            if ($("." + $.md5('city')).is(":visible"))
                aoData.push({"name": "city", "value": $('#F_city').val()});
            if ($("." + $.md5('province')).is(":visible"))
                aoData.push({"name": "province", "value": $('#F_province').val()});
            if ($("." + $.md5('country')).is(":visible"))
                aoData.push({"name": "country", "value": $('#F_country').val()});
            if ($("." + $.md5('timezone')).is(":visible"))
                aoData.push({"name": "timezone", "value": $('#F_timezone').val()});
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
