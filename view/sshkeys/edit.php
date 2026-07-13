<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h3><?= _('Chiavi SSH') ?></h3>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <!-- Info box -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='alert alert-info' style='border-left: 4px solid #31708f; padding: 15px;'>
                    <h4><i class='fa fa-info-circle'></i> <?= _('Come accedere ai dati del nodo') ?></h4>
                    <p><?= _('Il nodo espone un servizio <strong>SFTP</strong> sulla porta <strong>2026</strong> per il <strong>download</strong> dei dati acquisiti.') ?></p>
                    <p><strong><?= _('Importante:') ?></strong> <?= _('non si tratta di un accesso SSH con console interattiva — è possibile solo scaricare file dal nodo (sola lettura).') ?></p>
                    <hr style='border-color:#bce8f1'>
                    <p style='margin-bottom:4px'><strong><?= _('Comando di connessione:') ?></strong></p>
                    <pre style='background:#d9edf7; border:none; padding:8px; border-radius:4px; margin-bottom:8px'>sftp -i ~/.ssh/id_rsa -P 2026 prisma@<?= strtoupper(CoreLogic::GetStationCode()) ?>.local</pre>
                    <p style='margin-bottom:4px'><strong><?= _('Download file (esempio):') ?></strong></p>
                    <pre style='background:#d9edf7; border:none; padding:8px; border-radius:4px; margin-bottom:0'>get -r /prismadata/<?= CoreLogic::GetStationCode() ?>_<?= date('Ymd') ?> .</pre>
                    <p class='text-muted' style='margin-top:8px; margin-bottom:0'><small><?= _('Aggiungere la propria chiave pubblica SSH (file .pub) nel pannello sottostante per ottenere l\'accesso.') ?></small></p>
                </div>
            </div>

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
        $.ajax({
            url: '/service/sshkeys/list',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var keys = Array.isArray(data) ? data : [];
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
            },
            error: function (xhr) {
                $('#keysTbody').html('<tr><td colspan="4" class="text-danger">Errore caricamento: ' + xhr.status + '</td></tr>');
            }
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
