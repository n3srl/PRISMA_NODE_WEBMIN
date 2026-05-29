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
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Analisi log') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='row'>
                            <div class='col-md-3 col-sm-6 col-xs-12'>
                                <label for='log-filter-from'><?= _('Da') ?></label>
                                <input type='text' id='log-filter-from' class='form-control' placeholder='<?= _('vuoto = nessun limite') ?>' autocomplete='off'/>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12'>
                                <label for='log-filter-to'><?= _('A') ?></label>
                                <input type='text' id='log-filter-to' class='form-control' placeholder='<?= _('vuoto = nessun limite') ?>' autocomplete='off'/>
                            </div>
                            <div class='col-md-4 col-sm-8 col-xs-12'>
                                <label for='log-filter-levels'><?= _('Livelli') ?></label>
                                <select id='log-filter-levels' class='form-control' multiple>
                                    <option value='DEBUG'>DEBUG</option>
                                    <option value='INFO'>INFO</option>
                                    <option value='NOTICE'>NOTICE</option>
                                    <option value='WARN' selected>WARN</option>
                                    <option value='ERROR' selected>ERROR</option>
                                    <option value='FATAL' selected>FATAL</option>
                                </select>
                            </div>
                            <div class='col-md-2 col-sm-4 col-xs-12'>
                                <label>&nbsp;</label>
                                <button type='button' id='log-filter-apply' class='btn btn-primary btn-block'><?= _('Filtra') ?></button>
                            </div>
                        </div>
                        <div class='row' style='margin-top:10px;'>
                            <div class='col-md-12'>
                                <small id='log-filter-info' class='text-muted'></small>
                                <pre id='log-filter-result' style='max-height:400px; overflow:auto; white-space:pre-wrap; word-break:break-word; margin-top:6px;'></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
        </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/docker.js<?= _VERSION_ ?>'></script>