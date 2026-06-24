<?php // /* @var $CameraCalibration */                ?> 
<!-- Modal -->
<div class="modal fade" id="capture-preview-modal" tabindex="-1" role="dialog" aria-labelledby="capture-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capture-preview-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="capture-preview-modal-body">
            </div>
        </div>
    </div>
</div>

<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Controllo camera') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>

        <!-- Info box dispositivo: dati statici letti senza interrogare la camera. -->
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title'>
                        <h2><?= _('Informazioni dispositivo') ?></h2>
                        <div class='clearfix'></div>
                    </div>
                    <div class='x_content'>
                        <div id='camera-hwinfo-loading' style='color:#888;'><?= _('Caricamento informazioni...') ?></div>
                        <div id='camera-hwinfo' style='display:none;'>
                            <div class='col-md-6 col-sm-12 col-xs-12' style='padding-left:0;'>
                                <h4 style='margin-top:0;'><?= _('Hardware (dai log freeture)') ?></h4>
                                <table class='table table-condensed' style='margin-bottom:0;'>
                                    <tr><th style='width:40%;'><?= _('Vendor') ?></th><td id='hw-vendor'>&mdash;</td></tr>
                                    <tr><th><?= _('Modello') ?></th><td id='hw-model'>&mdash;</td></tr>
                                    <tr><th><?= _('Firmware') ?></th><td id='hw-firmware'>&mdash;</td></tr>
                                    <tr><th><?= _('Serial number') ?></th><td id='hw-serial'>&mdash;</td></tr>
                                    <tr><th><?= _('IP camera') ?></th><td id='hw-ip'>&mdash;</td></tr>
                                    <tr><th><?= _('Aravis') ?></th><td id='hw-aravis'>&mdash;</td></tr>
                                    <tr><th><?= _('Ultimo log') ?></th><td id='hw-lastseen'>&mdash;</td></tr>
                                </table>
                            </div>
                            <div class='col-md-6 col-sm-12 col-xs-12'>
                                <h4 style='margin-top:0;'><?= _('Configurazione (da configuration.cfg)') ?></h4>
                                <table class='table table-condensed' style='margin-bottom:0;'>
                                    <tr><th style='width:40%;'><?= _('Camera') ?></th><td id='cfg-camera'>&mdash;</td></tr>
                                    <tr><th><?= _('Camera ID') ?></th><td id='cfg-cameraId'>&mdash;</td></tr>
                                    <tr><th><?= _('Instrument') ?></th><td id='cfg-instrument'>&mdash;</td></tr>
                                    <tr><th><?= _('Telescope') ?></th><td id='cfg-telescope'>&mdash;</td></tr>
                                    <tr><th><?= _('Formato') ?></th><td id='cfg-format'>&mdash;</td></tr>
                                    <tr><th><?= _('Risoluzione') ?></th><td id='cfg-resolution'>&mdash;</td></tr>
                                    <tr><th><?= _('FPS') ?></th><td id='cfg-fps'>&mdash;</td></tr>
                                </table>
                            </div>
                            <div class='clearfix'></div>
                        </div>

                        <hr style='margin: 14px 0;'>

                        <!-- Lettura "deep" via arv-tool: pausa freeture per pochi secondi. -->
                        <div>
                            <button type='button' id='btn-camera-hwinfo-deep' class='btn btn-warning'>
                                <i class='fa fa-bolt'></i>
                                <?= _('Leggi parametri completi dalla camera') ?>
                            </button>
                            <small class='text-muted' style='margin-left:8px;'>
                                <?= _('Richiede pausa temporanea di freeture (~5s)') ?>
                            </small>
                            <span id='camera-hwinfo-deep-progress' style='display:none; margin-left:12px;'>
                                <i class='fa fa-spinner fa-spin'></i>
                                <?= _('Lettura in corso, freeture in pausa...') ?>
                            </span>
                        </div>
                        <div id='camera-hwinfo-deep' style='display:none; margin-top:14px;'></div>

                        <hr style='margin: 14px 0;'>

                        <!-- Diagnostica rete nodo<->camera: ethtool, /sys, ping, ARP. Non disturba freeture. -->
                        <div>
                            <button type='button' id='btn-camera-netdiag' class='btn btn-info'>
                                <i class='fa fa-stethoscope'></i>
                                <?= _('Diagnostica rete (nodo)') ?>
                            </button>
                            <small class='text-muted' style='margin-left:8px;'>
                                <?= _('Verifica link, errori NIC, ping verso la camera. Non interrompe freeture.') ?>
                            </small>
                            <span id='camera-netdiag-progress' style='display:none; margin-left:12px;'>
                                <i class='fa fa-spinner fa-spin'></i>
                                <?= _('Diagnostica in corso...') ?>
                            </span>
                        </div>
                        <div id='camera-netdiag' style='display:none; margin-top:14px;'></div>
                    </div>
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-8 col-sm-8 col-xs-10 no-padding'>
                                <h2><?= _('Funzioni') ?></h2>
                            </div>
                            <div class='col-md-4 col-sm-4 col-xs-2 no-padding'>
                                <input hidden id = "camera-ip" value = ""/>
                                <input hidden id = "camera-name_d" value = ""/>
                                <div hidden id = "camera-list-container-multiple" class = "pull-right">
                                    <select id = "camera-select" class = "pull-right">

                                    </select>
                                </div>
                                <div hidden id = "camera-list-container-single" class = "pull-right">
                                    <h5 id = "camera-name"></h5>
                                </div>
                            </div>
                            <div class='col-md-8 col-sm-8 col-xs-12 no-padding' id='enable-capture-preview-box'>
                                <div class='pull-right'>
                                    <div class='col-md-8'>
                                        
                                    </div>
                                    <div class='col-md-4'>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='x_content'>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success camera-control-function' id = "camera-control-list">
                            <?= _('List') ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-reset">
                            <?= _('List Reset') ?>
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-freset">
                            <?= _('FactoryReset') ?> 
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-features">
                            <?= _('Features') ?> 
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-values">
                            <?= _('Values') ?> 
                            </div>
                        </div>
                        
                    </div>
                    <br>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h3><?= _('Comando custom') ?></h3><small><?= _('[inserisci il comando senza scrivere arv-tool-0.8]') ?></small>
                            <textarea rows="5" style="min-width:500px; width:100%;" id="camera-control-in"></textarea>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-send">
                            <?= _('Invia comando') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-8 no-padding-l'>
                                <h2><?= _('Output') ?></h2>
                            </div>
                            
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <textarea rows="30" style="min-width:500px; width:100%;" id="camera-control-out"></textarea>
                        </div>  
                        <div class='col-md-12 col-sm-12 col-xs-12' id='last-capture-preview'>
                        </div>
                    </div>
                </div>
            </div>

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-8 no-padding-l'>
                                <h4><?= _('Esegui calibrazione con tutti i possibili valori di Guadagno') ?>[<span style = 'color: black' id = 'minGain'></span>,<span style = 'color: black' id = 'maxGain'></span>]</h4>
                                
                            </div>
                        </div>
                    </div>
                    <div id = 'calibration_form'>
                        <div class = 'x_content'>
                            
                            <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                <div class='col-md-2 no-padding-l'>
                                    <small class = "text-muted"><?= _('Esposizione per la calibrazione') ?></small>
                                    <input type = 'number' id = "calibration_exp" class = "form-control col-md-7 col-xs-12" value = "1000">
                                </div>
                                <div class='col-md-2 no-padding-l'>
                                    <small class = "text-muted"><?= _('MinGain') ?></small>
                                    <input type = 'number' id = "calibration_minGain" class = "form-control col-md-7 col-xs-12" value = "">
                                </div>
                                <div class='col-md-2 no-padding-l'>
                                    <small class = "text-muted"><?= _('MaxGain') ?></small>
                                    <input type = 'number' id = "calibration_maxGain" class = "form-control col-md-7 col-xs-12" value = "">
                                </div>
                                <div class='col-md-2 no-padding-l'>
                                    <small class = "text-muted"><?= _('GainStep') ?></small>
                                    <input type = 'number' id = "calibration_step" class = "form-control col-md-7 col-xs-12" value = "1">
                                </div>
                                <div class='col-md-2 no-padding-l'>
                                    <small class = "text-muted"><?= _('Freeture') ?></small>
                                    <select id = "calibration_freeture" class = "form-control col-md-7 col-xs-12">
                                        <option selected value = 'n3srl/freeture13'><?= _('Freeture13') ?></option>
                                        <option value = 'n3srl/freeture'><?= _('Freeture') ?></option>
                                    </select>
                                </div>
                                
                            </div>
                        </div>

                        <div class = 'x_content'>
                            
                                <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                    
                                    <div class='col-md-2 no-padding-l'>
                                        <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera_calibration">
                                        <?= _('Calibrazione') ?>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                        </div>
                    </div>



                    <div id = 'calibration_notice' hidden>
                        <div class = 'x_content'>
                            
                            <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                <div class='col-md-4 alert alert-success'>
                                <?= _('Una calibrazione e gia in corso') ?>
                                </div>
                                
                            </div>
                        </div>

                        
                    </div>



                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <div class = "calibration-download-table">
                                <table class = "table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th><?= _('Calibrazione') ?></th>
                                            <th><?= _('Data calibrazione') ?></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id = "calibration_table">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/camera.js<?= _VERSION_ ?>'></script>



