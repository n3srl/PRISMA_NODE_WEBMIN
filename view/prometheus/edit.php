<?php /* @var $FreetureFinal FreetureFinal */ ?> 
<div class='right_col' role='main' >
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Configurazione Prometheus') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='edit-buttons-prometheus' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Carica File') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <div class="mb-3">
                                <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                    <h5><?= _('Carica nuovo file di configurazione Prometheus') ?></h5>
                                </div>
                                <div class='clearfix'>
                                    <form id="prometheusCfgFileForm" method='post' class='file-upload' enctype='multipart/form-data'>
                                        <div class='col-md-10 no-padding'>
                                            <input class="form-control" name="configuration" type="file" accept=".yaml" id="form-prometheuscfg">
                                        </div>
                                        <div class='col-md-2 no-padding'>
                                            <input type = 'submit' style= 'margin-right: 10px;' id= 'uploadprometheusbtn' value='CARICA' disabled="true" class='btn btn-success pull-right' >
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id="status-prometheus-panel" class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Stato Prometheus') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <h4 id="status-prometheus"></h4>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <h4 id="status-prometheus-description"></h4>
                        </div>  
                    </div>
                </div>
            </div>

            
            
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/prometheus.js<?= _VERSION_ ?>'></script>



