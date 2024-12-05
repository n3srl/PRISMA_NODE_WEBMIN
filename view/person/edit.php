<?php /* @var $Person Person */ ?> 
<input type="hidden" id = "my_level" value = "<?= CoreLogic::VerifyPermission() ?>">
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
                
                    <div class='x_content'>
                        <table id='PersonList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>
                                    <th><?php echo (_('Nome utente')) ?></th>
                                    <th><?php echo (_('Password')) ?></th>
                                    <th><?php echo (_('Timezone')) ?></th>
                                    <th><?php echo (_('Rimosso')) ?></th>
                                    <th><?php echo (_('Livello')) ?></th>
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
                                        <small class='text-muted'><?php echo ( _('ID')) ?> </small>
                                        <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('ID')) ?> '  title=' <?php echo ( _('ID')) ?> '/>
                                    </div>
                                </div>
                            </div>                            
                            <div class='form-group 5f4dcc3b5aa765d61d8327deb882cf99'>
                                <div class='col-md-3 col-sm-6 col-xs-12 '>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Nuova Password')) ?>* </small>
                                            <input type = 'password' name='new_password' class='form-control col-md-7 col-xs-12 input-disabled optional' data-validate-linked='confirm_password' placeholder=' <?php echo ( _('Nuova Password')) ?> '  title=' <?php echo ( _('Nuova Password')) ?>' maxlength = '100' />
                                            <meter max="4" id="password-strength-meter" class="col-md-12 col-xs-12"></meter>
                                            <p id="password-strength-text"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 5f4dcc3b5aa765d61d8327deb882cf99'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Conferma Password')) ?>* </small>
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
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/person.js<?= _VERSION_ ?>'></script>

