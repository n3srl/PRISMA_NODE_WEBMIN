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
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(name) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('name')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_name' multiple='multiple' title='<?php echo (_('Filtra per name')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(image) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('image')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_image' multiple='multiple' title='<?php echo (_('Filtra per image')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(command) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('command')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_command' multiple='multiple' title='<?php echo (_('Filtra per command')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(status) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('status')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_status' multiple='multiple' title='<?php echo (_('Filtra per status')) ?>'>
                                                    </select>
                                            </div>
                                    </div>
                            </div>
                            <div class='form-group col-md-3 col-sm-6 col-xs-12 <?= md5(created) ?>'>
                                    <div class='form-group'>
                                            <div class='col-xs-12'>
                                                    <small class='text-muted'><?php echo (_('created')) ?></small>
                                                    <select class='form-control filter filter-text' id='F_created' multiple='multiple' title='<?php echo (_('Filtra per created')) ?>'>
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
                        <table id='DockerList' class='table table-striped table-bordered' style='width: 100%; '>
                            <thead>
                                <tr>    
                                    <th><?php echo (_('Nome')) ?></th>
                                    <th><?php echo (_('Immagine')) ?></th>
                                    <th><?php echo (_('Comando')) ?></th>
                                    <th><?php echo (_('Stato')) ?></th>
                                    <th><?php echo (_('Creato')) ?></th>
                                    <th><?php echo (_('Start')) ?></th>
                                    <th><?php echo (_('Stop')) ?></th>
                                    <th><?php echo (_('Restart')) ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/docker.js<?= _VERSION_ ?>'></script>

