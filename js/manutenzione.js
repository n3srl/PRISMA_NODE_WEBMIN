/**
 *
 * @author: N3 S.r.l.
 */

$(document).ready(function () {

});

function exec_reboot() {
    var baseUrl = "/lib/camera/V1/camera/rebootServer"; //da modificare

    $.ajax({
        url: baseUrl,
        type: 'POST',
        success: function(json) {
            try {
                var data = JSON.parse(json);
                if (data) {
                    alert(data.data.data);
                }
            }  catch (error) {
                console.error(error);
                alert("Errore nella risposta del server: " + json);
            }
        },
        error: function(xhr, status, error) {
            alert("Errore nella richiesta: " + error);
        }
    });
}

/* ============================================================
 * Migrazione configurazione DEFAULT -> configurazione attuale
 * ============================================================ */

// Cache dell'ultimo scan (per evitare di rifare il GET prima del run).
var defaultMigrationLastScan = null;

function _escHtml(s) {
    return $('<div>').text(s == null ? '' : String(s)).html();
}

function _csrfTokenForMigration() {
    // Replica del pattern usato in network.js: GET /lib/core/v1/csfr -> { data: { token } }
    return $.ajax({
        url: '/lib/core/v1/csfr',
        method: 'GET',
        dataType: 'json'
    }).then(function (resp) {
        if (resp && resp.result && resp.data && resp.data.token) {
            return resp.data.token;
        }
        return $.Deferred().reject('CSRF token missing');
    });
}

function _renderMigrationRows(items, opts) {
    opts = opts || {};
    var previewOnly = !!opts.previewOnly;
    var $tbody = $('#DefaultMigrationList tbody');
    if (!items || !items.length) {
        $tbody.html('<tr><td colspan="4" class="text-center text-muted">' +
            _('Nessun elemento da migrare') + '</td></tr>');
        return;
    }
    var rows = items.map(function (it) {
        var typeLabel = it.type === 'file' ? _('File')
                      : it.type === 'event_dir' ? _('Cartella evento')
                      : it.type === 'day_dir' ? _('Cartella giorno')
                      : it.type === 'root_merge' ? _('Unione contenuto root')
                      : it.type === 'root_dir' ? _('Cartella root') : it.type;

        var stateLabel, stateColor;
        if (it.status === 'renamed') { stateLabel = _('Rinominato'); stateColor = '#1d7a44'; }
        else if (it.status === 'merged') { stateLabel = _('Unito') + (it.message ? ' (' + it.message + ')' : ''); stateColor = '#1d7a44'; }
        else if (it.status === 'error') { stateLabel = _('Errore') + ': ' + (it.message || ''); stateColor = '#b52c1d'; }
        else if (it.status === 'skipped') { stateLabel = _('Saltato') + ': ' + (it.message || ''); stateColor = '#b07d00'; }
        else if (previewOnly) { stateLabel = _('Anteprima (config non ancora valida)'); stateColor = '#666'; }
        else if (it.type === 'root_merge') { stateLabel = _('Da unire (destinazione gia\' esistente)'); stateColor = '#666'; }
        else if (it.conflict) { stateLabel = _('Conflitto: destinazione esistente'); stateColor = '#b07d00'; }
        else { stateLabel = _('Da migrare'); stateColor = '#666'; }

        var afterPath = it.final_path || it.new_path;
        return '<tr>' +
            '<td>' + _escHtml(typeLabel) + '</td>' +
            '<td style="word-break:break-all"><code>' + _escHtml(it.old_path) + '</code></td>' +
            '<td style="word-break:break-all"><code>' + _escHtml(afterPath) + '</code></td>' +
            '<td style="color:' + stateColor + '">' + _escHtml(stateLabel) + '</td>' +
            '</tr>';
    }).join('');
    $tbody.html(rows);
}

function _renderMigrationStatus(payload) {
    var $s = $('#default-migration-status');
    if (!payload) { $s.empty(); return; }
    if (!payload.rootExists) {
        $s.html('<div class="alert alert-info">' +
            _('Nessuna cartella /freeture/DEFAULT trovata: niente da migrare.') +
            '</div>');
        return;
    }
    var nItems = (payload.items || []).length;
    if (!payload.configIsValid) {
        $s.html('<div class="alert alert-warning">' +
            _('La configurazione freeture corrente è ancora DEFAULT') +
            ' (STATION_CODE=<b>' + _escHtml(payload.stationCode) + '</b>, STATION_NAME=<b>' + _escHtml(payload.stationName) + '</b>). ' +
            _('Configura prima la stazione per poter eseguire la migrazione.') + ' ' +
            _('Qui sotto trovi comunque l\'anteprima dei dataset DEFAULT presenti sul nodo') +
            ' (<b>' + nItems + '</b> ' + _('elementi') + '): ' +
            _('i path "dopo migrazione" usano segnaposto <STATION_CODE> / <STATION_NAME> che verranno sostituiti con i valori reali una volta configurata la stazione.') +
            '</div>');
        return;
    }
    $s.html('<div class="alert alert-success">' +
        _('Configurazione attuale') + ': STATION_CODE=<b>' + _escHtml(payload.stationCode) +
        '</b>, STATION_NAME=<b>' + _escHtml(payload.stationName) + '</b>. ' +
        _('Elementi da migrare') + ': <b>' + nItems + '</b>.' +
        '</div>');
}

function loadDefaultMigrationPreview() {
    var $status = $('#default-migration-status');
    var $btnRun = $('#btn-run-default-migration');
    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Scansione in corso...') + '</div>');
    $('#DefaultMigrationList tbody').html(
        '<tr><td colspan="4" class="text-center text-muted">' + _('Caricamento...') + '</td></tr>'
    );

    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/migration/default/scan',
        method: 'GET',
        dataType: 'json'
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $status.html('<div class="alert alert-danger">' +
                _('Errore durante lo scan') + (resp && resp.data ? ': ' + _escHtml(resp.data) : '') +
                '</div>');
            $('#DefaultMigrationList tbody').html(
                '<tr><td colspan="4" class="text-center text-muted">—</td></tr>'
            );
            return;
        }
        var payload = resp.data || {};
        defaultMigrationLastScan = payload;
        _renderMigrationStatus(payload);
        _renderMigrationRows(payload.items || [], { previewOnly: !payload.configIsValid });
        var canRun = payload.rootExists && payload.configIsValid && (payload.items || []).length > 0;
        $btnRun.prop('disabled', !canRun);
    }).fail(function (xhr) {
        var msg = xhr && xhr.responseText ? xhr.responseText : '';
        $status.html('<div class="alert alert-danger">' +
            _('Errore HTTP durante lo scan') + ' ' + _escHtml(msg) + '</div>');
    });
}

function runDefaultMigration() {
    if (!defaultMigrationLastScan || !defaultMigrationLastScan.items || !defaultMigrationLastScan.items.length) {
        alert(_('Esegui prima lo scan ("Aggiorna lista").'));
        return;
    }
    var nItems = defaultMigrationLastScan.items.length;
    var stationCode = defaultMigrationLastScan.stationCode;
    var stationName = defaultMigrationLastScan.stationName;

    var msg = _('Confermi la migrazione di') + ' ' + nItems + ' ' +
              _('elementi verso STATION_CODE=') + stationCode +
              ' / STATION_NAME=' + stationName + '?\n\n' +
              _('Eventuali destinazioni già esistenti verranno saltate.');
    if (!confirm(msg)) {
        return;
    }

    var $btnRun = $('#btn-run-default-migration');
    var $status = $('#default-migration-status');
    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Migrazione in corso...') + '</div>');

    _csrfTokenForMigration().then(function (token) {
        return $.ajax({
            url: '/lib/manutenzione/V2/manutenzione/migration/default/run',
            method: 'POST',
            data: JSON.stringify({ token: token }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        });
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $status.html('<div class="alert alert-danger">' +
                _('Errore durante la migrazione') + (resp && resp.data ? ': ' + _escHtml(resp.data) : '') +
                '</div>');
            return;
        }
        var results = resp.data || [];
        var renamed = results.filter(function (r) { return r.status === 'renamed'; }).length;
        var skipped = results.filter(function (r) { return r.status === 'skipped'; }).length;
        var errors  = results.filter(function (r) { return r.status === 'error'; }).length;

        var cls = errors > 0 ? 'alert-warning' : 'alert-success';
        $status.html('<div class="alert ' + cls + '">' +
            _('Migrazione completata.') + ' ' +
            _('Rinominati') + ': <b>' + renamed + '</b>, ' +
            _('Saltati') + ': <b>' + skipped + '</b>, ' +
            _('Errori') + ': <b>' + errors + '</b>.' +
            '</div>');
        _renderMigrationRows(results);
        // Forza un nuovo scan per riallinearsi al filesystem.
        defaultMigrationLastScan = null;
    }).fail(function (xhr) {
        var msg = xhr && xhr.responseText ? xhr.responseText : '';
        $status.html('<div class="alert alert-danger">' +
            _('Errore HTTP durante la migrazione') + ' ' + _escHtml(msg) + '</div>');
    }).always(function () {
        // Lascio il bottone disabilitato finchè un nuovo scan non conferma che c'è ancora lavoro da fare.
    });
}
