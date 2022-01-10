<?php

/**
 * Class for N3Object
 * 
 * @author: N3 S.r.l.
 */
class N3Object {

    public $id;
    public $oid = null;
    public $create_date = null;
    public $valid_from = null;
    public $valid_to = null;
    public $erased = null;
    public $last_update;

    public static function InitN3Object($ob) {
        $ob->oid = CoreLogic::generateOID();
        $ob->create_date = DateLogic::toUser();
        $ob->valid_from = DateLogic::toUser();
        $ob->erased = 0;
    }

}

?>
