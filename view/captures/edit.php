<?php /* @var $Person Person */ ?> 
<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Utenti') ?></h2>
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
                    <!--
                    <div class='x_panel filter-container'>
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
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('id')) ?></small>
                                        <select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 14c4b06b824ec593239362517f538b29'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Username')) ?></small>
                                        <select class='form-control filter filter-text' id='F_username' multiple='multiple' title='<?php echo (_('Filtra per Username')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 5f4dcc3b5aa765d61d8327deb882cf99' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Password')) ?></small>
                                        <select class='form-control filter filter-text' id='F_password' multiple='multiple' title='<?php echo (_('Filtra per password')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b2c6cc48f97ccd71b16d31d88fc177a6'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Timezone')) ?></small>
                                        <select class='form-control filter filter-text' id='F_timezone' multiple='multiple' title='<?php echo (_('Filtra per timezone')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-12 col-sm-12 col-xs-12'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <button style= 'margin-right: 10px;' class="pull-right btn btn-success btn-blue-success applyFilter" ><?= _("Applica filtri") ?></button>
                                        <button style= 'margin-right: 10px;' class="pull-right btn btn-warning btn-yellow-warning clearFilter" ><?= _("Pulisci filtri") ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class='x_content'>
                        <table id='PersonList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>
                                    <th><?php echo (_('ID')) ?></th>
                                    <th><?php echo (_('Username')) ?></th>
                                    <th><?php echo (_('Password')) ?></th>
                                    <th><?php echo (_('Timezone')) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <form id='CompanyForm' method='POST' action='/service/person/save/<?php echo $Person->id; ?>' class='form-horizontal form-label-left' novalidate>
                    <div id='edit' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding'>
                                    <h2><?= _('Modifica Password') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <!--<button type = 'button' style= 'display: none; margin-right: 10px;' id= 'deletebtn' class='btn btn-danger pull-right' onclick="removeObj()" ><?= _('ELIMINA') ?></button>-->
                                    <button type = 'submit' style= 'display: none; margin-right: 10px;' id= 'savebtn' class='btn btn-success pull-right' ><?= _('SALVA') ?></button>
                                    <!--<button type = 'button' style='display: none; margin-right: 10px;' id='cleanbtn' onclick='newObj()' class='btn btn-clean pull-right cleanForm' ><?= _('PULISCI CAMPI') ?></button>-->
                                    <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditObj();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
                                    <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'undobtn' onclick= 'undoObj();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>
                               </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='item form-group'>
                                    <label class='col-md-6 col-sm-6 col-xs-12' ><?= _('I campi contrassegnati con * sono obbligatori') ?></label>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('id')) ?> </small>
                                        <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('id')) ?> '  title=' <?php echo ( _('id')) ?> '/>
                                    </div>
                                </div>
                            </div>                            
                            <div class='form-group 5f4dcc3b5aa765d61d8327deb882cf99'>
                                <div class='col-md-3 col-sm-6 col-xs-12 '>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Nuova Password*')) ?> </small>
                                            <input type = 'password' name='new_password' class='form-control col-md-7 col-xs-12 input-disabled optional' data-validate-linked='confirm_password' placeholder=' <?php echo ( _('Nuova Password')) ?> '  title=' <?php echo ( _('Nuova Password')) ?>' maxlength = '100' />
                                            <meter max="4" id="password-strength-meter" class="col-md-12 col-xs-12"></meter>
                                            <p id="password-strength-text"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 5f4dcc3b5aa765d61d8327deb882cf99'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Conferma Password*')) ?> </small>
                                            <input type = 'password' name='confirm_password' class='form-control col-md-7 col-xs-12 input-disabled optional' data-validate-linked='new_password' placeholder=' <?php echo ( _('Conferma Password')) ?> '  title=' <?php echo ( _('Conferma Password')) ?>' maxlength = '100'/>
                                        </div>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/captures.js<?= _VERSION_ ?>'></script>


