/**
 *
 * @author: N3 S.r.l.
 */

$(document).ready(function () {
    // Solo nella pagina di manutenzione (dove esistono i pannelli).
    if ($('#migration-src-code').length) {
        loadMigrationSources();
    }
    if ($('#fits-src-code').length) {
        loadFitsSources();
    }
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
 * Migrazione dati stazione: SORGENTE -> configurazione attuale
 * ============================================================ */

// Cache dell'ultimo scan (per evitare di rifare il GET prima del run).
var defaultMigrationLastScan = null;

// Token usato come sorgente di default (nodo non ancora configurato).
var migrationDefaultToken = 'DEFAULT';

function _selectedMigrationSrcCode() {
    return ($('#migration-src-code').val() || '').trim();
}

function _selectedMigrationSrcName() {
    return ($('#migration-src-name').val() || '').trim();
}

// Popola il dropdown delle cartelle stazione sorgenti presenti sotto /freeture.
function loadMigrationSources() {
    var $sel = $('#migration-src-code');
    $sel.html('<option value="">' + _('Caricamento...') + '</option>');

    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/migration/sources',
        method: 'GET',
        dataType: 'json'
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $sel.html('<option value="">' + _('Errore nel caricamento delle sorgenti') + '</option>');
            return;
        }
        var payload = resp.data || {};
        migrationDefaultToken = payload.defaultToken || 'DEFAULT';
        var sources = payload.sources || [];
        var dstCode = payload.stationCode;

        if (!sources.length) {
            $sel.html('<option value="">' + _('Nessuna cartella in /freeture') + '</option>');
        } else {
            var opts = sources.map(function (s) {
                // Marca la cartella corrispondente alla destinazione corrente (non migrabile su se stessa).
                var isDst = (s === dstCode);
                var label = _escHtml(s) + (isDst ? ' (' + _('destinazione attuale') + ')' : '');
                return '<option value="' + _escHtml(s) + '">' + label + '</option>';
            }).join('');
            $sel.html(opts);

            // Preseleziona DEFAULT se presente, altrimenti la prima sorgente != destinazione.
            if (sources.indexOf(migrationDefaultToken) !== -1) {
                $sel.val(migrationDefaultToken);
            } else {
                var firstNonDst = sources.filter(function (s) { return s !== dstCode; });
                $sel.val(firstNonDst.length ? firstNonDst[0] : sources[0]);
            }
        }
        onMigrationSourceChange();
        // Carica subito l'anteprima per la sorgente preselezionata.
        loadDefaultMigrationPreview();
    }).fail(function () {
        $sel.html('<option value="">' + _('Errore HTTP nel caricamento delle sorgenti') + '</option>');
    });
}

// Quando cambia la cartella sorgente: precompila il nome (per DEFAULT coincide col codice)
// e invalida la cache dello scan precedente.
function onMigrationSourceChange() {
    var code = _selectedMigrationSrcCode();
    var $name = $('#migration-src-name');
    // Suggerimento: per la sorgente DEFAULT codice e nome coincidono.
    if (code === migrationDefaultToken) {
        $name.val(migrationDefaultToken);
    } else if (!$name.val() || $name.val() === migrationDefaultToken) {
        // Per una sorgente reale il nome di solito differisce dal codice: lascio modificare.
        $name.val(code);
    }
    defaultMigrationLastScan = null;
    $('#btn-run-default-migration').prop('disabled', true);
}

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
            _('Nessuna cartella') + ' <code>' + _escHtml(payload.sourceRoot) + '</code> ' +
            _('trovata: niente da migrare per questa sorgente.') +
            '</div>');
        return;
    }
    var nShown = (payload.items || []).length;
    var nTotal = (typeof payload.total === 'number') ? payload.total : nShown;
    var truncated = !!payload.truncated;
    var truncationHtml = truncated
        ? (' <span style="color:#b07d00;">' +
           _('(visualizzati solo i primi') + ' <b>' + nShown + '</b> ' +
           _('di') + ' <b>' + nTotal + '</b> ' +
           _('per non sovraccaricare il browser; la migrazione li processerà comunque tutti') +
           ')</span>')
        : '';

    if (!payload.configIsValid) {
        $s.html('<div class="alert alert-warning">' +
            _('La configurazione freeture di destinazione è ancora DEFAULT') +
            ' (STATION_CODE=<b>' + _escHtml(payload.stationCode) + '</b>, STATION_NAME=<b>' + _escHtml(payload.stationName) + '</b>). ' +
            _('Configura prima la stazione per poter eseguire la migrazione.') + ' ' +
            _('Qui sotto trovi comunque l\'anteprima dei dataset della sorgente') +
            ' <b>' + _escHtml(payload.srcCode) + '</b> (<b>' + nTotal + '</b> ' + _('elementi') + ')' + truncationHtml + ': ' +
            _('i path "dopo migrazione" usano segnaposto <STATION_CODE> / <STATION_NAME> che verranno sostituiti con i valori reali una volta configurata la stazione.') +
            '</div>');
        return;
    }
    $s.html('<div class="alert alert-success">' +
        _('Sorgente') + ': STATION_CODE=<b>' + _escHtml(payload.srcCode) +
        '</b>, STATION_NAME=<b>' + _escHtml(payload.srcName) + '</b> &rarr; ' +
        _('Destinazione') + ': STATION_CODE=<b>' + _escHtml(payload.stationCode) +
        '</b>, STATION_NAME=<b>' + _escHtml(payload.stationName) + '</b>. ' +
        _('Elementi da migrare') + ': <b>' + nTotal + '</b>.' + truncationHtml +
        '</div>');
}

function loadDefaultMigrationPreview() {
    var $status = $('#default-migration-status');
    var $btnRun = $('#btn-run-default-migration');
    var srcCode = _selectedMigrationSrcCode();
    var srcName = _selectedMigrationSrcName();

    if (!srcCode) {
        $status.html('<div class="alert alert-info">' + _('Seleziona una cartella stazione sorgente.') + '</div>');
        $('#DefaultMigrationList tbody').html(
            '<tr><td colspan="4" class="text-center text-muted">—</td></tr>'
        );
        $btnRun.prop('disabled', true);
        return;
    }

    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Scansione in corso...') + '</div>');
    $('#DefaultMigrationList tbody').html(
        '<tr><td colspan="4" class="text-center text-muted">' + _('Caricamento...') + '</td></tr>'
    );

    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/migration/scan',
        method: 'GET',
        data: { srcCode: srcCode, srcName: srcName },
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
    var nItems = (typeof defaultMigrationLastScan.total === 'number')
        ? defaultMigrationLastScan.total
        : defaultMigrationLastScan.items.length;
    var srcCode = defaultMigrationLastScan.srcCode;
    var srcName = defaultMigrationLastScan.srcName;
    var stationCode = defaultMigrationLastScan.stationCode;
    var stationName = defaultMigrationLastScan.stationName;

    var msg = _('Confermi la migrazione di') + ' ' + nItems + ' ' +
              _('elementi dalla sorgente') + ' ' + srcCode + ' / ' + srcName +
              ' ' + _('verso STATION_CODE=') + stationCode +
              ' / STATION_NAME=' + stationName + '?\n\n' +
              _('Eventuali destinazioni già esistenti verranno saltate.');
    if (!confirm(msg)) {
        return;
    }

    var $btnRun = $('#btn-run-default-migration');
    var $status = $('#default-migration-status');
    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Migrazione in corso...') + '</div>');

    _showMigrationProgress(0, nItems, 'starting');
    var pollHandle = setInterval(_pollMigrationProgress, 500);

    _csrfTokenForMigration().then(function (token) {
        return $.ajax({
            url: '/lib/manutenzione/V2/manutenzione/migration/run',
            method: 'POST',
            data: JSON.stringify({ token: token, srcCode: srcCode, srcName: srcName }),
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
        var merged  = results.filter(function (r) { return r.status === 'merged'; }).length;
        var skipped = results.filter(function (r) { return r.status === 'skipped'; }).length;
        var errors  = results.filter(function (r) { return r.status === 'error'; }).length;

        var cls = errors > 0 ? 'alert-warning' : 'alert-success';
        $status.html('<div class="alert ' + cls + '">' +
            _('Migrazione completata.') + ' ' +
            _('Rinominati') + ': <b>' + renamed + '</b>, ' +
            _('Uniti') + ': <b>' + merged + '</b>, ' +
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
        clearInterval(pollHandle);
        // Lascio la progress bar visibile al 100% per qualche secondo, poi la nascondo.
        _pollMigrationProgress(); // ultimo refresh per portare la barra a 100%/error
        setTimeout(_hideMigrationProgress, 2500);
    });
}

// Aggiornamento visuale della progress bar di migrazione.
function _showMigrationProgress(processed, total, phase) {
    var pct = (total > 0) ? Math.round((processed / total) * 100) : 0;
    var $bar  = $('#default-migration-progress-bar');
    var $txt  = $('#default-migration-progress-text');
    $('#default-migration-progress-wrap').show();
    $bar.css('width', pct + '%').attr('aria-valuenow', pct).text(pct + '%');
    if (phase === 'merging') {
        $txt.text(_('Unione finale della cartella sorgente nella destinazione...'));
    } else if (phase === 'starting') {
        $txt.text(_('Avvio...') + ' (' + processed + '/' + total + ')');
    } else if (phase === 'done') {
        $txt.text(_('Completato') + ' (' + processed + '/' + total + ').');
    } else if (phase === 'error') {
        $bar.removeClass('progress-bar-striped active').addClass('progress-bar-danger');
        $txt.text(_('Errore durante la migrazione.'));
    } else {
        $txt.text(_('Elementi processati') + ': ' + processed + ' / ' + total);
    }
}
function _hideMigrationProgress() {
    var $bar = $('#default-migration-progress-bar');
    $bar.removeClass('progress-bar-danger').addClass('progress-bar-striped active')
        .css('width', '0%').attr('aria-valuenow', 0).text('0%');
    $('#default-migration-progress-text').text('');
    $('#default-migration-progress-wrap').hide();
}
function _pollMigrationProgress() {
    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/migration/progress',
        method: 'GET',
        dataType: 'json',
        cache: false
    }).done(function (resp) {
        if (!resp || !resp.result || !resp.data) { return; }
        var d = resp.data;
        if (d.idle) { return; }
        _showMigrationProgress(d.processed || 0, d.total || 0, d.phase || 'running');
    });
}

/* ============================================================
 * Riallineamento header FITS a configuration.cfg
 * ============================================================ */

// Cache dell'ultimo scan FITS.
var fitsHeaderLastScan = null;

function _selectedFitsSrcCode() {
    return ($('#fits-src-code').val() || '').trim();
}

// Popola il dropdown delle cartelle stazione per il pannello FITS (riusa l'endpoint sources).
function loadFitsSources() {
    var $sel = $('#fits-src-code');
    $sel.html('<option value="">' + _('Caricamento...') + '</option>');

    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/migration/sources',
        method: 'GET',
        dataType: 'json'
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $sel.html('<option value="">' + _('Errore nel caricamento delle sorgenti') + '</option>');
            return;
        }
        var payload = resp.data || {};
        var sources = payload.sources || [];
        var dstCode = payload.stationCode;
        var defToken = payload.defaultToken || 'DEFAULT';

        if (!sources.length) {
            $sel.html('<option value="">' + _('Nessuna cartella in /freeture') + '</option>');
            onFitsSourceChange();
            return;
        }

        var opts = sources.map(function (s) {
            var isDst = (s === dstCode);
            var label = _escHtml(s) + (isDst ? ' (' + _('stazione attuale') + ')' : '');
            return '<option value="' + _escHtml(s) + '">' + label + '</option>';
        }).join('');
        $sel.html(opts);

        // Per il riallineamento header conviene partire dalla stazione ATTUALE (destinazione):
        // i suoi file vanno allineati alla config corrente. Fallback: DEFAULT, poi prima voce.
        if (dstCode && sources.indexOf(dstCode) !== -1) {
            $sel.val(dstCode);
        } else if (sources.indexOf(defToken) !== -1) {
            $sel.val(defToken);
        } else {
            $sel.val(sources[0]);
        }
        onFitsSourceChange();
        loadFitsHeaderPreview();
    }).fail(function () {
        $sel.html('<option value="">' + _('Errore HTTP nel caricamento delle sorgenti') + '</option>');
    });
}

function onFitsSourceChange() {
    fitsHeaderLastScan = null;
    $('#btn-run-fits-header').prop('disabled', true);
}

function _renderFitsStatus(payload) {
    var $s = $('#fits-header-status');
    if (!payload) { $s.empty(); return; }
    if (!payload.rootExists) {
        $s.html('<div class="alert alert-info">' +
            _('Nessuna cartella') + ' <code>' + _escHtml(payload.sourceRoot) + '</code> ' +
            _('trovata.') + '</div>');
        return;
    }
    var summary = payload.summary || [];
    var kwToChange = summary.filter(function (r) { return r.filesToChange > 0; }).length;
    var coverage = payload.scannedFiles + (payload.capped ? ' (' + _('campione') + ')' : '') +
                   ' / ' + payload.totalFiles;
    var cls = kwToChange > 0 ? 'alert-success' : 'alert-info';
    var extra = '';
    if (payload.errors && payload.errors.length) {
        extra = ' <span class="text-danger">(' + payload.errors.length + ' ' + _('file non leggibili') + ')</span>';
    }
    $s.html('<div class="alert ' + cls + '">' +
        _('Cartella') + ': <b>' + _escHtml(payload.srcCode) + '</b>. ' +
        _('File .fit analizzati') + ': <b>' + _escHtml(String(coverage)) + '</b>. ' +
        _('Keyword da aggiornare') + ': <b>' + kwToChange + '</b> ' + _('su') + ' ' + summary.length + '.' +
        extra + '</div>');
}

function _renderFitsRows(summary) {
    var $tbody = $('#FitsHeaderList tbody');
    if (!summary || !summary.length) {
        $tbody.html('<tr><td colspan="4" class="text-center text-muted">' +
            _('Nessuna keyword') + '</td></tr>');
        return;
    }
    var rows = summary.map(function (r) {
        var old = (r.sampleOldValue === null || r.sampleOldValue === '') ? '∅' : r.sampleOldValue;
        var changeCell, color;
        if (r.filesWithKey === 0) {
            changeCell = _('non presente negli header');
            color = '#999';
        } else if (r.filesToChange > 0) {
            changeCell = '<b>' + r.filesToChange + '</b> / ' + r.filesWithKey;
            color = '#1d7a44';
        } else {
            changeCell = _('già allineata') + ' (' + r.filesWithKey + ')';
            color = '#666';
        }
        var distinct = (!r.perFile && r.distinctOldVals > 1)
            ? ' <span class="text-muted">(' + r.distinctOldVals + ' ' + _('valori diversi') + ')</span>'
            : '';
        return '<tr>' +
            '<td><code>' + _escHtml(r.keyword) + '</code></td>' +
            '<td style="word-break:break-all"><code>' + _escHtml(old) + '</code>' + distinct + '</td>' +
            '<td style="word-break:break-all"><code>' + _escHtml(r.newValue) + '</code></td>' +
            '<td style="color:' + color + '">' + changeCell + '</td>' +
            '</tr>';
    }).join('');
    $tbody.html(rows);
}

function loadFitsHeaderPreview() {
    var $status = $('#fits-header-status');
    var $btnRun = $('#btn-run-fits-header');
    var srcCode = _selectedFitsSrcCode();

    if (!srcCode) {
        $status.html('<div class="alert alert-info">' + _('Seleziona una cartella stazione.') + '</div>');
        $('#FitsHeaderList tbody').html('<tr><td colspan="4" class="text-center text-muted">—</td></tr>');
        $btnRun.prop('disabled', true);
        return;
    }

    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Scansione header in corso...') + '</div>');
    $('#FitsHeaderList tbody').html(
        '<tr><td colspan="4" class="text-center text-muted">' + _('Caricamento...') + '</td></tr>'
    );

    $.ajax({
        url: '/lib/manutenzione/V2/manutenzione/fits/scan',
        method: 'GET',
        data: { srcCode: srcCode },
        dataType: 'json'
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $status.html('<div class="alert alert-danger">' +
                _('Errore durante lo scan') + (resp && resp.data ? ': ' + _escHtml(resp.data) : '') +
                '</div>');
            $('#FitsHeaderList tbody').html('<tr><td colspan="4" class="text-center text-muted">—</td></tr>');
            return;
        }
        var payload = resp.data || {};
        fitsHeaderLastScan = payload;
        _renderFitsStatus(payload);
        _renderFitsRows(payload.summary || []);
        var kwToChange = (payload.summary || []).filter(function (r) { return r.filesToChange > 0; }).length;
        var canRun = payload.rootExists && kwToChange > 0;
        $btnRun.prop('disabled', !canRun);
    }).fail(function (xhr) {
        var msg = xhr && xhr.responseText ? xhr.responseText : '';
        $status.html('<div class="alert alert-danger">' +
            _('Errore HTTP durante lo scan') + ' ' + _escHtml(msg) + '</div>');
    });
}

function runFitsHeader() {
    if (!fitsHeaderLastScan || !fitsHeaderLastScan.summary) {
        alert(_('Esegui prima lo scan ("Aggiorna lista").'));
        return;
    }
    var srcCode = fitsHeaderLastScan.srcCode;
    var summary = fitsHeaderLastScan.summary || [];
    var kwToChange = summary.filter(function (r) { return r.filesToChange > 0; }).length;
    if (kwToChange === 0) {
        alert(_('Nessuna keyword da aggiornare.'));
        return;
    }

    var msg = _('Confermi l\'aggiornamento degli header FITS sotto') + ' ' + fitsHeaderLastScan.sourceRoot + '?\n\n' +
              _('Verranno riscritti i valori delle keyword elencate, allineandoli a configuration.cfg.') + '\n' +
              (fitsHeaderLastScan.capped ? _('NB: l\'anteprima è su un campione; l\'operazione processerà tutti i file.') + '\n' : '') +
              _('L\'operazione modifica i file in modo permanente (nessun backup).');
    if (!confirm(msg)) {
        return;
    }

    var $btnRun = $('#btn-run-fits-header');
    var $status = $('#fits-header-status');
    $btnRun.prop('disabled', true);
    $status.html('<div class="alert alert-info">' + _('Aggiornamento header in corso...') + '</div>');

    _csrfTokenForMigration().then(function (token) {
        return $.ajax({
            url: '/lib/manutenzione/V2/manutenzione/fits/run',
            method: 'POST',
            data: JSON.stringify({ token: token, srcCode: srcCode }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        });
    }).done(function (resp) {
        if (!resp || !resp.result) {
            $status.html('<div class="alert alert-danger">' +
                _('Errore durante l\'aggiornamento') + (resp && resp.data ? ': ' + _escHtml(resp.data) : '') +
                '</div>');
            return;
        }
        var r = resp.data || {};
        var cls = (r.filesError > 0) ? 'alert-warning' : 'alert-success';
        var extra = '';
        if (r.errors && r.errors.length) {
            extra = '<br><small class="text-danger">' +
                r.errors.slice(0, 5).map(_escHtml).join('<br>') +
                (r.errors.length > 5 ? '<br>[...]' : '') + '</small>';
        }
        $status.html('<div class="alert ' + cls + '">' +
            _('Aggiornamento completato.') + ' ' +
            _('File modificati') + ': <b>' + (r.filesChanged || 0) + '</b>, ' +
            _('invariati') + ': <b>' + (r.filesSkipped || 0) + '</b>, ' +
            _('errori') + ': <b>' + (r.filesError || 0) + '</b>. ' +
            _('Card riscritte') + ': <b>' + (r.cardsWritten || 0) + '</b>.' +
            extra + '</div>');
        // Rilancio lo scan per riallinearsi (ora le keyword risulteranno già allineate).
        fitsHeaderLastScan = null;
        loadFitsHeaderPreview();
    }).fail(function (xhr) {
        var msg = xhr && xhr.responseText ? xhr.responseText : '';
        $status.html('<div class="alert alert-danger">' +
            _('Errore HTTP durante l\'aggiornamento') + ' ' + _escHtml(msg) + '</div>');
    });
}
