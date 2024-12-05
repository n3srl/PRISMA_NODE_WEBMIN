<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h2><?= _('Impostazioni') ?></h2>
            </div>
        </div>
        <div class="clearfix"></div>
    <? if(CoreLogic::VerifyPermission()>=1){?>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-6 no-padding-l'>
                                    <h2><?= _('Visualizzazione Media') ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='col-md-8'>
                                    <label style="font-size:14px;"><?= _('Abilita possibilità di visualizzare le anteprime') ?></label>
                                </div>
                                <div class='col-md-4'>
                                    <label class='switch pull-right' style='vertical-align: middle;'>
                                        <input type='checkbox' id='enable-media-preview'>
                                        <span class='slider round'></span>
                                    </label>
                                </div>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <div class='col-md-8'>
                                    <label style="font-size:14px;"><?= _('Abilita possibilità di scaricare video e zip') ?></label>
                                </div>
                                <div class='col-md-4'>
                                    <label class='switch pull-right' style='vertical-align: middle;'>
                                        <input type='checkbox' id='enable-media-processing'>
                                        <span class='slider round'></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class='x_panel'>
                        <div class='x_title no-padding-lr'>
                            <div class='clearfix'>
                                <div class='col-md-6 no-padding-l'>
                                    <h2><?= _('Archiviazione Media') ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class='x_content'>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <label style='font-size:14px; padding-bottom:10px;' id='mediausagelbl'></label>
                            </div>
                            <div class='col-md-12 col-sm-12 col-xs-12'>
                                <button type = 'button' onclick='cleanMedia()' class='btn btn-danger'><i class='fa fa-trash' style='margin-right:10px;'></i><?= _('Pulisci media') ?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?} ?>
    </div>

    <div class="col-md-12 col-sm-12 col-xs-12"> 
    <div class="x_panel">
        <div class="x_title no-padding-lr">
            <div class="clearfix">
                <div class="col-md-6 no-padding-l">
                    <h2><?= _('Lingua') ?></h2> 
                </div>
            </div>
        </div>
        <div class="x_content">
            <div class="col-md-12 col-sm-12 col-xs-12">
            <h5> <label for="language"><?= _('Seleziona Lingua') ?>:</label> </h5>
            <?php PrismaMultilanguage::getViewOptions(); ?>
            </div>
        </div>
    </div>
</div>

   




</div>
<?php
include "./view/template/foot.php";
?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/settings.js<?= _VERSION_ ?>'></script>
