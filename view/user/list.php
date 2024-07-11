<?php /* @var $Users User[] */ ?> 
<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h3><?= _('Anagrafica') ?></h3>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title'>                     
                        <h2><?= _('Utenti') ?></h2><button type='button' onclick="window.location.href = '/user/edit'" class='btn btn-success btn-sm' style='margin-left: 20px'><?= _("Nuovo Utente") ?></button>                    

                        <div class='clearfix'></div>
                    </div>
                    <div class='x_content'>

                        <div id='content'>	<table id='UserList' style='width: 100%' class='table table-striped table-bordered'>
                                <thead>
                                    <tr>
                                        <th><?php echo (_('Nome')) ?></th>
                                        <th><?php echo (_('Cognome')) ?></th>
                                        <th><?php echo (_('Nome utente')) ?></th>
                                        <th><?php echo (_('Password')) ?></th>
                                        <th><?php echo (_('Livello')) ?></th>
                                        <th><?php echo (_('Modifica')) ?></th>
                                        <th><?php echo (_('Elimina')) ?></th>
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
</div>
<?php
include './view/template/foot.php';
?><!-- dataTable -->
<script>
    $(document).ready(function () {
        $('#UserList').dataTable({
            "oLanguage": {
                "sZeroRecords": "<?= _("Nessun risultato") ?>",
                "sSearch": "<?= _("Cerca") ?>:",
                "oPaginate": {
                    "sPrevious": "<?= _("Indietro") ?>",
                    "sNext": "<?= _("Avanti") ?>"
                },
                "sInfo": "<?= _("Mostra pagina _PAGE_ di _PAGES_") ?>",
                "sInfoFiltered": "",
                "sInfoEmpty": "<?= _("Mostra pagina 0 di 0 elementi") ?>",
                "sEmptyTable": "<?= _("Nessun risultato") ?>",
                "sLengthMenu": "<?= _("Mostra _MENU_ elementi") ?>"
            },
            "columnDefs": [{
                    "targets": [5, 6],
                    "orderable": false
                }],
            responsive: true,
            bProcessing: true,
            bServerSide: true,
            sAjaxSource: '/user/listAjax'
        });
    });
</script>
<!-- /dataTable -->

