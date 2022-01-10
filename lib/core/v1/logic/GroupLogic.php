<?php

class GroupLogic {

    public static function GetAccountGroup($Person) {
        $Group = GroupFactory::GetAccountGroup($Person->id);
        return $Group->id;
    }

    //  PRIMA CONFIGURAZIONE
    /*
      INSERT INTO `core_group`(`id`,`oid`,`name`,`type`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
      VALUES(1,NULL,"ADMIN","AC",null,null,null,now(),now(),null,0,now());
     * 
      INSERT INTO `core_group_has_person`(`id`,`oid`,`person_id`,`group_id`,`modified_by`,`created_by`,`assigned`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`)
      VALUES(null,null,1,1,1,null,null,now(),now(),null,0,now());

     * 
     * 
     * 
     */
}
