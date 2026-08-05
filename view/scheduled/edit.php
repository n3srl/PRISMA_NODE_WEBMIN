<?php // /* @var $Scheduled Scheduled */                ?>
<!-- Modal -->
<div class="modal fade" id="scheduled-preview-modal" tabindex="-1" role="dialog" aria-labelledby="scheduled-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduled-preview-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="scheduled-preview-modal-body">
            </div>
        </div>
    </div>
</div>

<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Acquisizioni Schedulate') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-4 col-sm-4 col-xs-12 no-padding'>
                                <h2><?= _('Elenco') ?></h2>
                            </div>
                            <div class='col-md-8 col-sm-8 col-xs-12 no-padding' id='enable-scheduled-preview-box'>
                                <div class='pull-right'>
                                    <div class='col-md-8'>
                                        <label class='pull-right text-nowrap'><?= _('Abilita anteprima') ?></label>
                                    </div>
                                    <div class='col-md-4'>
                                        <label class='switch pull-left'>
                                            <input type='checkbox' id='enable-scheduled-preview'>
                                            <span class='slider round'></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-3 col-sm-3 col-xs-12'>
                            <table id='ScheduledDayList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('#')) ?></th>
                                        <th><?php echo (_('Cartella')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class='col-md-9 col-sm-9 col-xs-12'>
                            <table id='ScheduledList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Nome Acquisizione')) ?></th>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('Ora')) ?></th>
                                        <th><?php echo (_('Anteprima')) ?></th>
                                        <th><?php echo (_('Scarica')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Ultima Acquisizione Schedulata') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5 id="last-scheduled-description"></h5>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12' id='last-scheduled-preview'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/scheduled.js<?= _VERSION_ ?>'></script>
