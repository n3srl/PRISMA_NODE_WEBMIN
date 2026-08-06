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

/* ==================================================================
 *  EDITOR SCHEDULAZIONI (ACQ_SCHEDULE) + PROSSIMI SCATTI
 * ================================================================== */

// Working list dell'editor: {h,m,s,e,g,f,n, bracket:{group,type}|null}
var scheduleEntries = [];
var savedSchedule = null;
var bracketGroupCounter = 0;

function pad2(v) {
    return (v < 10 ? '0' : '') + v;
}

function entryUtcSeconds(e) {
    return e.h * 3600 + e.m * 60 + e.s;
}

function serializeEntry(e) {
    return pad2(e.h) + 'h' + pad2(e.m) + 'm' + pad2(e.s) + 's' + e.e + 'e' + e.g + 'g' + e.f + 'f' + e.n + 'n';
}

function serializeEntries() {
    return scheduleEntries.map(serializeEntry).join(',');
}

function parseScheduleToken(tok) {
    var m = tok.trim().match(/^(\d{1,2})h(\d{1,2})m(\d{1,2})s(\d+)e(\d+)g(\d*)f(\d+)n$/i);
    if (!m) {
        return null;
    }
    var e = {h: +m[1], m: +m[2], s: +m[3], e: +m[4], g: +m[5], f: (m[6] === '' ? 1 : +m[6]), n: +m[7], bracket: null};
    if (e.h > 23 || e.m > 59 || e.s > 59 || e.n < 1) {
        return null;
    }
    return e;
}

// Parsa la stringa ACQ_SCHEDULE completa (nessun limite di lunghezza).
function parseScheduleString(raw) {
    var res = {entries: [], errors: []};
    raw = (raw || '').trim();
    if (raw === '') {
        return res;
    }
    raw.split(',').forEach(function (tok, i) {
        if (tok.trim() === '') {
            return;
        }
        var e = parseScheduleToken(tok);
        if (e) {
            res.entries.push(e);
        } else {
            res.errors.push('#' + (i + 1) + " '" + tok.trim() + "'");
        }
    });
    return res;
}

// Data odierna (UTC) con l'orario UT dell'entry: serve per convertire in locale.
function entryLocalDate(e) {
    var now = new Date();
    return new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), e.h, e.m, e.s));
}

function entryUtcHMS(e) {
    return pad2(e.h) + ':' + pad2(e.m) + ':' + pad2(e.s);
}

function entryLocalHMS(e) {
    var d = entryLocalDate(e);
    return pad2(d.getHours()) + ':' + pad2(d.getMinutes()) + ':' + pad2(d.getSeconds());
}

function formatLocalDateTime(d) {
    return pad2(d.getDate()) + '/' + pad2(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' +
            pad2(d.getHours()) + ':' + pad2(d.getMinutes()) + ':' + pad2(d.getSeconds());
}

// Crea una entry a partire da una Date locale (l'orario viene salvato in UTC).
function entryFromDate(d, exposure, gain, format, reps, bracket) {
    return {
        h: d.getUTCHours(), m: d.getUTCMinutes(), s: d.getUTCSeconds(),
        e: exposure, g: gain, f: format, n: reps, bracket: bracket
    };
}

// Genera gli scatti dal form (evento ripetuto + bracketing opzionale) e li
// aggiunge alla working list. Il bracketing espande ogni scatto in step
// CONSECUTIVI: ogni sub-scatto parte quando il precedente e' finito
// (durata = esposizione arrotondata al secondo + margine).
function generateShots() {
    var start = ($('#gen-start').val() || '').trim();
    var mm = start.match(/^(\d{1,2}):(\d{1,2})(?::(\d{1,2}))?$/);
    if (!mm) {
        defaultError(_('Ora inizio non valida, usa il formato HH:MM:SS'));
        return;
    }
    var count = parseInt($('#gen-count').val(), 10);
    var interval = parseInt($('#gen-interval').val(), 10);
    var exposure = parseInt($('#gen-exposure').val(), 10);
    var gain = parseInt($('#gen-gain').val(), 10);
    var format = parseInt($('#gen-format').val(), 10);
    if (!(count >= 1) || !(interval >= 0) || !(exposure >= 1) || !(gain >= 0) || !(format >= 0)) {
        defaultError(_('Controlla i campi del generatore: valori mancanti o non validi'));
        return;
    }

    var bracketing = $('#gen-bracketing').is(':checked');
    var bracketType = $('#gen-bracket-type').val();
    var bracketStep = parseInt($('#gen-bracket-step').val(), 10);
    var bracketSteps = parseInt($('#gen-bracket-steps').val(), 10);
    var bracketMargin = parseInt($('#gen-bracket-margin').val(), 10);
    if (bracketing && (!(bracketStep >= 1) || !(bracketSteps >= 2) || !(bracketMargin >= 0))) {
        defaultError(_('Controlla i campi del bracketing: passo, numero step e margine'));
        return;
    }

    var base = new Date();
    base.setHours(+mm[1], +mm[2], +(mm[3] || 0), 0);

    var warnings = [];
    var added = [];

    if (!bracketing && interval === 0 && count > 1) {
        // Scatti consecutivi identici: freeture li esprime con il campo n.
        added.push(entryFromDate(base, exposure, gain, format, count, null));
    } else {
        for (var i = 0; i < count; i++) {
            var shotStart = new Date(base.getTime() + i * interval * 1000);
            if (bracketing) {
                bracketGroupCounter++;
                var group = {group: bracketGroupCounter, type: bracketType};
                var cursor = new Date(shotStart.getTime());
                for (var j = 0; j < bracketSteps; j++) {
                    var exp = (bracketType === 'exposure') ? exposure + j * bracketStep : exposure;
                    var gn = (bracketType === 'gain') ? gain + j * bracketStep : gain;
                    added.push(entryFromDate(cursor, exp, gn, format, 1, group));
                    // Il sub-scatto successivo parte a esposizione conclusa + margine.
                    var durSec = Math.max(1, Math.ceil(exp / 1000000)) + bracketMargin;
                    cursor = new Date(cursor.getTime() + durSec * 1000);
                }
                var bracketSpanMs = cursor.getTime() - shotStart.getTime();
                if (i < count - 1 && bracketSpanMs > interval * 1000) {
                    warnings.push(_('Il bracketing dello scatto') + ' ' + (i + 1) + ' ' +
                            _('dura') + ' ' + Math.ceil(bracketSpanMs / 1000) + ' s, ' +
                            _('più dell\'intervallo tra gli scatti') + ' (' + interval + ' s): gli scatti si sovrappongono.');
                }
            } else {
                added.push(entryFromDate(shotStart, exposure, gain, format, 1, null));
            }
        }
    }

    // Avviso se la sequenza attraversa la mezzanotte UTC: la schedulazione
    // freeture e' giornaliera, quindi quei token scattano "prima" nel giorno.
    if (added.length > 1) {
        var firstSec = entryUtcSeconds(added[0]);
        var wrapped = added.some(function (e) {
            return entryUtcSeconds(e) < firstSec;
        });
        if (wrapped) {
            warnings.push(_('La sequenza supera la mezzanotte UTC: i token oltre la mezzanotte verranno eseguiti nelle prime ore del giorno (la schedulazione è giornaliera).'));
        }
    }

    scheduleEntries = scheduleEntries.concat(added);
    renderScheduleEditor(warnings);
}

// Ridisegna tabella preview + textarea (sempre sincronizzata con la lista).
function renderScheduleEditor(extraWarnings) {
    var $body = $('#schedule-preview-body');
    if (!$body.length) {
        return;
    }

    scheduleEntries.sort(function (a, b) {
        return entryUtcSeconds(a) - entryUtcSeconds(b);
    });

    $('#schedule-raw').val(serializeEntries());
    $('#schedule-count-badge').text(scheduleEntries.length + ' ' + _('schedulazioni'));

    // Orari duplicati: freeture identifica lo scatto per orario, due token con
    // lo stesso hh:mm:ss sono quasi certamente un errore.
    var timesCount = {};
    scheduleEntries.forEach(function (e) {
        var k = entryUtcHMS(e);
        timesCount[k] = (timesCount[k] || 0) + 1;
    });

    var rows = '';
    scheduleEntries.forEach(function (e, idx) {
        var t = entryUtcHMS(e);
        var dup = timesCount[t] > 1;
        var bracketLabel = '&mdash;';
        if (e.bracket) {
            bracketLabel = '<span class="label label-info">' + _('Gruppo') + ' ' + e.bracket.group + ' &middot; ' +
                    (e.bracket.type === 'exposure' ? _('Esposizione') : _('Gain')) + '</span>';
        }
        rows += '<tr' + (dup ? ' class="danger"' : '') + '>' +
                '<td>' + (idx + 1) + '</td>' +
                '<td><code>' + t + '</code>' + (dup ? ' <i class="fa fa-exclamation-triangle text-danger" title="' + _('Orario duplicato') + '"></i>' : '') + '</td>' +
                '<td>' + entryLocalHMS(e) + '</td>' +
                '<td>' + e.e + '</td>' +
                '<td>' + e.g + '</td>' +
                '<td>' + e.f + '</td>' +
                '<td>' + e.n + '</td>' +
                '<td>' + bracketLabel + '</td>' +
                '<td><center><button class="btn btn-xs btn-danger schedule-remove" data-idx="' + idx + '"><i class="fa fa-trash"></i></button></center></td>' +
                '</tr>';
    });
    if (rows === '') {
        rows = '<tr><td colspan="9"><em>' + _('Nessuna schedulazione: genera gli scatti o carica una stringa') + '</em></td></tr>';
    }
    $body.html(rows);

    var warnHtml = '';
    var dupTimes = Object.keys(timesCount).filter(function (k) {
        return timesCount[k] > 1;
    });
    if (dupTimes.length) {
        warnHtml += '<div class="alert alert-danger" style="margin: 5px 0;">' +
                '<i class="fa fa-exclamation-triangle"></i> ' + _('Orari UTC duplicati') + ': ' +
                dupTimes.map(function (k) { return '<code>' + k + '</code>'; }).join(', ') +
                '</div>';
    }
    (extraWarnings || []).forEach(function (w) {
        warnHtml += '<div class="alert alert-warning" style="margin: 5px 0;">' +
                '<i class="fa fa-exclamation-circle"></i> ' + w + '</div>';
    });
    $('#schedule-editor-warnings').html(warnHtml);
}

// Carica la stringa manuale (lunghezza illimitata) nella working list.
function loadRawIntoEditor() {
    var parsed = parseScheduleString($('#schedule-raw').val());
    if (parsed.errors.length) {
        defaultError(_('Token non riconosciuti') + ': ' + parsed.errors.join(', '));
    }
    scheduleEntries = parsed.entries;
    renderScheduleEditor();
}

// Banner stato schedulazione (verde attiva / arancio non attiva).
function renderScheduledActiveStatus(d) {
    var $box = $('#scheduled-active-status');
    if (!$box.length) {
        return;
    }
    if (d.enabled === true) {
        $box.html('<div class="alert alert-success" style="margin:0;">' +
                '<i class="fa fa-check"></i> ' + _('Acquisizione schedulata ATTIVA') +
                ' (' + (d.entries ? d.entries.length : 0) + ' ' + _('schedulazioni configurate') + ').' +
                '</div>');
        // Flag attivo ma la policy temperatura potrebbe sospendere gli scatti.
        fetchCameraTemperatureStatus(function (cam) {
            if (!cam || !cam.overheated) {
                return;
            }
            var detail = '';
            if (cam.temperature !== null) {
                detail = ' (' + _('temperatura attuale') + ' ' + cam.temperature + ' °C' +
                        (cam.threshold !== null ? ', ' + _('soglia') + ' ' + cam.threshold + ' °C' : '') + ')';
            }
            $box.html('<div class="alert alert-danger" style="margin:0;">' +
                    '<i class="fa fa-thermometer-full"></i> <b>' +
                    _('Le acquisizioni schedulate dovrebbero essere attive ma sono SOSPESE dal controllo temperatura') +
                    '</b>' + detail + '.' +
                    '</div>');
        });
    } else if (d.enabled === false) {
        var extra = d.regularEnabled === true ? (' ' + _('È attiva l\'acquisizione regolare.')) : '';
        $box.html('<div class="alert alert-warning" style="margin:0;">' +
                '<i class="fa fa-exclamation-triangle"></i> <b>' + _('Le acquisizioni schedulate NON sono attive') + '</b> ' +
                '(ACQ_SCHEDULE_ENABLED = false).' + extra +
                '</div>');
    } else {
        $box.html('<div class="alert alert-warning" style="margin:0;">' +
                _('Impossibile determinare lo stato della schedulazione (ACQ_SCHEDULE_ENABLED assente in configurazione).') +
                '</div>');
    }
}

// Tabella dei prossimi 3 scatti in ORA LOCALE (calcolati dalla configurazione
// salvata: ogni token scatta ogni giorno al proprio orario UT).
function renderNextShots(d) {
    var $body = $('#next-shots-body');
    if (!$body.length) {
        return;
    }
    var entries = d.entries || [];
    if (!entries.length) {
        $body.html('<tr><td colspan="6"><em>' + _('Nessuna schedulazione configurata') + '</em></td></tr>');
        return;
    }
    var now = new Date();
    var shots = entries.map(function (e) {
        var occ = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), e.h, e.m, e.s));
        if (occ <= now) {
            occ = new Date(occ.getTime() + 86400000);
        }
        return {when: occ, e: e};
    }).sort(function (a, b) {
        return a.when - b.when;
    }).slice(0, 3);

    var rows = '';
    shots.forEach(function (s) {
        rows += '<tr>' +
                '<td><b>' + formatLocalDateTime(s.when) + '</b></td>' +
                '<td><code>' + entryUtcHMS(s.e) + '</code></td>' +
                '<td>' + s.e.e + '</td>' +
                '<td>' + s.e.g + '</td>' +
                '<td>' + s.e.f + '</td>' +
                '<td>' + s.e.n + '</td>' +
                '</tr>';
    });
    if (d.enabled !== true) {
        rows += '<tr><td colspan="6"><small class="text-muted">' +
                _('Nota: la schedulazione non è attiva, questi scatti non verranno eseguiti.') +
                '</small></td></tr>';
    }
    $body.html(rows);
}

// Carica lo stato schedulazione dal backend; se applyToEditor riempie anche
// l'editor con le entries correnti della configurazione.
function loadSchedule(applyToEditor) {
    $.get('/lib/scheduled/V2/scheduled/schedule', function (json) {
        var resp = JSON.parse(json);
        if (!resp || !resp.result || !resp.data) {
            $('#scheduled-active-status').html('<div class="alert alert-warning" style="margin:0;">' +
                    _('Impossibile leggere lo stato della schedulazione dal server.') + '</div>');
            return;
        }
        var d = resp.data;
        savedSchedule = d;
        renderScheduledActiveStatus(d);
        renderNextShots(d);
        if (applyToEditor && $('#schedule-preview-body').length) {
            scheduleEntries = (d.entries || []).map(function (e) {
                e.bracket = null;
                return e;
            });
            $('#schedule-enabled').prop('checked', d.enabled === true);
            var warnings = [];
            if (d.invalidTokens && d.invalidTokens.length) {
                warnings.push(_('Token non validi in configurazione (ignorati)') + ': ' + d.invalidTokens.join(', '));
            }
            renderScheduleEditor(warnings);
        }
    }).fail(function () {
        $('#scheduled-active-status').html('<div class="alert alert-warning" style="margin:0;">' +
                _('Impossibile leggere lo stato della schedulazione dal server.') + '</div>');
    });
}

// Salva la working list su configuration.cfg (riavvia freeture lato server).
function saveSchedule() {
    var enabled = $('#schedule-enabled').is(':checked');
    if (enabled && scheduleEntries.length === 0) {
        defaultError(_('Impossibile abilitare la schedulazione con un elenco vuoto'));
        return;
    }
    var msg = _('Salvare la schedulazione su configuration.cfg?') + ' ' +
            (enabled ? _('L\'acquisizione regolare verrà disabilitata.') + ' ' : '') +
            _('Il container freeture verrà riavviato.');
    var doSave = function () {
        var payload = JSON.stringify({raw: serializeEntries(), enabled: enabled});
        genericPost('/lib/scheduled/V2/scheduled/schedule', payload, function () {
            loadSchedule(true);
        });
    };
    alertConfirm(_('Conferma'), msg, doSave);
}

$(document).ready(function () {
    // Stato + prossimi scatti (visibili a tutti); editor solo se presente (admin).
    loadSchedule(true);

    if ($('#gen-add').length) {
        $('#gen-bracketing').on('change', function () {
            $('.bracketing-options').toggle($(this).is(':checked'));
        });
        $('#gen-add').on('click', function (e) {
            e.preventDefault();
            generateShots();
        });
        $('#raw-load').on('click', function (e) {
            e.preventDefault();
            loadRawIntoEditor();
        });
        $('#schedule-clear').on('click', function (e) {
            e.preventDefault();
            scheduleEntries = [];
            renderScheduleEditor();
        });
        $('#schedule-reload').on('click', function (e) {
            e.preventDefault();
            loadSchedule(true);
        });
        $('#schedule-save').on('click', function (e) {
            e.preventDefault();
            saveSchedule();
        });
        $('#schedule-preview-body').on('click', '.schedule-remove', function () {
            var idx = parseInt($(this).attr('data-idx'), 10);
            scheduleEntries.splice(idx, 1);
            renderScheduleEditor();
        });
    }
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
