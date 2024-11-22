<?php /* @var $Docker Docker */ ?>
<div class='right_col' role='main'>
        <div class=''>
                <div class='page-title'>
                        <div class='title_left'>
                                <h2><?= _('Stato Containers') ?></h2>
                        </div>
                </div>
                <div class='clearfix'></div>
                <div class='row'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div id='list' class='x_panel'>
                                        <div class='x_title no-padding-lr'>
                                                <div class='clearfix'>
                                                        <div class='col-md-6 no-padding-l'>
                                                                <h2><?= _('Elenco') ?></h2>
                                                        </div>

                                                </div>
                                        </div>

                                        <div class='x_content'>
                                                <table id='DockerList' class='table table-striped table-bordered' style='width: 100%; '>
                                                        <thead>
                                                                <tr>
                                                                        <th><?php echo (_('Nome')) ?></th>
                                                                        <th><?php echo (_('Immagine')) ?></th>
                                                                        <th><?php echo (_('Comando')) ?></th>
                                                                        <th><?php echo (_('Stato')) ?></th>
                                                                        <th><?php echo (_('Creato')) ?></th>
                                                                        <th><?php echo (_('Avvia')) ?></th>
                                                                        <th><?php echo (_('Ferma')) ?></th>
                                                                        <th><?php echo (_('Riavvia')) ?></th>
                                                                </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                </table>
                                        </div>
                                </div>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Freeture logs') ?></h2>
                            </div>
                            <div class='col-md-6 no-padding-l'>
                                <div id = "freeture-log-download" class = "btn btn-success btn-blue-success pull-right"><?= _('Download') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                            <textarea rows="30" style="min-width:500px; width:100%;" id="freeture-log"></textarea>
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
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/docker.js<?= _VERSION_ ?>'></script>