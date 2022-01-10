<?php

/**
 * Class for GroupHasPersonFactory
 * 
 * @author: N3 S.r.l.
 */
class GroupHasPersonFactory extends GroupHasPersonFactoryBase {

    public static function CheckData($object, $clean = true) {
        $errors = array();
        $parse_error = false;
        if (!is_numeric($object->person_id) && $object->person_id != null && $object->person_id != '' && $object->person_id != 'null') {
            $errors[] = _('person_id non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->group_id) && $object->group_id != null && $object->group_id != '' && $object->group_id != 'null') {
            $errors[] = _('group_id non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->modified_by) && $object->modified_by != null && $object->modified_by != '' && $object->modified_by != 'null') {
            $errors[] = _('modified_by non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->created_by) && $object->created_by != null && $object->created_by != '' && $object->created_by != 'null') {
            $errors[] = _('created_by non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->assigned) && $object->assigned != null && $object->assigned != '' && $object->assigned != 'null') {
            $errors[] = _('assigned non numerico');
            $parse_error = true;
        }
        if (($object->erased === true && $object->erased != null) || $object->erased == 'true' || $object->erased == '1') {
            $object->erased = 1;
        } else {
            $object->erased = 0;
        }
        if (!is_numeric($object->erased) && $object->erased != null && $object->erased != '' && $object->erased != 'null') {
            $errors[] = _('erased non numerico');
            $parse_error = true;
        }
        if ($parse_error) {
            throw new FieldException($errors);
        }
    }

}
