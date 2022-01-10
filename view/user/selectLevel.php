<select class='form-control' name="level" id="user_id" <?php echo $required ?>>
    <option value="<?php echo UserLevel::ADMIN ?>" <?php if ($User->level == UserLevel::ADMIN) { ?> selected <?php } ?> ><?php echo _("Amministratore") ?></option>
    <option value="<?php echo UserLevel::ALL ?>"  <?php if ($User->level == UserLevel::ALL) { ?> selected <?php } ?>><?php echo _("Agente") ?></option>
</select>