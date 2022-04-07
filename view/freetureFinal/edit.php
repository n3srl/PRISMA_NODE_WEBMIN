<?php /* @var $FreetureFinal FreetureFinal */ ?> 
<div class='right_col' role='main' >
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Configurazione Freeture') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>
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
                                    <form id="maskFileForm" method='post' class='mask-upload' enctype='multipart/form-data'>
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

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='list' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Elenco') ?></h2>
                            </div>

                        </div>
                    </div>
                    <!-- <div class='x_panel filter-container'>
                            <div class='x_title filter-title-container collapse-link'>
                                    <div class='filter-title'>
                                            <h2 class='font-15'>Filtra per...</h2>
                                            <ul class='nav navbar-right panel_toolbox'>
                                                    <li><a class='black'><i class='fa fa-chevron-down'></i></a>
                                                    </li>
                                            </ul>
                                    </div>
                            <div class='clearfix'></div>
                    </div>
                    <div class='x_content filter-content' hidden>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(id) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('id')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(key) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('key')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_key' multiple='multiple' title='<?php echo (_('Filtra per key')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(value) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('value')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_value' multiple='multiple' title='<?php echo (_('Filtra per value')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(description) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('description')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_description' multiple='multiple' title='<?php echo (_('Filtra per description')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(show) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('show')) ?></small>
                                                    <select class='form-control filter filter-checkbox' id='F_show' title='<?php echo (_('Filtra per show')) ?>'>
                                                            <option value=''></option>
                                                            <option value='1'> <?php echo (_('Sì')) ?></option>
                                                            <option value='0'> <?php echo (_('No')) ?></option>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-12 col-sm-12 col-xs-12'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <button class="pull-right btn btn-success applyFilter" ><?= _("Applica filtri") ?></button>
                                            </div>
                                    </div>
                            </div>
                    </div>
            </div> -->
                    <div class='x_content'>
                        <table id='FreetureFinalList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>
                                    <th><?php echo (_('Parametro')) ?></th>
                                    <th><?php echo (_('Valore')) ?></th>
                                    <th><?php echo (_('Descrizione')) ?></th>
                                    <th><?php echo (_('Show')) ?></th>
                                    <th></th>
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
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(id) ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('id')) ?> </small>
                                        <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('id')) ?> '  title=' <?php echo ( _('id')) ?> '/>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(key) ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('parametro')) ?> </small>
                                        <input type = 'text' name='key' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('key')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(value) ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('valore')) ?> </small>
                                        <input type = 'text' name='value' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('value')) ?>' maxlength = '128' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(description) ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('descrizione')) ?> </small>
                                        <input type = 'text' name='description' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _(' ')) ?> '  title=' <?php echo ( _('description')) ?>' maxlength = '512' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 <?= md5(show) ?>'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <label class='text-muted checkbox-label'><?php echo ( _('show')) ?></label>
                                        <input type = 'checkbox' onclick="$(this).val(this.checked ? 1 : 0)"  name='show' class='col-md-1 col-xs-1 checkbox input-disabled' placeholder='<?php echo ( _('show')) ?>' title='<?php echo ( _('show')) ?>'>
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
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/freetureFinal.js<?= _VERSION_ ?>'></script>

