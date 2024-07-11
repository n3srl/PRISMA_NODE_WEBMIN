<?php /* @var $Station Station */ ?> 
<!-- Mask Modal -->
<div class="modal fade" id="mask-preview-modal" tabindex="-1" role="dialog" aria-labelledby="detection-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding-lr">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="mask-preview-modal-label"><?= _('Maschera') ?></h5>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-r">
                        <a download="default.bmp" id="download-mask">
                            <button type="button" class="btn btn-success pull-right"><?= _('Scarica') ?></button>
                        </a>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding-r">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body" id="mask-preview-modal-body">
            </div>
        </div>
    </div>
</div>

<div class='right_col' role='main' >
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Configurazione Freeture') ?></h2>
            </div>
        </div>
        <div class='row'>
            <?php if (CoreLogic::GetPersonLogged() != null && CoreLogic::VerifyPermission() == 1) { ?>
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <div id='edit-buttons-ft' class='x_panel'>
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
                                        <h5><?= _('Carica nuovo file di configurazione Freeture (esempio: configuration.cfg)') ?></h5>
                                    </div>
                                    <div class='clearfix'>
                                        <form id="ftCfgFileForm" method='post' class='file-upload' enctype='multipart/form-data'>
                                            <div class='col-md-10 no-padding'>
                                                <input class="form-control" name="configuration" type="file" accept=".cfg" id="form-ftcfg">
                                            </div>
                                            <div class='col-md-2 no-padding'>
                                                <input type = 'submit' style= 'margin-right: 10px;' id= 'uploadftbtn' value='CARICA' disabled="true" class='btn btn-success pull-right' >
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                <div class="mb-3">
                                    <div class='col-md-12 col-sm-12 col-xs-12 no-padding'>
                                        <h5><?= _('Carica nuova maschera (esempio: default.bmp)') ?></h5>
                                    </div>
                                    <div class='clearfix'>
                                        <form id="maskFileForm" method='post' class='file-upload' enctype='multipart/form-data'>
                                            <div class='col-md-10 no-padding'>
                                                <input class="form-control" name="mask" type="file" accept=".bmp" id="form-mask">
                                            </div>
                                            <div class='col-md-2 no-padding'>
                                                <input type = 'submit' style= 'margin-right: 10px;' id= 'uploadmaskbtn' value='CARICA' disabled="true" class='btn btn-success pull-right' >
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <form id='StationForm' method='POST' action='' class='form-horizontal form-label-left file-upload' novalidate>
                    <div id='station-edit' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 col-sm-8 no-padding'>
                                    <h2><?= _('Configurazione automatica') ?></h2>
                                </div>
                                <div class='col-md-4 col-sm-4 no-padding'>
                                    <button type = 'submit' id = 'ftsavebtn' style= 'display: none; margin-right: 10px;' class='btn btn-success pull-right' ><?= _('SALVA') ?></button>
                                    <button type = 'button' id = 'ftmodifybtn' style= 'display: none; margin-left: 10px; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditStation();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
                                    <button type = 'button' id = 'ftundobtn' style= 'display: none; margin-left: 10px; margin-right: 10px;' id= 'undobtn' onclick= 'undoStation();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>

                                </div>
                            </div>
                        </div>
                        <div class='x_content'>

                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='item form-group'>
                                    <label class='col-md-6 col-sm-6 col-xs-12' ><?= _('I campi contrassegnati con * sono obbligatori') ?></label>
                                </div>
                            </div>
                            <?php if (CoreLogic::GetPersonLogged() != null && CoreLogic::VerifyPermission() == 1) { ?>
                                <div class='col-md-12 col-sm-12 col-xs-12'>
                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('station-name') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Nome stazione')) ?>* </small>
                                                    <input type = 'text' id='station-name' name='station-name' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Nome stazione')) ?> '  title=' <?php echo ( _('Nome stazione')) ?> '/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('station-code') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Codice stazione')) ?>* </small>
                                                    <input type = 'text' id='station-code' name='station-code' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Codice stazione')) ?> '  title=' <?php echo ( _('Codice stazione')) ?>' maxlength = '128' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('observer') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Osservatore')) ?>* </small>
                                                    <input type = 'text' id='observer' name='observer' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Osservatore')) ?> '  title=' <?php echo ( _('Osservatore')) ?>' maxlength = '128' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('elevation-observatory') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Altitudine')) ?>* </small>
                                                    <input type = 'number' id='elevation-observatory' name='elevation-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Altitudine')) ?> '  title=' <?php echo ( _('Altitudine')) ?>' maxlength = '128' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('longitude-observatory') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Longitudine')) ?>* </small>
                                                    <input type = 'number' id='longitude-observatory' name='longitude-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Longitudine')) ?> '  title=' <?php echo ( _('Longitudine')) ?>' maxlength = '128' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('latitude-observatory') ?>'>
                                            <div class='item form-group'>
                                                <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo ( _('Latitudine')) ?>* </small>
                                                    <input type = 'number' id='latitude-observatory'  name='latitude-observatory' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Latitudine')) ?> '  title=' <?php echo ( _('Latitudine')) ?>' maxlength = '128' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                 
                                    <div class='col-md-6 col-sm-12 col-xs-12'>
                                        <div class='col-md-12 col-sm-12 col-xs-12'>
                                            <div class='col-xs-12'>
                                                <small class='text-muted'><?php echo ( _('Indica le coordinate')) ?> </small>
                                                <div id="location-picker" style="height: 325px"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class='col-md-12 col-sm-12 col-xs-12 <?= md5('observer') ?>'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Osservatore')) ?>* </small>
                                            <input type = 'text' id='observer' name='observer' class='form-control col-md-7 col-xs-12 input-disabled required' placeholder=' <?php echo ( _('Osservatore')) ?> '  title=' <?php echo ( _('Osservatore')) ?>' maxlength = '128' />
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
            <?php if (CoreLogic::GetPersonLogged() != null && CoreLogic::VerifyPermission() == 1) { ?>
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding-l'>
                                    <h2><?= _('Configurazione manuale') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <button type = 'button' style= 'margin-left: 10px;' id ="btn-show-mask" class='btn btn-success pull-right' ><?= _('Maschera') ?></button>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <table id='FreetureFinalList' class='table table-striped table-bordered' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Parametro')) ?></th>
                                        <th><?php echo (_('Valore')) ?></th>
                                        <th><?php echo (_('Descrizione')) ?></th>
                                        <th><?php echo (_('Mostra')) ?></th>
                                        <th><?php echo (_('Rimosso')) ?></th>
                                        <th><?php echo (_('ID')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class='col-md-12 col-sm-12 col-xs-12'>
                    <form id='FreetureFinalForm' method='POST' action='/service/freetureFinal/save/<?php echo $FreetureFinal->id; ?>' class='form-horizontal form-label-left' novalidate>
                        <div id='edit' class='x_panel'>
                            <div class='x_title no-padding-lr'>
                                <div class='clearfix'>
                                    <div class='col-md-8 no-padding'>
                                        <h2><?= _('Modifica') ?></h2>
                                    </div>
                                    <div class='col-md-4 no-padding'>
                                        <button type = 'submit' style= 'display: none; margin-right: 10px;' id= 'savebtn' class='btn btn-success pull-right' ><?= _('SALVA') ?></button>
                                        <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditObj();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
                                        <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'undobtn' onclick= 'undoObj();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class='x_content'>
                                <div class='col-md-3 col-sm-6 col-xs-12 <?= md5('id') ?>'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('ID')) ?> </small>
                                            <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('ID')) ?> '  title=' <?php echo ( _('ID')) ?> '/>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 <?= md5('key') ?>'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Parametro')) ?> </small>
                                            <input type = 'text' name='key' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Chiave')) ?> '  title=' <?php echo ( _('Chiave')) ?>' maxlength = '128' />
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 <?= md5('value') ?>'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Valore')) ?> </small>
                                            <input type = 'text' name='value' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Valore')) ?> '  title=' <?php echo ( _('Valore')) ?>' maxlength = '128' />
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 <?= md5('description') ?>'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Descrizione')) ?> </small>
                                            <input type = 'text' name='description' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Descrizione')) ?> '  title=' <?php echo ( _('Descrizione')) ?>' maxlength = '512' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<?php if (CoreLogic::GetPersonLogged() != null && CoreLogic::VerifyPermission() == 1) { ?>
    <script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/station.js<?= _VERSION_ ?>'></script>
    <script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/freetureFinal.js<?= _VERSION_ ?>'></script>
<?php } else { ?>
    <script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/stationReduced.js<?= _VERSION_ ?>'></script>
<?php } ?>

<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyA7Wg-9vZ4pc0KTymAAh4L2x93HLRtWMZ4'></script>

