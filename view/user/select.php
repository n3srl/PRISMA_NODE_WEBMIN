<?php /* @var $Users User[] */ ?> 
<select class='form-control' name="user_id" id="user_id" <?php echo $required ?>>
    <?php
    foreach ($Users as $User) {
        if ($User->id == $user_id) {
            ?>
            <option value="<?php echo $User->id ?>"  selected='selected'><?php echo $User->id ?></option>
            <?php
        } else {
            ?>
            <option value="<?php echo $User->id ?>" ><?php echo $User->id ?></option>
            <?php
        }
        ?>
    <?php } ?>
</select>
