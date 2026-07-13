<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h3><?= _('Chiavi SSH') ?></h3>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <!-- Aggiunta chiave -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title'>
                        <h2><?= _('Aggiungi chiave pubblica SSH') ?></h2>
                        <div class='clearfix'></div>
                    </div>
                    <div class='x_content'>
                        <form id='addKeyForm' class='form-horizontal file-upload'>
                            <div class='form-group'>
                                <label class='control-label col-md-3 col-sm-3 col-xs-12'><?= _('Chiave pubblica') ?> *</label>
                                <div class='col-md-7 col-sm-7 col-xs-12'>
                                    <textarea id='pubkey' name='pubkey' class='form-control' rows='4'
                                        placeholder='ssh-ed25519 AAAA... utente@host'
                                        required style='font-family:monospace;font-size:12px'></textarea>
                                    <small class='text-muted'><?= _('Incolla il contenuto del file .pub (es. id_ed25519.pub)') ?></small>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-7 col-md-offset-3'>
                                    <button type='submit' id='addKeyBtn' class='btn btn-success'>
                                        <i class='fa fa-plus'></i> <?= _('Aggiungi') ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Lista chiavi autorizzate -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title'>
                        <h2><?= _('Chiavi autorizzate') ?> <small class='text-muted'><?= _('accesso SSH porta 2026') ?></small></h2>
                        <div class='clearfix'></div>
                    </div>
                    <div class='x_content'>
                        <div id='keysTableWrapper'>
                            <table id='keysTable' class='table table-striped table-hover'>
                                <thead>
                                    <tr>
                                        <th><?= _('Tipo') ?></th>
                                        <th><?= _('Commento / Identità') ?></th>
                                        <th><?= _('Fingerprint (inizio blob)') ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id='keysTbody'>
                                    <tr><td colspan='4' class='text-center'><i class='fa fa-spinner fa-spin'></i> <?= _('Caricamento...') ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include './view/template/foot.php'; ?>

<script>
(function () {

    function loadKeys() {
        $.get('/service/sshkeys/list', function (data) {
            var keys = [];
            try {
                var parsed = (typeof data === 'string') ? JSON.parse(data) : data;
                keys = JSON.parse(parsed.content || '[]');
            } catch(e) {}
            var tbody = $('#keysTbody');
            tbody.empty();
            if (!keys.length) {
                tbody.append('<tr><td colspan="4" class="text-muted text-center"><?= _('Nessuna chiave presente') ?></td></tr>');
                return;
            }
            keys.forEach(function (k) {
                var shortBlob = k.blob ? k.blob.substring(0, 20) + '...' : '';
                var row = $('<tr>');
                row.append($('<td>').text(k.type));
                row.append($('<td>').text(k.comment || '—'));
                row.append($('<td>').html('<code>' + escHtml(shortBlob) + '</code>'));
                var del = $('<button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> <?= _('Rimuovi') ?></button>');
                del.on('click', function () { deleteKey(k.blob, k.comment); });
                row.append($('<td>').append(del));
                tbody.append(row);
            });
        });
    }

    function deleteKey(blob, comment) {
        if (!confirm('<?= _('Rimuovere la chiave') ?> "' + (comment || blob.substring(0,20)) + '"?')) return;
        $.post('/service/sshkeys/delete', { blob: blob }, function (data) {
            if (data && data.success) {
                alertSuccess('<?= _('OK') ?>', data.content);
                loadKeys();
            } else {
                alertError('<?= _('Errore') ?>', data ? data.content : '<?= _('Errore') ?>');
            }
        }, 'json').fail(function (xhr) {
            alertError('<?= _('Errore') ?>', 'HTTP ' + xhr.status + ': ' + xhr.responseText.substring(0, 200));
        });
    }

    $('#addKeyForm').on('submit', function (e) {
        e.preventDefault();
        var key = $.trim($('#pubkey').val());
        if (!key) return;
        $('#addKeyBtn').prop('disabled', true);
        $.post('/service/sshkeys/add', { pubkey: key }, function (data) {
            if (data && data.success) {
                alertSuccess('<?= _('OK') ?>', data.content);
                $('#pubkey').val('');
                loadKeys();
            } else {
                alertError('<?= _('Errore') ?>', data ? data.content : '<?= _('Errore') ?>');
            }
        }, 'json').fail(function (xhr) {
            alertError('<?= _('Errore') ?>', 'HTTP ' + xhr.status + ': ' + xhr.responseText.substring(0, 200));
        }).always(function () {
            $('#addKeyBtn').prop('disabled', false);
        });
    });

    function escHtml(s) {
        return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    loadKeys();

}());
</script>
