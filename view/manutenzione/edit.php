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
                                <h2><?= _('Migrazione dati stazione') ?></h2>
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
                            <?= _("Rinomina cartelle e file di una stazione SORGENTE verso la configurazione attuale (DESTINAZIONE) letta da configuration.cfg. La cartella root /freeture/<sorgente> viene unita in /freeture/<STATION_CODE>, le sub-cartelle giorno ed evento mantengono la struttura aggiornando i prefissi, e i file .fit vengono rinominati di conseguenza (captures e cartelle giorno usano STATION_CODE, stacks ed eventi usano STATION_NAME). Caso tipico: dati storici scritti quando il nodo non era ancora configurato (sorgente DEFAULT) oppure configurato con un codice/nome errato. Eventuali destinazioni gia' esistenti vengono saltate per evitare sovrascritture.") ?>
                        </p>

                        <div class="form-inline" style="margin-bottom: 12px;">
                            <div class="form-group" style="margin-right: 16px;">
                                <label for="migration-src-code" style="margin-right: 6px;"><?= _('Codice stazione SORGENTE') ?></label>
                                <select id="migration-src-code" class="form-control" onchange="onMigrationSourceChange()">
                                    <option value=""><?= _('Caricamento...') ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="migration-src-name" style="margin-right: 6px;"><?= _('Nome stazione SORGENTE') ?></label>
                                <input type="text" id="migration-src-name" class="form-control" value="DEFAULT" placeholder="DEFAULT">
                            </div>
                        </div>

                        <div id="default-migration-status" style="margin-bottom: 12px;"></div>

                        <div id="default-migration-progress-wrap" style="display:none; margin-bottom: 12px;">
                            <div class="progress" style="height: 22px; margin-bottom: 4px;">
                                <div id="default-migration-progress-bar"
                                     class="progress-bar progress-bar-striped active"
                                     role="progressbar"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                     style="width: 0%; line-height: 22px; font-weight: 600;">0%</div>
                            </div>
                            <div id="default-migration-progress-text" style="font-size: 12px; color:#555;"></div>
                        </div>

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

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='fits-header-panel' class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-8 no-padding-l'>
                                <h2><?= _('Riallineamento header FITS') ?></h2>
                            </div>
                            <div class='col-md-4 text-right no-padding-r'>
                                <button type="button" class="btn btn-default" onclick="loadFitsHeaderPreview()">
                                    <i class="fa fa-refresh"></i> <?= _('Aggiorna lista') ?>
                                </button>
                                <button id="btn-run-fits-header" type="button" class="btn btn-primary" onclick="runFitsHeader()" disabled>
                                    <i class="fa fa-play"></i> <?= _('Applica a tutti') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <p class="text-muted">
                            <?= _("Riscrive i valori delle keyword dell'header dei file .fit (TELESCOP, OBSERVER, INSTRUME, SITELAT, SITELONG, ecc.) allineandoli alla configurazione attuale letta dalla sezione FITS di configuration.cfg. Inoltre riallinea la keyword FILENAME al nome reale del file (utile dopo una migrazione che ha rinominato i file). Viene modificato solo il valore di keyword gia' presenti nell'header (la dimensione del file e i dati immagine restano invariati); STATION_NAME e COMMENT sono escluse. Seleziona la cartella stazione su cui operare.") ?>
                        </p>

                        <div class="form-inline" style="margin-bottom: 12px;">
                            <div class="form-group">
                                <label for="fits-src-code" style="margin-right: 6px;"><?= _('Cartella stazione') ?></label>
                                <select id="fits-src-code" class="form-control" onchange="onFitsSourceChange()">
                                    <option value=""><?= _('Caricamento...') ?></option>
                                </select>
                            </div>
                        </div>

                        <div id="fits-header-status" style="margin-bottom: 12px;"></div>

                        <table id="FitsHeaderList" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th><?= _('Keyword') ?></th>
                                    <th><?= _('Valore attuale (esempio)') ?></th>
                                    <th><?= _('Valore nuovo') ?></th>
                                    <th><?= _('File da aggiornare') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="4" class="text-center text-muted"><?= _('Premi "Aggiorna lista" per scansionare gli header') ?></td></tr>
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
