<?php

/**
 * Class for PersonFactory
 * 
 * @author: N3 S.r.l.
 */
class PersonFactory extends PersonFactoryBase {

    public static function CheckData($object, $clean = true) {
        $errors = array();
        $parse_error = false;
        if (!is_numeric($object->modified_by) && $object->timezone != null && $object->timezone != '' && $object->timezone != 'null') {
            $errors[] = _('timezone non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->modified_by) && $object->modified_by != null && $object->modified_by != '' && $object->modified_by != 'null') {
            $errors[] = _('Modificato da non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->created_by) && $object->created_by != null && $object->created_by != '' && $object->created_by != 'null') {
            $errors[] = _('Creato da non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->assigned) && $object->assigned != null && $object->assigned != '' && $object->assigned != 'null') {
            $errors[] = _('Assegnato non numerico');
            $parse_error = true;
        }
        if (($object->erased === true && $object->erased != null) || $object->erased == 'true' || $object->erased == '1') {
            $object->erased = 1;
        } else {
            $object->erased = 0;
        }
        if (!is_numeric($object->erased) && $object->erased != null && $object->erased != '' && $object->erased != 'null') {
            $errors[] = _('Rimosso non numerico');
            $parse_error = true;
        }
        if ($parse_error) {
            throw new FieldException($errors);
        }
    }

    /** @return Person */
    public static function Get4Username($username) {
        global $db_conn;
        $query = "SELECT * FROM core_person WHERE username ='$username' and erased = 0";
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

}
