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



