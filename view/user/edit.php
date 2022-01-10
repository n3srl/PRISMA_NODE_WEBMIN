<?php /* @var $User User */ ?> 
<div class='right_col' role='main'>
    <div class=''>
        <div class='page-title'>
            <div class='title_left'>
                <h3><?= _('Gruppo Menu') ?></h3>
            </div>
        </div>
        <div class='clearfix'></div>
        <div class='row'>

            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div class='x_panel'>
                    <div class='x_title'>
                        <h2><?= _('User') ?></h2>                   
                        <div class='clearfix'></div>
                    </div>
                    <div class='x_content'>

                        <div id='content'>
                            <form id='UserForm' method='POST' action='/service/user/save/<?php echo $User->id; ?>' class='form-horizontal form-label-left' novalidate>
                                <input type='hidden' name='id' id='id' value="<?php echo $User->id ?>"/>
                                <div class='item form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ></label>
                                    <label class='col-md-6 col-sm-6 col-xs-12' ><?= _('I campi contrassegnati con * sono obbligatori') ?>
                                    </label>
                                </div>						
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ><?php echo ( _('name')) ?>: </label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <input type='text' name='name'class='form-control col-md-7 col-xs-12' value="<?php echo parseText($User->name) ?>" maxlength = "250"/>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ><?php echo ( _('surname')) ?>: </label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <input type='text' name='surname'class='form-control col-md-7 col-xs-12' value="<?php echo parseText($User->surname) ?>" maxlength = "250"/>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ><?php echo ( _('username')) ?>: </label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <input type='text' name='username'class='form-control col-md-7 col-xs-12' value="<?php echo parseText($User->username) ?>" maxlength = "250"/>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ><?php echo ( _('password')) ?>: </label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <input type='text' name='password'class='form-control col-md-7 col-xs-12' value="<?php echo parseText($User->password) ?>" maxlength = "45"/>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <label class='control-label col-md-3 col-sm-3 col-xs-12' ><?php echo ( _('level')) ?>: </label>
                                    <div class='col-md-6 col-sm-6 col-xs-12'>
                                        <?php include "selectLevel.php" ?>
                                    </div>
                                </div>
                                <div class='form-group'>
                                    <div class='col-md-6 col-sm-6 col-xs-12 col-md-offset-3'>
                                        <button type='submit' class='btn btn-success'><?= _('Invia') ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include './view/template/foot.php';
?><!-- validator -->
<script>
    validator.defaults.alerts = false;
    validator.message.empty = "<?= _("Campo obbligatorio") ?>";
    validator.message.select = "<?= _("Campo obbligatorio") ?>";
    validator.message.number_min = "<?= _("Troppo basso") ?>";
    validator.message.number_max = "<?= _("Troppo alto") ?>";

    $('form')
            .on('blur', 'input[required], input.optional, select.required', validator.checkField)
            .on('change', 'select.required', validator.checkField)
            .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required').on('keyup blur', 'input', function () {
        validator.checkField.apply($(this).siblings().last()[0]);
    });

    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;

        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            defaultSubmitAjax(e);


        return false;
    });
</script>
<!-- /validator -->
<!-- date -->
<script>
    $(document).ready(function () {
        var setData = {
            showDropdowns: true, singleDatePicker: true, opens: 'right',
            calender_style: "picker_2",
            format: 'DD/MM/YYYY',

        };

        $('#last_update').daterangepicker(setData, null);
    });

</script>
<!-- /date -->

