<?php /* @var $Manutenzione Manutenzione */ ?>

<div class='right_col' role='main'>
    <div class=''>
        <div class='clearfix'></div>
        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='list' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Reboot') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <button type="button" class="btn btn-danger" onclick="exec_reboot()"> <?= _('Avvia il reboot') ?></button>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>



<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/manutenzione.js<?= _VERSION_ ?>'></script>