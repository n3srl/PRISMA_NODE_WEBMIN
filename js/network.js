/**
 * Network configuration page.
 *
 * Loads current /etc/network/interfaces state and arv-tool-0.8 camera list,
 * lets the admin switch between DHCP and static for either, previews the
 * resulting changes in a modal, then applies via the CSRF-protected endpoints.
 */

var pendingAction = null; // {kind: 'node'|'camera', payload: {...}}

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
            var body = '<p>' + _('Differenza rispetto a /etc/network/interfaces:') + '</p>' +
                       diffNodeContents(d.oldContent, d.newContent) +
                       '<p style="margin-top:10px; color:#b52c1d;"><strong>' +
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
    postWithCsrf('/lib/network/V2/network/node/apply', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                alert((resp && resp.message) || _('Apply fallito.'));
                return;
            }
            var d = resp.data;
            $('#preview-modal-body').html(
                '<div class="alert alert-success">' + _('Configurazione applicata.') + '</div>' +
                '<p>' + _('Backup salvato in') + ' <code>' + escHtml(d.backupPath) + '</code></p>' +
                '<p>' + _('Output:') + '</p>' +
                '<pre style="max-height:200px; overflow:auto;">' + escHtml(d.output || '') + '</pre>'
            );
            $('#preview-confirm').hide();
            loadNodeConfig();
        })
        .fail(function () {
            alert(_('Errore di rete in fase di apply.'));
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
            var cmds = (resp.data && resp.data.commands) ? resp.data.commands : [];
            var body = '<p>' + _('Comandi che verranno eseguiti via SSH:') + '</p>' +
                       '<pre style="max-height:300px; overflow:auto; white-space:pre-wrap;">' +
                       cmds.map(escHtml).join('\n') +
                       '</pre>' +
                       '<p style="color:#b52c1d;"><strong>' +
                       _("La camera potrebbe richiedere un riavvio per applicare il PersistentIP.") +
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
    postWithCsrf('/lib/network/V2/network/camera/apply', payload)
        .then(function (resp) {
            if (!resp || !resp.result) {
                alert((resp && resp.message) || _('Apply fallito.'));
                return;
            }
            var d = resp.data;
            $('#preview-modal-body').html(
                '<div class="alert alert-success">' + _('Comandi eseguiti.') + '</div>' +
                '<p>' + _('Output:') + '</p>' +
                '<pre style="max-height:300px; overflow:auto;">' + escHtml(d.output || '') + '</pre>'
            );
            $('#preview-confirm').hide();
        })
        .fail(function () {
            alert(_('Errore di rete in fase di apply.'));
        });
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
        $('#preview-confirm').show();
        pendingAction = null;
    });
    $('#preview-confirm').on('click', function () {
        if (!pendingAction) {
            return;
        }
        $('#preview-confirm').prop('disabled', true);
        var act = pendingAction;
        if (act.kind === 'node') {
            applyNode(act.payload);
        } else if (act.kind === 'camera') {
            applyCamera(act.payload);
        }
        $('#preview-confirm').prop('disabled', false);
    });

    loadNodeConfig();
});
