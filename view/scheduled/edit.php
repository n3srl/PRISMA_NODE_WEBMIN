<?php // /* @var $Scheduled Scheduled */                ?>
<!-- Modal -->
<div class="modal fade" id="scheduled-preview-modal" tabindex="-1" role="dialog" aria-labelledby="scheduled-preview-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduled-preview-modal-label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="scheduled-preview-modal-body">
            </div>
        </div>
    </div>
</div>

<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h2><?= _('Acquisizioni Schedulate') ?></h2>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <!-- Stato schedulazione + prossimi scatti -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Stato e prossimi scatti') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12' id='scheduled-active-status'></div>
                        <div class='col-md-12 col-sm-12 col-xs-12' style='margin-top: 10px;'>
                            <h5><b><?= _('Prossimi 3 scatti (ora locale)') ?></b></h5>
                            <table id='NextShotsList' class='table table-striped table-bordered' style='width: 100%;'>
                                <thead>
                                    <tr>
                                        <th><?= _('Data e ora locale') ?></th>
                                        <th><?= _('Ora UTC') ?></th>
                                        <th><?= _('Esposizione (µs)') ?></th>
                                        <th><?= _('Gain') ?></th>
                                        <th><?= _('Formato') ?></th>
                                        <th><?= _('Ripetizioni') ?></th>
                                    </tr>
                                </thead>
                                <tbody id='next-shots-body'>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (CoreLogic::GetPersonLogged() != null && CoreLogic::VerifyPermission() >= 1) { ?>
            <!-- Editor schedulazioni (solo admin) -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Editor Schedulazioni') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>

                        <!-- Generatore eventi ripetuti -->
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5><b><?= _('Genera evento ripetuto') ?></b> <small><?= _('(gli orari inseriti sono in ora locale, salvati in UTC)') ?></small></h5>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Ora inizio (locale)') ?></small>
                            <input type='text' class='form-control' id='gen-start' placeholder='HH:MM:SS'>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Numero scatti') ?></small>
                            <input type='number' class='form-control' id='gen-count' value='1' min='1'>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Intervallo tra scatti (s)') ?></small>
                            <input type='number' class='form-control' id='gen-interval' value='60' min='0'>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Esposizione (µs)') ?></small>
                            <input type='number' class='form-control' id='gen-exposure' value='1000000' min='1'>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Gain') ?></small>
                            <input type='number' class='form-control' id='gen-gain' value='1' min='0'>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Formato') ?></small>
                            <input type='number' class='form-control' id='gen-format' value='1' min='0'>
                        </div>
                        <div class='clearfix'></div>

                        <!-- Bracketing -->
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'><?= _('Bracketing') ?></small><br>
                            <label class='switch'>
                                <input type='checkbox' id='gen-bracketing'>
                                <span class='slider round'></span>
                            </label>
                        </div>
                        <div class='bracketing-options' style='display:none;'>
                            <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                                <small class='text-muted'><?= _('Tipo bracketing') ?></small>
                                <select class='form-control' id='gen-bracket-type'>
                                    <option value='exposure'><?= _('Esposizione') ?></option>
                                    <option value='gain'><?= _('Gain') ?></option>
                                </select>
                            </div>
                            <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                                <small class='text-muted'><?= _('Passo') ?></small>
                                <input type='number' class='form-control' id='gen-bracket-step' value='500000' min='1'>
                            </div>
                            <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                                <small class='text-muted'><?= _('Numero step') ?></small>
                                <input type='number' class='form-control' id='gen-bracket-steps' value='3' min='2'>
                            </div>
                            <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                                <small class='text-muted'><?= _('Margine tra scatti consecutivi (s)') ?></small>
                                <input type='number' class='form-control' id='gen-bracket-margin' value='1' min='0'>
                            </div>
                        </div>
                        <div class='form-group col-md-2 col-sm-4 col-xs-6'>
                            <small class='text-muted'>&nbsp;</small><br>
                            <button class='btn btn-success' id='gen-add'><i class='fa fa-plus'></i> <?= _('Aggiungi scatti') ?></button>
                        </div>
                        <div class='clearfix'></div>
                        <div class='col-md-12'><hr style='margin: 8px 0;'></div>

                        <!-- Stringa manuale, lunghezza illimitata -->
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5><b><?= _('Stringa schedulazioni (formato freeture)') ?></b> <small><?= _('lunghezza illimitata, token separati da virgola: HHhMMmSSs&lt;esp&gt;e&lt;gain&gt;g&lt;formato&gt;f&lt;rip&gt;n (orari UTC)') ?></small></h5>
                            <textarea class='form-control' id='schedule-raw' rows='4' style='font-family: monospace; resize: vertical;'></textarea>
                            <div style='margin-top: 6px;'>
                                <button class='btn btn-default' id='raw-load'><i class='fa fa-arrow-down'></i> <?= _('Carica stringa nella preview') ?></button>
                            </div>
                        </div>
                        <div class='col-md-12'><hr style='margin: 8px 0;'></div>

                        <!-- Preview entries -->
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5><b><?= _('Preview schedulazioni') ?></b> <span id='schedule-count-badge' class='badge'></span></h5>
                            <div id='schedule-editor-warnings'></div>
                            <div style='max-height: 400px; overflow-y: auto;'>
                                <table id='SchedulePreviewList' class='table table-striped table-bordered' style='width: 100%;'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?= _('Ora UTC') ?></th>
                                            <th><?= _('Ora locale') ?></th>
                                            <th><?= _('Esposizione (µs)') ?></th>
                                            <th><?= _('Gain') ?></th>
                                            <th><?= _('Formato') ?></th>
                                            <th><?= _('Ripetizioni') ?></th>
                                            <th><?= _('Bracketing') ?></th>
                                            <th><?= _('Rimuovi') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id='schedule-preview-body'>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Azioni -->
                        <div class='col-md-12 col-sm-12 col-xs-12' style='margin-top: 10px;'>
                            <div class='form-group col-md-4 col-sm-6 col-xs-12 no-padding'>
                                <label>
                                    <input type='checkbox' id='schedule-enabled'>
                                    <?= _('Abilita acquisizione schedulata') ?>
                                </label>
                                <br><small class='text-muted'><?= _('Abilitandola verrà disabilitata l\'acquisizione regolare (vincolo freeture). Il salvataggio riavvia il container freeture.') ?></small>
                            </div>
                            <div class='pull-right'>
                                <button class='btn btn-warning' id='schedule-reload'><i class='fa fa-refresh'></i> <?= _('Ricarica da configurazione') ?></button>
                                <button class='btn btn-danger' id='schedule-clear'><i class='fa fa-trash'></i> <?= _('Svuota') ?></button>
                                <button class='btn btn-success' id='schedule-save'><i class='fa fa-save'></i> <?= _('Salva su configurazione') ?></button>
                            </div>
                        </div>
                        <div class='clearfix'></div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Elenco file acquisiti -->
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-4 col-sm-4 col-xs-12 no-padding'>
                                <h2><?= _('Elenco') ?></h2>
                            </div>
                            <div class='col-md-8 col-sm-8 col-xs-12 no-padding' id='enable-scheduled-preview-box'>
                                <div class='pull-right'>
                                    <div class='col-md-8'>
                                        <label class='pull-right text-nowrap'><?= _('Abilita anteprima') ?></label>
                                    </div>
                                    <div class='col-md-4'>
                                        <label class='switch pull-left'>
                                            <input type='checkbox' id='enable-scheduled-preview'>
                                            <span class='slider round'></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-3 col-sm-3 col-xs-12'>
                            <table id='ScheduledDayList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('#')) ?></th>
                                        <th><?php echo (_('Cartella')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class='col-md-9 col-sm-9 col-xs-12'>
                            <table id='ScheduledList' class='table table-striped table-bordered noclick' style='width: 100%; '>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Nome Acquisizione')) ?></th>
                                        <th><?php echo (_('Data')) ?></th>
                                        <th><?php echo (_('Ora')) ?></th>
                                        <th><?php echo (_('Anteprima')) ?></th>
                                        <th><?php echo (_('Scarica')) ?></th>
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
                <div class='x_panel'>
                    <div class='x_title no-padding-lr'>
                        <div class='clearfix'>
                            <div class='col-md-6 no-padding-l'>
                                <h2><?= _('Ultima Acquisizione Schedulata') ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class='x_content'>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                            <h5 id="last-scheduled-description"></h5>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12' id='last-scheduled-preview'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include './view/template/foot.php'; ?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/scheduled.js<?= _VERSION_ ?>'></script>
