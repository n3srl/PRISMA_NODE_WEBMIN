<?php /* @var $GroupHasPerson GroupHasPerson */ ?> 
<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('GroupHasPerson') ?></h2>
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
                            <div class='col-md-6 no-padding'>
                                <a href = '#edit' ><button type='button' onclick='newObj()' style='margin-right: 10px' class='btn btn-success pull-right' ><?= _('AGGIUNGI NUOVOgroupHasPerson') ?></button></a>
                            </div>
                        </div>
                    </div>
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
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('id')) ?></small>
                                        <select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 130f43112bb8a7a7790ebfc08ee9d6af'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('oid')) ?></small>
                                        <select class='form-control filter filter-text' id='F_oid' multiple='multiple' title='<?php echo (_('Filtra per oid')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 a8452ca7c1312f959e1307fe2d017eb0'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('person_id')) ?></small>
                                        <select class='form-control filter filter-text' id='F_person_id' multiple='multiple' title='<?php echo (_('Filtra per person_id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 0e939a4ffd3aacd724dd3b50147b4353'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('group_id')) ?></small>
                                        <select class='form-control filter filter-text' id='F_group_id' multiple='multiple' title='<?php echo (_('Filtra per group_id')) ?>'>
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
                    </div>
                    <div class='x_content'>
                        <table id='GroupHasPersonList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>
                                    <th><?php echo (_('oid')) ?></th>
                                    <th><?php echo (_('person_id')) ?></th>
                                    <th><?php echo (_('group_id')) ?></th>
                                    <th></th>
                                    <th></th>
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
                <form id='CompanyForm' method='POST' action='/service/groupHasPerson/save/<?php echo $GroupHasPerson->id; ?>' class='form-horizontal form-label-left' novalidate>
                    <div id='edit' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding'>
                                    <h2><?= _('Aggiungi nuovo') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <button type = 'submit' style= 'display: none; margin-right: 10px;' id= 'savebtn' class='btn btn-success pull-right' ><?= _('SALVA') ?></button>
                                    <button type = 'button' style='display: none; margin-right: 10px;' id='cleanbtn' onclick='newObj()' class='btn btn-clean pull-right cleanForm' ><?= _('PULISCI CAMPI') ?></button>
                                    <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'modifybtn' onclick= 'allowEditObj();' class='btn btn-success btn-blue-success pull-right' ><?= _('MODIFICA') ?></button>
                                    <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'undobtn' onclick= 'undoObj();' class='btn btn-warning btn-yellow-warning pull-right' ><?= _('ANNULLA') ?></button>
                                    <a href = '#list' ><button type='button' style='margin-right: 10px' class='btn btn-all pull-right' ><?= _('TUTTI') ?></button></a>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='item form-group'>
                                    <label class='col-md-6 col-sm-6 col-xs-12' ><?= _('I campi contrassegnati con * sono obbligatori') ?></label>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('id')) ?> </small>
                                        <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('id')) ?> '  title=' <?php echo ( _('id')) ?> '/>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 130f43112bb8a7a7790ebfc08ee9d6af'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('oid')) ?> </small>
                                        <input type = 'text' name='oid' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('oid')) ?> '  title=' <?php echo ( _('oid')) ?>' maxlength = '32' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 a8452ca7c1312f959e1307fe2d017eb0'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('person_id')) ?></small>
                                        <select class='form-control foreign_key' id='person_id' name='person_id' title='<?php echo(_('person_id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 0e939a4ffd3aacd724dd3b50147b4353'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('group_id')) ?></small>
                                        <select class='form-control foreign_key' id='group_id' name='group_id' title='<?php echo(_('group_id')) ?>'>
                                        </select>
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
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/crud/action.js<?= _VERSION_ ?>'></script>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/form.js<?= _VERSION_ ?>'></script>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/model/groupHasPersonClass.js<?= _VERSION_ ?>'></script>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/factory/groupHasPersonFactory.js<?= _VERSION_ ?>'></script>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/groupHasPerson.js<?= _VERSION_ ?>'></script>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/logic/groupHasPersonLogic.js<?= _VERSION_ ?>'></script>

