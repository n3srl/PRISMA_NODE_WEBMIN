<?php

/**
 * Class for GuiFactory
 * 
 * @author: N3 S.r.l.
 */
class GuiFactory extends GuiFactoryBase {

    public static function CheckData($object, $clean = true) {
        $errors = array();
        $parse_error = false;
        if (!is_numeric($object->parent_id) && $object->parent_id != null && $object->parent_id != '' && $object->parent_id != 'null') {
            $errors[] = _('parent_id non numerico');
            $parse_error = true;
        }
        if (($object->menu_item === true && $object->menu_item != null) || $object->menu_item == 'true' || $object->menu_item == '1') {
            $object->menu_item = 1;
        } else {
            $object->menu_item = 0;
        }
        if (!is_numeric($object->menu_item) && $object->menu_item != null && $object->menu_item != '' && $object->menu_item != 'null') {
            $errors[] = _('menu_item non numerico');
            $parse_error = true;
        }
        if (!is_numeric($object->sorting) && $object->sorting != null && $object->sorting != '' && $object->sorting != 'null') {
            $errors[] = _('sorting non numerico');
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

    /** @return Gui[] */
    public static function GetFullMenu($person, $parent_id) {
        global $db_conn;
        $object_list = array();

        $query = "select * from (
                    select  id,name,description,parent_id, oid, sorting 
                    from    (select * from core_gui where erased = 0
                             order by parent_id, id) guis_sorted,
                            (select @pv := '$parent_id') initialisation
                    where   find_in_set(parent_id, @pv)
                    and     length(@pv := concat(@pv, ',', id))
                ) menu
                where oid in (select distinct ext_oid 
                    from core_permission where 
                        (person_id = $person->id or group_id in (select group_id from core_group_has_person where person_id = $person->id)) 
                        and active = $person->id and `read` = 1 and erased = 0)";

        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res, 'Gui')) {
            $object_list[] = self::LoadField($row);
        }
        return $object_list;
    }

    /** @return Group */
    public static function GetRowMenu() {
        global $db_conn;
        $query = "select * from core_gui where name = 'MENU'";
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

}
