<?php

class permissionLogic {

    public static function SmallPermission($Permission) {
        unset($Permission->id);
        unset($Permission->oid);
        unset($Permission->ext_oid);
        unset($Permission->person_id);
        unset($Permission->group_id);
        unset($Permission->username);
        unset($Permission->secret_token);
        unset($Permission->modified_by);
        unset($Permission->created_by);
        unset($Permission->assigned);
        unset($Permission->create_date);
        unset($Permission->valid_from);
        unset($Permission->valid_to);
        unset($Permission->erased);
        unset($Permission->last_update);
        return $Permission;
    }

}
