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
                            <div class='col-md-6 no-padding'>
                                <a href = '#edit' ><button type='button' onclick='newObj()' style='margin-right: 10px' class='btn btn-success pull-right' ><?= _('AGGIUNGI NUOVO UTENTE') ?></button></a>
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
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('id')) ?></small>
                                        <select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 130f43112bb8a7a7790ebfc08ee9d6af' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('oid')) ?></small>
                                        <select class='form-control filter filter-text' id='F_oid' multiple='multiple' title='<?php echo (_('Filtra per oid')) ?>'>
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
                                        <small class='text-muted'><?php echo (_('password')) ?></small>
                                        <select class='form-control filter filter-text' id='F_password' multiple='multiple' title='<?php echo (_('Filtra per password')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 d5d3db1765287eef77d7927cc956f50a' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('title')) ?></small>
                                        <select class='form-control filter filter-text' id='F_title' multiple='multiple' title='<?php echo (_('Filtra per title')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 2a034e9d9e2601c21191cca53760eaaf'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Nome')) ?></small>
                                        <select class='form-control filter filter-text' id='F_first_name' multiple='multiple' title='<?php echo (_('Filtra per Nome')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 e2e657fbf2b8662e5c235b568646a061'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Secondo Nome')) ?></small>
                                        <select class='form-control filter filter-text' id='F_middle_name' multiple='multiple' title='<?php echo (_('Filtra per Secondo Nome')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 7d4553c09a59578c8addc8c617a76ca1'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Cognome')) ?></small>
                                        <select class='form-control filter filter-text' id='F_last_name' multiple='multiple' title='<?php echo (_('Filtra per Cognome')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 4ec1b477cd0232b832c1899905ec51a4' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('suffix')) ?></small>
                                        <select class='form-control filter filter-text' id='F_suffix' multiple='multiple' title='<?php echo (_('Filtra per suffix')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 93c731f1c3a84ef05cd54d044c379eaa' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('company')) ?></small>
                                        <select class='form-control filter filter-text' id='F_company' multiple='multiple' title='<?php echo (_('Filtra per company')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 29f840186cf011c1b53858b4718cf213' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('job_title')) ?></small>
                                        <select class='form-control filter filter-text' id='F_job_title' multiple='multiple' title='<?php echo (_('Filtra per job_title')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 0c83f57c786a0b4a39efab23731c7ebc'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Email')) ?></small>
                                        <select class='form-control filter filter-text' id='F_email' multiple='multiple' title='<?php echo (_('Filtra per Email')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b1e26a3d94716d4ffb03d4749252b633' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('web_page_address')) ?></small>
                                        <select class='form-control filter filter-text' id='F_web_page_address' multiple='multiple' title='<?php echo (_('Filtra per web_page_address')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 f58bd040a4aa67f289da9da603b9d5cf' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('im_address')) ?></small>
                                        <select class='form-control filter filter-text' id='F_im_address' multiple='multiple' title='<?php echo (_('Filtra per im_address')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 f7a42fe7211f98ac7a60a285ac3a9e87' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('phone')) ?></small>
                                        <select class='form-control filter filter-text' id='F_phone' multiple='multiple' title='<?php echo (_('Filtra per phone')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 884d9804999fc47a3c2694e49ad2536a' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('address')) ?></small>
                                        <select class='form-control filter filter-text' id='F_address' multiple='multiple' title='<?php echo (_('Filtra per address')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 e90ebd9556fa4031171f043013794b61' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('postcode')) ?></small>
                                        <select class='form-control filter filter-text' id='F_postcode' multiple='multiple' title='<?php echo (_('Filtra per postcode')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b1bc248a7ff2b2e95569f56de68615df' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('number')) ?></small>
                                        <select class='form-control filter filter-text' id='F_number' multiple='multiple' title='<?php echo (_('Filtra per number')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 4ed5d2eaed1a1fadcc41ad1d58ed603e' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('city')) ?></small>
                                        <select class='form-control filter filter-text' id='F_city' multiple='multiple' title='<?php echo (_('Filtra per city')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 53aad639aca4b5c010927cf610c3ff9c' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('province')) ?></small>
                                        <select class='form-control filter filter-text' id='F_province' multiple='multiple' title='<?php echo (_('Filtra per province')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 e909c2d7067ea37437cf97fe11d91bd0' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('country')) ?></small>
                                        <select class='form-control filter filter-text' id='F_country' multiple='multiple' title='<?php echo (_('Filtra per country')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 b2c6cc48f97ccd71b16d31d88fc177a6' hidden>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('timezone')) ?></small>
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
                    </div>
                    <div class='x_content'>
                        <table id='PersonList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>
                                    <th><?php echo (_('Username')) ?></th>
                                    <th><?php echo (_('Email')) ?></th>
                                    <th><?php echo (_('Appellativo')) ?></th>
                                    <th><?php echo (_('Nome')) ?></th>
                                    <th><?php echo (_('Secondo Nome')) ?></th>
                                    <th><?php echo (_('Cognome')) ?></th>
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
                <form id='CompanyForm' method='POST' action='/service/person/save/<?php echo $Person->id; ?>' class='form-horizontal form-label-left' novalidate>
                    <div id='edit' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding'>
                                    <h2><?= _('Aggiungi nuovo') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <button type = 'button' style= 'display: none; margin-right: 10px;' id= 'deletebtn' class='btn btn-danger pull-right' onclick="removeObj()" ><?= _('ELIMINA') ?></button>
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
                            <div class='col-md-3 col-sm-6 col-xs-12 b80bb7740288fda1f201890375a60c8f' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('id')) ?> </small>
                                        <input type = 'text' name='id' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('id')) ?> '  title=' <?php echo ( _('id')) ?> '/>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 130f43112bb8a7a7790ebfc08ee9d6af' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('oid')) ?> </small>
                                        <input type = 'text' name='oid' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('oid')) ?> '  title=' <?php echo ( _('oid')) ?>' maxlength = '32' />
                                    </div>
                                </div>
                            </div>
                            <div class='form-group 5f4dcc3b5aa765d61d8327deb882cf99'>
                                <div class='col-md-3 col-sm-6 col-xs-12 '>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Nuova Password')) ?> </small>
                                            <input type = 'password' name='new_password' class='form-control col-md-7 col-xs-12 input-disabled optional' data-validate-linked='confirm_password' placeholder=' <?php echo ( _('Nuova Password')) ?> '  title=' <?php echo ( _('Nuova Password')) ?>' maxlength = '100' />
                                            <meter max="4" id="password-strength-meter" class="col-md-12 col-xs-12"></meter>
                                            <p id="password-strength-text"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 5f4dcc3b5aa765d61d8327deb882cf99'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Conferma Password')) ?> </small>
                                            <input type = 'password' name='confirm_password' class='form-control col-md-7 col-xs-12 input-disabled optional' data-validate-linked='new_password' placeholder=' <?php echo ( _('Conferma Password')) ?> '  title=' <?php echo ( _('Conferma Password')) ?>' maxlength = '100'/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group'>
                                <div class='col-md-3 col-sm-6 col-xs-12 14c4b06b824ec593239362517f538b29'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Username')) ?> </small>
                                            <input type = 'text' name='username' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Username')) ?> '  title=' <?php echo ( _('Username')) ?>' maxlength = '100' />
                                            <small class='col-md-12'><?php echo ( _('[ Utilizzato per la Login ]')) ?> </small>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-3 col-sm-6 col-xs-12 0c83f57c786a0b4a39efab23731c7ebc'>
                                    <div class='item form-group'>
                                        <div class='col-xs-12'>
                                            <small class='text-muted'><?php echo ( _('Email')) ?> </small>
                                            <input type = 'text' name='email' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Email')) ?> '  title=' <?php echo ( _('Email')) ?>' maxlength = '100' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 d5d3db1765287eef77d7927cc956f50a'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('Appellativo')) ?> </small>
                                        <input type = 'text' name='title' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Appellativo')) ?> '  title=' <?php echo ( _('Appellativo')) ?>' maxlength = '10' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 2a034e9d9e2601c21191cca53760eaaf'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('Nome')) ?> </small>
                                        <input type = 'text' name='first_name' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Nome')) ?> '  title=' <?php echo ( _('Nome')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 e2e657fbf2b8662e5c235b568646a061'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('Secondo Nome')) ?> </small>
                                        <input type = 'text' name='middle_name' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Secondo Nome')) ?> '  title=' <?php echo ( _('Secondo Nome')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 7d4553c09a59578c8addc8c617a76ca1'>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('Cognome')) ?> </small>
                                        <input type = 'text' name='last_name' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('Cognome')) ?> '  title=' <?php echo ( _('Cognome')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 4ec1b477cd0232b832c1899905ec51a4' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('suffix')) ?> </small>
                                        <input type = 'text' name='suffix' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('suffix')) ?> '  title=' <?php echo ( _('suffix')) ?>' maxlength = '10' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 93c731f1c3a84ef05cd54d044c379eaa' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('company')) ?> </small>
                                        <input type = 'text' name='company' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('company')) ?> '  title=' <?php echo ( _('company')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 29f840186cf011c1b53858b4718cf213' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('job_title')) ?> </small>
                                        <input type = 'text' name='job_title' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('job_title')) ?> '  title=' <?php echo ( _('job_title')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-3 col-sm-6 col-xs-12 b1e26a3d94716d4ffb03d4749252b633' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('web_page_address')) ?> </small>
                                        <input type = 'text' name='web_page_address' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('web_page_address')) ?> '  title=' <?php echo ( _('web_page_address')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 f58bd040a4aa67f289da9da603b9d5cf' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('im_address')) ?> </small>
                                        <input type = 'text' name='im_address' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('im_address')) ?> '  title=' <?php echo ( _('im_address')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 f7a42fe7211f98ac7a60a285ac3a9e87' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('phone')) ?> </small>
                                        <input type = 'text' name='phone' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('phone')) ?> '  title=' <?php echo ( _('phone')) ?>' maxlength = '45' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 884d9804999fc47a3c2694e49ad2536a' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('address')) ?> </small>
                                        <input type = 'text' name='address' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('address')) ?> '  title=' <?php echo ( _('address')) ?>' maxlength = '200' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 e90ebd9556fa4031171f043013794b61' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('postcode')) ?> </small>
                                        <input type = 'text' name='postcode' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('postcode')) ?> '  title=' <?php echo ( _('postcode')) ?>' maxlength = '45' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 b1bc248a7ff2b2e95569f56de68615df' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('number')) ?> </small>
                                        <input type = 'text' name='number' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('number')) ?> '  title=' <?php echo ( _('number')) ?>' maxlength = '45' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 4ed5d2eaed1a1fadcc41ad1d58ed603e' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('city')) ?> </small>
                                        <input type = 'text' name='city' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('city')) ?> '  title=' <?php echo ( _('city')) ?>' maxlength = '45' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 53aad639aca4b5c010927cf610c3ff9c' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('province')) ?> </small>
                                        <input type = 'text' name='province' class='form-control col-md-7 col-xs-12 input-disabled to-upper' placeholder=' <?php echo ( _('province')) ?> '  title=' <?php echo ( _('province')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 e909c2d7067ea37437cf97fe11d91bd0' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('country')) ?> </small>
                                        <input type = 'text' name='country' class='form-control col-md-7 col-xs-12 input-disabled to-upper' placeholder=' <?php echo ( _('country')) ?> '  title=' <?php echo ( _('country')) ?>' maxlength = '100' />
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-3 col-sm-6 col-xs-12 b2c6cc48f97ccd71b16d31d88fc177a6' hidden>
                                <div class='item form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo ( _('timezone')) ?> <span class='required'>*</span></small>
                                        <input type = 'text' name='timezone' required='required' class='form-control col-md-7 col-xs-12 input-disabled' placeholder=' <?php echo ( _('timezone')) ?> '  title=' <?php echo ( _('timezone')) ?>' maxlength = '100' />
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

