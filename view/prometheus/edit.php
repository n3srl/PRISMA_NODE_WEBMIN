<?php /* @var $FreetureFinal FreetureFinal */ ?> 
<div class='right_col' role='main' >
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Stato node_exporter') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>
          
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id="status-prometheus-panel" class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Metriche') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <h4 id="status-prometheus"></h4>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <textarea id="status-prometheus-description"></textarea>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/prometheus.js<?= _VERSION_ ?>'></script>



