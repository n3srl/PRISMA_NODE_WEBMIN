<?php /* @var $Station Station */ ?> 
<div class='right_col' role='main' >
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Configurazione Stazione') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <form id='StationForm' method='POST' action='' class='form-horizontal form-label-left file-upload' novalidate>
                    <div id='edit' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding'>
                                    <h2><?= _('Modifica') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <button type = 'submit' id = 'savebtn' style= 'display: none; margin-right: 10px;' id= 'savebtn' class='btn btn-success pull-right' ><?= _('SALVA') ?></button>
                                    <button type = 'button' id = 'modifybtn' style= 'display: none; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditObj();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
                                    <button type = 'button' id = 'undobtn' style= 'display: none; margin-right: 10px;' id= 'undobtn' onclick= 'undoObj();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>

                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='item form-group'>
                                    <label class='col-md-6 col-sm-6 col-xs-12' ><?= _('I campi contrassegnati con * sono obbligatori') ?></label>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('station-name') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('station name*')) ?> </small>
                                        <input type = 'text' id='station-name' name='station-name' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('station name')) ?> '  title=' <?php echo ( _('station name')) ?> '/>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('station-code') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('station code*')) ?> </small>
                                        <input type = 'text' id='station-code' name='station-code' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('station code')) ?> '  title=' <?php echo ( _('station code')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('observer') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('observer*')) ?> </small>
                                        <input type = 'text' id='observer' name='observer' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('observer')) ?> '  title=' <?php echo ( _('observer')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('longitude-observatory') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('longitude observatory*')) ?> </small>
                                        <input type = 'number' id='longitude-observatory' name='longitude-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('longitude observatory')) ?> '  title=' <?php echo ( _('longitude observatory')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('latitude-observatory') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('latitude observatory*')) ?> </small>
                                        <input type = 'number' id='latitude-observatory'  name='latitude-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('latitude observatory')) ?> '  title=' <?php echo ( _('latitude observatory')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('elevation-observatory') ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('elevation observatory*')) ?> </small>
                                        <input type = 'number' id='elevation-observatory' name='elevation-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('elevation observatory')) ?> '  title=' <?php echo ( _('elevation observatory')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?= _('Carica nuova maschera (esempio: default.bmp)') ?></small>
                                        <input id="station-mask-upload" class="form-control input-disabled" name="mask" type="file" accept=".bmp" id="form-mask">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/station.js<?= _VERSION_ ?>'></script>

