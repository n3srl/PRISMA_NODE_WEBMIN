<?php // /* @var $Capture Capture */                ?> 
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
                <h2><?= _('Camera control') ?></h2>
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
                                <div hidden id = "camera-list-container-multiple" class = "pull-right">
                                    <select id = "camera-select" class = "pull-right">

                                    </select>
                                </div>
                                <div hidden id = "camera-list-container-single" class = "pull-right">
                                    <h5 id = "camera-name"></h5>
                                    <input hidden id = "camera-ip" value = ""/>
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
                                List
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-reset">
                                Reset
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-freset">
                                FactoryReset
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-features">
                                Features
                            </div>
                        </div>
                        <div class='col-md-2 col-sm-2 col-xs-12'>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-values">
                                Values
                            </div>
                        </div>
                        
                    </div>
                    <br>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h3>Comando custom</h3><small>[inserisci il comando senza scrivere arv-tool-0.8]</small>
                            <textarea rows="5" style="min-width:500px; width:100%;" id="camera-control-in"></textarea>
                            <div class = 'btn btn-success btn-blue-success camera-control-function' id = "camera-control-send">
                                Invia comando
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
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
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Output') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <textarea rows="30" style="min-width:500px; width:100%;" id=""></textarea>
                        </div>  
                        <div class='col-md-12 col-sm-12 col-xs-12' id='last-capture-preview'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/camera.js<?= _VERSION_ ?>'></script>



