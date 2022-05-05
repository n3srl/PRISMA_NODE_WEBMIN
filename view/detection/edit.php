<?php /* @var $Detection Detection */ ?> 
<!-- Modal -->
<div class="modal fade" id="detection-preview-modal" tabindex="-1" role="dialog" aria-labelledby="detection-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detection-preview-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detection-preview-modal-body">
            </div>
        </div>
    </div>
</div>

<a href="" id="download-event-zip"></a>


<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Detections') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='list' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-4 col-sm-4 col-xs-12 no-padding'>
                                <h2><?= _('Elenco') ?></h2>
                            </div>
                            <div class='col-md-8 col-sm-8 col-xs-12 no-padding'>
                                <div class='pull-right'>
                                    <div class='col-md-8'>
                                        <label class='pull-right text-nowrap' style='vertical-align: middle;'>Abilita anteprima</label>
                                    </div>
                                    <div class='col-md-4'>
                                        <label class='switch pull-right'>
                                            <input type='checkbox' id='enable-detection-preview'>
                                            <span class='slider round'></span>
                                        </label>
                                    </div>
                                </div>
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
                                        <small class='text-muted'><?php echo (_('Data')) ?></small>
                                        <select class='form-control filter filter-text' id='F_id' multiple='multiple' title='<?php echo (_('Filtra per id')) ?>'>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 14c4b06b824ec593239362517f538b29'>
                                <div class='form-group'>
                                    <div class='col-xs-12'>
                                        <small class='text-muted'><?php echo (_('Nome')) ?></small>
                                        <select class='form-control filter filter-text' id='F_username' multiple='multiple' title='<?php echo (_('Filtra per Username')) ?>'>
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
                        <div class='col-md-3 col-sm-3 col-xs-12'>
                            <table id='DetectionDayList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('#')) ?></th>
                                        <th><?php echo (_('Folder')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class='col-md-9 col-sm-9 col-xs-12'>
                            <table id='DetectionList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Nome Detection')) ?></th>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('Ora')) ?></th>
                                        <th><center><?php echo (_('Anteprima')) ?></center></th>
                                <th><center><?php echo (_('DirMap')) ?></center></th>
                                <th><center><?php echo (_('GeMap')) ?></center></th>
                                <th><center><?php echo (_('Zip')) ?></center></th>
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
                <div id='list' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Ultima Detection') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5 id="last-detection-description"></h5>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <!--<video>
                                <source src="/lib/detection/V2/detection/preview/lastdetection" type="video/webm">
                            </video>-->
                            <div class='col-md-12 col-sm-12 col-xs-12' id='last-detection-preview'>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/detection.js<?= _VERSION_ ?>'></script>


