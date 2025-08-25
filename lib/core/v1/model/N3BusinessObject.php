<?php

/**
 * Class for N3Object
 * 
 * @author: N3 S.r.l.
 */
class N3BusinessObject extends N3Object {

    public $modified_by = null;
    public $created_by = null;
    public $assigned = null;

    public static function Init($ob, $Person) {

        N3Object::InitN3Object($ob);

        $ob->modified_by = $Person->id;
        $ob->created_by = $Person->id;
        $ob->assigned = $Person->id;
    }

    public static function SetModified($ob, $Person) {
        $ob->modified_by = $Person->id;
    }

}

?>
