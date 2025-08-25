/**
 *
 * @author: N3 S.r.l.
 */

$(setDockerVisibility());
var inafdocker = new DockerModel('V2');
var lastEditId = '';
var indexToShow = null;
$(function () {
    disableForm(inafdocker);

});

function setLastEditId() {
    lastEditId = inafdocker.id;
}

function editObj(id) {
    disableForm(inafdocker, true);
    dockerLogic.get(inafdocker, id);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function allowEditObj() {
    enableForm(inafdocker, false, ["name", "image", "status"]);
}

function saveObj() {
    var f = function () {
        disableForm(inafdocker);
    }
    dockerLogic.save(inafdocker, setIndexToShow, setLastEditId, f, reloadAllDatatable);
}

function removeObj() {
    var f = function () {
        disableForm(inafdocker);
    }
    dockerLogic.remove(inafdocker, inafdocker.id, safeDelete, f, reloadAllDatatable);
}

function newObj() {
    newForm(inafdocker);
    dockerLogic.get(inafdocker, null);
    $('td').removeClass('lastEditedRow');
    lastEditId = '';
}

function undoObj() {
    var f = function () {
        editObj(inafdocker.id);
    };
    alertConfirm(_("Conferma"), _("Sei sicuro di voler annullare le modifiche? Le modifiche non salvate andranno perse"), f);
}

function setIndexToShow() {
    indexToShow = inafdocker.id;
}

// Restart Docker container
function restart(value) {
    $.ajax({
        url: "/lib/docker/V2/docker/restart/" + value,
        type: "POST",
        success: function (res) {
            if (JSON.parse(res).result) {
                defaultSuccess(_("Container riavviato correttamente"));
                reloadAllDatatable();
            } else {
                defaultError();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            defaultError();
        }

    });
}

// Start Docker container
function start(value) {
    $.ajax({
        url: "/lib/docker/V2/docker/start/" + value,
        type: "POST",
        success: function (res) {
            if (JSON.parse(res).result) {
                defaultSuccess(_("Container avviato correttamente"));
                reloadAllDatatable();
            } else {
                defaultError();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            defaultError();
        }

    });
}

// Stop Docker container
function stop(value) {
    $.ajax({
        url: "/lib/docker/V2/docker/stop/" + value,
        type: "POST",
        success: function (res) {
            if (JSON.parse(res).result) {
                defaultSuccess(_("Container fermato correttamente"));
                reloadAllDatatable();
            } else {
                defaultError();
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            defaultError();
        }

    });
}

function getFreetureLogs()
{
    $.get(
        "/lib/docker/V2/docker/freeture/log",
        function(json) {
            try 
            {
                var data = JSON.parse(json);
                if(data)
                {
                    $("#freeture-log").val(data.data);
                    var txt = $("#freeture-log");
                    txt.scrollTop(txt[0].scrollHeight);
                }   
            } catch(error)
            {
                $("#freeture-log").val(json);
                    var txt = $("#freeture-log");
                    txt.scrollTop(txt[0].scrollHeight);
            }
        }

    );
}

function downloadFreetureLog()
{
    var logs = $("#freeture-log").val();
    const blob = new Blob([logs], { type: 'text/plain'});

    const url = URL.createObjectURL(blob);
    const $a = $('<a>').attr({
        href: url,
        download: 'freeturelog.log'
    });

    $('body').append($a);
    $a[0].click();

    $a.remove();

    URL.revokeObjectURL(url);
}

setInterval(function() {
    getFreetureLogs();
}, 2000);

$(document).ready(function () {

    // Docker freeture logs
    getFreetureLogs();

    

    $("#freeture-log-download").click(function() {
        downloadFreetureLog();
    });
    //
    table = $('#DockerList').DataTable({
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

        columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            },
            {
                "targets": [-5],
                "orderable": false
            },
            {
                "targets": [-4, -6],
                "visible": false
            },
            {"width": "5%",
                "className": "dt-center",
                "targets": [-1, -2, -3]
            },
            {
                "targets": [-5],
                render: function (data, type, row, meta) {
                    var color;
                    if (data === "Up") {
                        color = "green";
                    } else if (data === "Restarting") {
                        color = "orange";
                    } else {
                        color = "black";
                    }

                    return '<div style="color:'
                            + color
                            + '">' + data
                            + '</div>';
                }
            },
            {
                "targets": [-3],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (row[3] === "Restarting" || row[3] === "Up") {
                        disabled = "disabled";
                    }
                    return "<div>" +
                            "<button type = 'button' " + disabled + " value='" + data + "' onclick= 'start(this.value)' class='btn btn-primary'><i class='fa fa-play'></i></button>" +
                            "</div>";
                }
            },
            {
                "targets": [-2],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (row[3] === "Exited") {
                        disabled = "disabled";
                    }
                    return "<div>" +
                            "<button type = 'button' " + disabled + " value='" + data + "' onclick= 'stop(this.value)' class='btn btn-warning'><i class='fa fa-stop'></i></button>" +
                            "</div>";
                }
            },
            {
                "targets": [-1],
                render: function (data, type, row, meta) {
                    var disabled = "";
                    if (row[3] === "Exited") {
                        disabled = "disabled";
                    }
                    return "<div>" +
                            "<button type = 'button' " + disabled + " value='" + data + "' onclick= 'restart(this.value)' class='btn btn-info' ><i class='fa fa-repeat'></i></button>" +
                            "</div>";
                }
            }
        ],

        responsive: true,
        dom: 'lfrt<t>ip',

        "fnServerParams": function (aoData) {
            // Show page with passed index
            aoData.push({"name": "searchPageById", "value": indexToShow});
            if ($("." + $.md5('id')).is(":visible"))
                aoData.push({"name": "id", "value": $('#F_id').val()});
            if ($("." + $.md5('name')).is(":visible"))
                aoData.push({"name": "name", "value": $('#F_name').val()});
            if ($("." + $.md5('image')).is(":visible"))
                aoData.push({"name": "image", "value": $('#F_image').val()});
            if ($("." + $.md5('command')).is(":visible"))
                aoData.push({"name": "command", "value": $('#F_command').val()});
            if ($("." + $.md5('status')).is(":visible"))
                aoData.push({"name": "status", "value": $('#F_status').val()});
            if ($("." + $.md5('created')).is(":visible"))
                aoData.push({"name": "created", "value": $('#F_created').val()});
            if ($("." + $.md5('actions')).is(":visible"))
                aoData.push({"name": "actions", "value": $('#F_actions').val()});
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
        sAjaxSource: '/lib/docker/V2/docker/datatable/list',
        "paging": false,
        "ordering": false,
        "info": false,
        "searching": false
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
                url: '/lib/docker/V2/docker/autocomplete/' + $(this).attr('id').replace('F_', ''),
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
                url: '/lib/docker/V2/docker/foreignkey/' + $(this).attr('id').replace('F_', ''),
                dataType: 'json'
            },
            minimumInputLength: 0
        });
    });
}

