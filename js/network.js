/**
 * Network configuration page.
 *
 * Loads current /etc/network/interfaces state and arv-tool-0.8 camera list,
 * lets the admin switch between DHCP and static for either, previews the
 * resulting changes in a modal, then applies via the CSRF-protected endpoints.
 */

var pendingAction = null; // {kind: 'node'|'camera', payload: {...}}
var appliedSuccessfully = false;

function escHtml(s) {
    return $('<div/>').text(s == null ? '' : String(s)).html();
}

function diffNodeContents(oldText, newText) {
    var a = (oldText || '').split('\n');
    var b = (newText || '').split('\n');
    var max = Math.max(a.length, b.length);
    var lines = [];
    for (var i = 0; i < max; i++) {
        var av = a[i] == null ? '' : a[i];
        var bv = b[i] == null ? '' : b[i];
        if (av === bv) {
            lines.push('<span style="color:#666;">  ' + escHtml(av) + '</span>');
        } else {
            if (av !== '') {
                lines.push('<span style="color:#b52c1d; background:#fdeaea;">- ' + escHtml(av) + '</span>');
            }
            if (bv !== '') {
                lines.push('<span style="color:#1d7a44; background:#eaf7ee;">+ ' + escHtml(bv) + '</span>');
            }
        }
    }
    return '<pre style="max-height:400px; overflow:auto; font-family:monospace; white-space:pre-wrap;">' +
           lines.join('\n') +
           '</pre>';
}

function fetchCsrf() {
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

function postWithCsrf(url, data) {
    return fetchCsrf().then(function (token) {
        return $.ajax({
            url: url,
            method: 'POST',
            type: 'POST',
            data: JSON.stringify({ token: token, data: data }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json'
        });
    });
}

/* ---------------- WIRED STATUS ---------------- */

function showWiredNetworkStatus() {
    var $el = $('#status-wired-content');
    $.ajax({
        url: '/lib/network/V2/network/wired_status',
        type: 'GET',
        success: function (res) {
            var data;
            try { data = JSON.parse(res).data || []; } catch (e) { data = []; }
            $el.html(renderWiredInterfaces(data));
        },
        error: function () {
            $el.html('<p class="text-muted">' + _('Impossibile leggere lo stato della rete') + '</p>');
        }
    });
}

function renderWiredInterfaces(list) {
    if (!list.length) {
        return '<p class="text-muted">' + _('Nessuna interfaccia configurata') + '</p>';
    }
    var blocks = [];
    list.forEach(function (iface) {
        var label, color;
        if (!iface.present) {
            label = _('NON PRESENTE');
            color = '#999';
        } else if (iface.operstate === 'UP') {
            label = _('ATTIVA');
            color = '#35b85a';
        } else {
            label = _('NON ATTIVA') + (iface.operstate ? ' (' + iface.operstate + ')' : '');
            color = '#b52c1d';
        }
        var rows = '';
        if (iface.present) {
            var v4 = (iface.ipv4 && iface.ipv4.length) ? iface.ipv4.join(', ') : '—';
            rows += '<div><b>IPv4:</b> ' + escHtml(v4) + '</div>';
            if (iface.ipv6 && iface.ipv6.length) {
                rows += '<div><b>IPv6:</b> ' + escHtml(iface.ipv6.join(', ')) + '</div>';
            }
            if (iface.mac) { rows += '<div><b>MAC:</b> ' + escHtml(iface.mac) + '</div>'; }
            if (iface.mtu) { rows += '<div><b>MTU:</b> ' + escHtml(iface.mtu) + '</div>'; }
        }
        var titleSuffix = iface.isDefault
            ? ' <small class="text-muted" style="color:#666;">(' + _('default route') + ')</small>'
            : '';
        blocks.push(
            '<div style="margin-bottom:14px;">' +
                '<h4 style="margin:4px 0; color:' + color + '; font-weight:bold;">' +
                    escHtml(iface.name) + ': ' + label + titleSuffix +
                '</h4>' +
                rows +
            '</div>'
        );
    });
    return blocks.join('');
}

/* ---------------- NODE ---------------- */

function loadNodeConfig() {
    $('#node-iface').val('...');
    $.get('/lib/network/V2/network/node', function (json) {
        var resp = (typeof json === 'string') ? JSON.parse(json) : json;
        var d = (resp && resp.data) ? resp.data : {};
        $('#node-iface').val(d.iface || '');
        $('#node-mode').val((d.mode === 'static') ? 'static' : 'dhcp').trigger('change');
        $('#node-address').val(d.address || '');
        $('#node-netmask').val(d.netmask || '');
        $('#node-gateway').val(d.gateway || '');
        $('#node-dns').val(d.dns || '');
    }).fail(function () {
        alert(_('Impossibile leggere la configurazione del nodo.'));
    });
}

function collectNodeForm() {
    return {
        iface:   $.trim($('#node-iface').val()),
        mode:    $('#node-mode').val(),
        address: $.trim($('#node-address').val()),
        netmask: $.trim($('#node-netmask').val()),
        gateway: $.trim($('#node-gateway').val()),
        dns:     $.trim($('#node-dns').val())
    };
}

function previewNode() {
    var payload = collectNodeForm();
    postWithCsrf('/lib/network/V2/network/node/preview', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                alert((resp && resp.message) || _('Errore di anteprima.'));
                return;
            }
            var d = resp.data;
            var body;
            if (d.canSeeCommands) {
                body = '<p>' + _('Differenza rispetto a /etc/network/interfaces:') + '</p>' +
                       diffNodeContents(d.oldContent, d.newContent);
            } else {
                body = '<div class="alert alert-info">' +
                       _('Verrà aggiornata la configurazione di rete del nodo. Solo gli utenti superuser possono vedere il dettaglio dei comandi applicati.') +
                       '</div>';
            }
            body += '<p style="margin-top:10px; color:#b52c1d;"><strong>' +
                    _("Applicando il networking verrà riavviato. Se ti sei collegato via questa interfaccia, potresti perdere la connessione.") +
                    '</strong></p>';
            $('#preview-modal-title').text(_('Anteprima: configurazione nodo'));
            $('#preview-modal-body').html(body);
            pendingAction = { kind: 'node', payload: payload };
            $('#preview-modal').modal('show');
        })
        .fail(function () {
            alert(_('Errore di rete in fase di anteprima.'));
        });
}

function applyNode(payload) {
    setApplyRunning(_('Riavvio networking in corso, può richiedere alcuni secondi...'));
    postWithCsrf('/lib/network/V2/network/node/apply', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                applyShowError((resp && resp.message) || _('Apply fallito.'));
                return;
            }
            var d = resp.data || {};
            var html = d.applied
                ? '<div class="alert alert-success"><strong>' + _('Configurazione applicata.') + '</strong> ' + _('Tutti i comandi sono andati a buon fine.') + '</div>'
                : '<div class="alert alert-warning"><strong>' + _('Esito incerto.') + '</strong> ' + _('Uno o più comandi potrebbero essere falliti — verifica la configurazione manualmente.') + '</div>';
            if (d.canSeeCommands) {
                if (d.backupPath) {
                    html += '<p>' + _('Backup salvato in') + ' <code>' + escHtml(d.backupPath) + '</code></p>';
                }
                if (d.output) {
                    html += '<p>' + _('Output:') + '</p>' +
                            '<pre style="max-height:200px; overflow:auto;">' + escHtml(d.output) + '</pre>';
                }
            }
            $('#preview-modal-body').html(html);
            applyShowDone();
            loadNodeConfig();
        })
        .fail(function () {
            applyShowError(_('Errore di rete in fase di apply.'));
        });
}

/* ---------------- CAMERA ---------------- */

function loadCameraList() {
    $('#cam-name').prop('disabled', true).html('<option value="">' + _('Caricamento...') + '</option>');
    $.get('/lib/network/V2/network/camera/list', function (json) {
        var resp = (typeof json === 'string') ? JSON.parse(json) : json;
        var cams = (resp && resp.data && resp.data.cameras) ? resp.data.cameras : [];
        var html = '<option value="">' + _('-- seleziona --') + '</option>';
        cams.forEach(function (c) {
            html += '<option value="' + escHtml(c.name) + '">' + escHtml(c.name) + ' (' + escHtml(c.ip) + ')</option>';
        });
        if (cams.length === 0) {
            html = '<option value="">' + _('Nessuna camera trovata') + '</option>';
        }
        $('#cam-name').html(html).prop('disabled', false);
    }).fail(function () {
        $('#cam-name').html('<option value="">' + _('Errore di scoperta') + '</option>');
    });
}

function loadCameraConfig(name) {
    $('#cam-current').hide();
    $('#cam-preview').prop('disabled', true);
    if (!name) {
        return;
    }
    $.get('/lib/network/V2/network/camera/info', { name: name }, function (json) {
        var resp = (typeof json === 'string') ? JSON.parse(json) : json;
        var d = (resp && resp.data) ? resp.data : {};
        var info = _('Modalità corrente:') + ' <b>' + escHtml((d.mode || 'unknown').toUpperCase()) + '</b>' +
                   '. ' + _('IP:') + ' ' + escHtml(d.currentIp || '—') +
                   ', ' + _('Mask:') + ' ' + escHtml(d.currentMask || '—') +
                   ', ' + _('Gateway:') + ' ' + escHtml(d.currentGateway || '—');
        $('#cam-current-info').html(info);
        $('#cam-current').show();
        $('#cam-mode').val(d.mode === 'static' ? 'static' : 'dhcp').trigger('change');
        $('#cam-ip').val(d.persistentIp || '');
        $('#cam-mask').val(d.persistentMask || '');
        $('#cam-gateway').val(d.persistentGateway || '');
        $('#cam-preview').prop('disabled', false);
    }).fail(function () {
        $('#cam-current-info').text(_('Impossibile leggere la configurazione corrente.'));
        $('#cam-current').show();
        $('#cam-preview').prop('disabled', false);
    });
}

function collectCameraForm() {
    return {
        name:    $('#cam-name').val(),
        mode:    $('#cam-mode').val(),
        ip:      $.trim($('#cam-ip').val()),
        mask:    $.trim($('#cam-mask').val()),
        gateway: $.trim($('#cam-gateway').val())
    };
}

function previewCamera() {
    var payload = collectCameraForm();
    if (!payload.name) {
        alert(_('Seleziona prima una camera.'));
        return;
    }
    postWithCsrf('/lib/network/V2/network/camera/preview', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                alert((resp && resp.message) || _('Errore di anteprima.'));
                return;
            }
            var d = resp.data || {};
            var body;
            if (d.canSeeCommands) {
                var cmds = d.commands || [];
                body = '<p>' + _('Comandi che verranno eseguiti via SSH (in ordine):') + '</p>' +
                       '<pre style="max-height:300px; overflow:auto; white-space:pre-wrap;">' +
                       cmds.map(escHtml).join('\n') +
                       '</pre>';
            } else {
                var modeLabel = (d.mode === 'static') ? _('Statica') : 'DHCP';
                body = '<div class="alert alert-info">' +
                       _('Verrà aggiornata la configurazione IP della camera in modalità') + ' <b>' + escHtml(modeLabel) + '</b>. ' +
                       _('Solo gli utenti superuser possono vedere il dettaglio dei comandi applicati.') +
                       '</div>';
            }
            body += '<p style="color:#b52c1d;"><strong>' +
                    _("L'ultimo comando (DeviceReset) riavvia la camera per applicare la nuova configurazione: la camera resterà offline 10-30 secondi.") +
                    '</strong></p>';
            $('#preview-modal-title').text(_('Anteprima: configurazione camera'));
            $('#preview-modal-body').html(body);
            pendingAction = { kind: 'camera', payload: payload };
            $('#preview-modal').modal('show');
        })
        .fail(function () {
            alert(_('Errore di rete in fase di anteprima.'));
        });
}

function applyCamera(payload) {
    setApplyRunning(_('Esecuzione comandi e reset camera in corso (può richiedere 10-30 secondi)...'));
    postWithCsrf('/lib/network/V2/network/camera/apply', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                applyShowError((resp && resp.message) || _('Apply fallito.'));
                return;
            }
            var d = resp.data || {};
            var html = d.applied
                ? '<div class="alert alert-success"><strong>' + _('Comandi eseguiti.') + '</strong> ' + _('La camera è in fase di reset.') + '</div>'
                : '<div class="alert alert-warning"><strong>' + _('Esito incerto.') + '</strong> ' + _('La scrittura della configurazione potrebbe non essere riuscita.') + '</div>';
            if (d.canSeeCommands && d.output) {
                html += '<p>' + _('Output:') + '</p>' +
                        '<pre style="max-height:300px; overflow:auto;">' + escHtml(d.output) + '</pre>';
            }
            $('#preview-modal-body').html(html);
            applyShowDone();
        })
        .fail(function () {
            applyShowError(_('Errore di rete in fase di apply.'));
        });
}

// ---- Apply lifecycle helpers ----
// While the request is in flight: disable the confirm button, change its label
// to a spinner + waiting text, and prepend a running banner above the existing
// preview content (the diff/commands stay visible so the user can read them).
function setApplyRunning(message) {
    var $btn = $('#preview-confirm');
    if (!$btn.data('orig-html')) {
        $btn.data('orig-html', $btn.html());
    }
    $btn.prop('disabled', true)
        .html('<i class="fa fa-spinner fa-spin"></i> ' + _('Esecuzione...'));
    $('#preview-modal-body').prepend(
        '<div id="apply-running" class="alert alert-info" style="margin-bottom:10px;">' +
            '<i class="fa fa-spinner fa-spin"></i> ' + escHtml(message) +
        '</div>'
    );
}

function applyShowDone() {
    $('#apply-running').remove();
    var $btn = $('#preview-confirm');
    $btn.hide().prop('disabled', false);
    if ($btn.data('orig-html')) {
        $btn.html($btn.data('orig-html'));
    }
    // Swap "Annulla" → "OK" green so the user can confirm reading the result.
    var $cancel = $('#preview-cancel');
    if (!$cancel.data('orig-html')) {
        $cancel.data('orig-html', $cancel.html());
        $cancel.data('orig-class', $cancel.attr('class') || '');
    }
    $cancel.attr('class', 'btn btn-success').html(_('OK'));
    appliedSuccessfully = true;
}

function applyShowError(msg) {
    $('#apply-running').remove();
    $('#preview-modal-body').prepend(
        '<div class="alert alert-danger" style="margin-bottom:10px;">' + escHtml(msg) + '</div>'
    );
    var $btn = $('#preview-confirm');
    $btn.prop('disabled', false);
    if ($btn.data('orig-html')) {
        $btn.html($btn.data('orig-html'));
    }
}

/* ---------------- WIRING ---------------- */

$(document).ready(function () {

    $('#node-mode').on('change', function () {
        $('#node-static-fields').toggle($(this).val() === 'static');
    });
    $('#cam-mode').on('change', function () {
        $('#cam-static-fields').toggle($(this).val() === 'static');
    });

    $('#node-preview').on('click', previewNode);
    $('#node-reload').on('click', loadNodeConfig);

    $('#cam-discover').on('click', loadCameraList);
    $('#cam-name').on('change', function () { loadCameraConfig($(this).val()); });
    $('#cam-preview').on('click', previewCamera);

    $('#preview-modal').on('hidden.bs.modal', function () {
        if (appliedSuccessfully) {
            // Reload the page to reflect the new applied state.
            window.location.reload();
            return;
        }
        var $btn = $('#preview-confirm');
        $btn.show().prop('disabled', false);
        if ($btn.data('orig-html')) {
            $btn.html($btn.data('orig-html'));
        }
        var $cancel = $('#preview-cancel');
        if ($cancel.data('orig-html')) {
            $cancel.html($cancel.data('orig-html'));
            $cancel.attr('class', $cancel.data('orig-class') || 'btn btn-default');
        }
        $('#apply-running').remove();
        pendingAction = null;
    });
    $('#preview-confirm').on('click', function () {
        if (!pendingAction) {
            return;
        }
        // applyNode/applyCamera handle the button + status lifecycle themselves.
        var act = pendingAction;
        if (act.kind === 'node') {
            applyNode(act.payload);
        } else if (act.kind === 'camera') {
            applyCamera(act.payload);
        }
    });

    loadNodeConfig();
    showWiredNetworkStatus();
});
