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

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='default-migration-panel' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-8 no-padding-l'>
                                <h2><?= _('Migrazione configurazione DEFAULT') ?></h2>
                            </div>
                            <div class='col-md-4 text-right no-padding-r'>
                                <button type="button" class="btn btn-default" onclick="loadDefaultMigrationPreview()">
                                    <i class="fa fa-refresh"></i> <?= _('Aggiorna lista') ?>
                                </button>
                                <button id="btn-run-default-migration" type="button" class="btn btn-primary" onclick="runDefaultMigration()" disabled>
                                    <i class="fa fa-play"></i> <?= _('Migra tutto') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <p class="text-muted">
                            <?= _("Rinomina cartelle e file scritti dal nodo quando non era ancora configurato (STATION_CODE / STATION_NAME = DEFAULT) utilizzando la configurazione attuale letta da configuration.cfg. La cartella root /freeture/DEFAULT viene rinominata in /freeture/<STATION_CODE>, le sub-cartelle giorno ed evento mantengono la struttura usando il prefisso <STATION_NAME>, e i file .fit prefissati con DEFAULT_ vengono rinominati di conseguenza. Eventuali destinazioni gia' esistenti vengono saltate per evitare sovrascritture.") ?>
                        </p>

                        <div id="default-migration-status" style="margin-bottom: 12px;"></div>

                        <table id="DefaultMigrationList" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?= _('Tipo') ?></th>
                                    <th><?= _('Path attuale') ?></th>
                                    <th><?= _('Path dopo migrazione') ?></th>
                                    <th><?= _('Stato') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="4" class="text-center text-muted"><?= _('Premi "Aggiorna lista" per scansionare il filesystem') ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/manutenzione.js<?= _VERSION_ ?>'></script>
