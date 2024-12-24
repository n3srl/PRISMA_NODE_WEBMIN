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
            <div class="modal-body" id="detection-preview-modal-body"></div>
        </div>
    </div>
</div>

<!-- Download anchor (hidden) -->
<a href="" id="download-event-zip" style="display: none;"></a>

<div class="right_col" role="main">
    <div class="container-fluid">
        <!-- Title Section -->
        <div class="page-title">
            <div class="title_left">
                <h2><?= _('Detections') ?></h2>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="row">
                            <div class="col-md-4">
                                <h2><?= _('Elenco') ?></h2>
                            </div>
                            <div class="col-md-8 text-right" id="enable-detection-preview-box">
                                <label class="switch">
                                    <input type="checkbox" id="enable-detection-preview">
                                    <span class="slider round"></span>
                                </label>
                                <span class="ml-2"><?= _('Abilita anteprima') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="x_content">
                        <div class="row">
                            <!-- Detection Day List -->
                            <div class="col-md-3">
                                <table id="DetectionDayList" class="table table-striped table-bordered noclick">
                                    <thead>
                                        <tr>
                                            <th><?= _('Data') ?></th>
                                            <th><?= _('#') ?></th>
                                            <th><?= _('Cartella') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <!-- Detection List -->
                            <div class="col-md-9">
                                <table id="DetectionList" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?= _('Nome Detection') ?></th>
                                            <th><?= _('Data') ?></th>
                                            <th><?= _('Ora') ?></th>
                                            <th class="text-center"><?= _('Anteprima') ?></th>
                                            <th class="text-center"><?= _('DirMap') ?></th>
                                            <th class="text-center"><?= _('GeMap') ?></th>
                                            <th class="text-center"><?= _('Video') ?></th>
                                            <th class="text-center"><?= _('Zip') ?></th>
                                            <th><?= _('Durata') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_content">

                    <div class="row d-flex align-items-stretch" style="height: 100%;">
                        <div class="col-md-5 d-flex flex-column justify-content-center" style="height: 80%;">
                                
                                <div class="x_title">
                                    <div class="clearfix">
                                        <h2><?= _('Ultima Detection') ?></h2>
                                    </div>
                                </div>

                                <div class="x_content" style="max-width: 90%; height: 80%">
                                    <h5 id="last-detection-description"></h5>
                                    <div id="last-detection-preview"></div>
                                </div>

                        </div>

                            <div class="col-md-6 d-flex flex-column justify-content-center" style="height: 80%;">
                                <div class="x_content d-flex flex-column justify-content-center">
                                    <?php include './view/detectionGraph/graph.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include './view/template/foot.php'; ?>
<script src="<?= $_SERVER['PATH_WEBROOT'] ?>/js/detection.js<?= _VERSION_ ?>"></script>
