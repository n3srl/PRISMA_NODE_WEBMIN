<div class='right_col' role='main'>
    <div>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Configurazione IP') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>

        <div class='row'>
            <!-- NODE -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Indirizzo IP del nodo') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='alert alert-warning' style='margin-bottom:14px;'>
                            <strong><?= _('Attenzione') ?>:</strong>
                            <?= _('una configurazione errata può rendere il nodo irraggiungibile via rete. Verrà fatto un backup di /etc/network/interfaces prima di applicare; usa il bottone Anteprima per controllare il diff.') ?>
                        </div>
                        <form id='node-form' onsubmit='return false;'>
                            <div class='row'>
                                <div class='col-md-4 col-sm-6 col-xs-12'>
                                    <label for='node-iface'><?= _('Interfaccia') ?></label>
                                    <input type='text' id='node-iface' class='form-control' readonly>
                                </div>
                                <div class='col-md-4 col-sm-6 col-xs-12'>
                                    <label for='node-mode'><?= _('Modalità') ?></label>
                                    <select id='node-mode' class='form-control'>
                                        <option value='dhcp'>DHCP</option>
                                        <option value='static'><?= _('Statica') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div id='node-static-fields' style='display:none;'>
                                <div class='row' style='margin-top:10px;'>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='node-address'><?= _('Indirizzo (CIDR es. 192.168.1.10/24)') ?></label>
                                        <input type='text' id='node-address' class='form-control' placeholder='192.168.1.10/24'>
                                    </div>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='node-netmask'><?= _('Netmask (se non in CIDR)') ?></label>
                                        <input type='text' id='node-netmask' class='form-control' placeholder='255.255.255.0'>
                                    </div>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='node-gateway'><?= _('Gateway') ?></label>
                                        <input type='text' id='node-gateway' class='form-control' placeholder='192.168.1.1'>
                                    </div>
                                </div>
                                <div class='row' style='margin-top:10px;'>
                                    <div class='col-md-8 col-sm-12 col-xs-12'>
                                        <label for='node-dns'><?= _('DNS (separati da spazio)') ?></label>
                                        <input type='text' id='node-dns' class='form-control' placeholder='1.1.1.1 8.8.8.8'>
                                    </div>
                                </div>
                            </div>
                            <div class='row' style='margin-top:14px;'>
                                <div class='col-md-12'>
                                    <button type='button' id='node-preview' class='btn btn-info'><i class='fa fa-eye'></i> <?= _('Anteprima') ?></button>
                                    <button type='button' id='node-reload' class='btn btn-default'><i class='fa fa-refresh'></i> <?= _('Ricarica') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CAMERA -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Indirizzo IP della camera') ?></h2>
                            </div>
                            <div class='col-md-6 no-padding-r'>
                                <button type='button' id='cam-discover' class='btn btn-default pull-right'><i class='fa fa-search'></i> <?= _('Scopri') ?></button>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='alert alert-info' style='margin-bottom:14px;'>
                            <?= _("Le modifiche al PersistentIP vengono scritte tramite arv-tool-0.8 sull'host. La camera applica il nuovo indirizzo al riavvio.") ?>
                        </div>
                        <form id='camera-form' onsubmit='return false;'>
                            <div class='row'>
                                <div class='col-md-6 col-sm-12'>
                                    <label for='cam-name'><?= _('Camera') ?></label>
                                    <select id='cam-name' class='form-control'>
                                        <option value=''><?= _('Premi Scopri per elencare le camere') ?></option>
                                    </select>
                                </div>
                                <div class='col-md-3 col-sm-6'>
                                    <label for='cam-mode'><?= _('Modalità') ?></label>
                                    <select id='cam-mode' class='form-control'>
                                        <option value='dhcp'>DHCP</option>
                                        <option value='static'><?= _('Statica') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div id='cam-current' class='row' style='margin-top:10px; display:none;'>
                                <div class='col-md-12'>
                                    <small class='text-muted' id='cam-current-info'></small>
                                </div>
                            </div>
                            <div id='cam-static-fields' style='display:none;'>
                                <div class='row' style='margin-top:10px;'>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='cam-ip'><?= _('IP') ?></label>
                                        <input type='text' id='cam-ip' class='form-control' placeholder='192.168.0.42'>
                                    </div>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='cam-mask'><?= _('Netmask') ?></label>
                                        <input type='text' id='cam-mask' class='form-control' placeholder='255.255.255.0'>
                                    </div>
                                    <div class='col-md-4 col-sm-6 col-xs-12'>
                                        <label for='cam-gateway'><?= _('Gateway (opzionale)') ?></label>
                                        <input type='text' id='cam-gateway' class='form-control' placeholder='192.168.0.1'>
                                    </div>
                                </div>
                            </div>
                            <div class='row' style='margin-top:14px;'>
                                <div class='col-md-12'>
                                    <button type='button' id='cam-preview' class='btn btn-info' disabled><i class='fa fa-eye'></i> <?= _('Anteprima') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class='modal fade' id='preview-modal' tabindex='-1' role='dialog'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title' id='preview-modal-title'><?= _('Anteprima modifiche') ?></h4>
            </div>
            <div class='modal-body' id='preview-modal-body'>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'><?= _('Annulla') ?></button>
                <button type='button' id='preview-confirm' class='btn btn-danger'><?= _('Conferma e applica') ?></button>
            </div>
        </div>
    </div>
</div>

<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/network.js<?= _VERSION_ ?>'></script>
