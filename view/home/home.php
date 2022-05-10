<!-- Detection Modal -->
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
<!-- Mask Modal -->
<div class="modal fade" id="mask-preview-modal" tabindex="-1" role="dialog" aria-labelledby="detection-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding-lr">
                    <div class="col-md-8 col-sm-8 col-xs-8">
                        <h5 class="modal-title" id="mask-preview-modal-label">Mask</h5>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-r">
                        <a download="default.bmp" id="download-mask">
                            <button type="button" class="btn btn-success pull-right">Download</button>
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

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="count" id="all-detections-number">0</div>
                    <h3>Detection rilevate dalla stazione</h3>

                </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="count" id="month-detections-number">0</div>
                    <h3>Detection rilevate nel mese corrente</h3>

                </div>
            </div>
            <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="count" id="day-detections-number">0</div>
                    <h3>Detection rilevate oggi</h3>

                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12 no-padding-lr">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding-r">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-6 no-padding-l'>
                                    <h2><?= _('Geolocalizzazione stazione') ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div id="station-map" style="height: 328px"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-md-12 col-sm-12 col-xs-12 no-padding-r'>
                    <div id='list' class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-4 col-sm-4 col-xs-12 no-padding'>
                                    <h2><?= _('Detection rilevate oggi') ?></h2>
                                </div>
                                <div class='col-md-8 col-sm-8 col-xs-12 no-padding'>
                                    <div class='pull-right'>
                                        <div class='col-md-8'>
                                            <label class='pull-right text-nowrap'>Abilita anteprima</label>
                                        </div>
                                        <div class='col-md-4'>
                                            <label class='switch pull-left'>
                                                <input type='checkbox' id='enable-detection-preview'>
                                                <span class='slider round'></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <table id='DetectionList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                    <thead>
                                        <tr>
                                            <th><?php echo (_('Nome')) ?></th>
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
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding-r">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-6 no-padding-l'>
                                    <h2><?= _('Utilizzo Dati') ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <label>CPU</label>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" id="cpu-percentage" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <label>RAM</label>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-info" id="ram-percentage" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <label>Disco</label>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-danger" id="disk-percentage" role="progressbar" aria-valuenow="0"
                                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 no-padding-lr">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-8 no-padding'>
                                    <h2><?= _('Dati stazione') ?></h2>
                                </div>
                                <div class='col-md-4 no-padding'>
                                    <button type = 'button' style= 'margin-left: 10px;' id ="btn-show-mask" class='btn btn-success pull-right' ><?= _('Mask') ?></button>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <table id='StationInfo' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                    <thead>
                                        <tr>
                                            <th><?php echo (_('Parametro')) ?></th>
                                            <th><?php echo (_('Valore')) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id='StationInfoBody'>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-6 no-padding-l'>
                                    <h2><?= _('Ultima immagine rilevata') ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <h5 id="last-image-description"></h5>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div id='last-image-preview'>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row" style="height: 20px;"></div>
</div>
<?php
include "./view/template/foot.php";
?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/home.js<?= _VERSION_ ?>'></script>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyA7Wg-9vZ4pc0KTymAAh4L2x93HLRtWMZ4'></script>