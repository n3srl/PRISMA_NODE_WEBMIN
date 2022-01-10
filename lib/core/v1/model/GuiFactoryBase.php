<?php

/**
 * Class for GuiFactory
 * 
 * @author: N3 S.r.l.
 */
class GuiFactoryBase {

    public static function Save($object) {
        global $db_conn;
        $ob = clone $object;
        GuiFactoryBase::cleanData($ob);
        GuiFactory::CheckData($ob);
        $query = "INSERT INTO core_gui (id,oid,name,description,parent_id,menu_item,create_date,valid_from,valid_to,erased,last_update) VALUES ( null,$ob->oid,$ob->name,$ob->description,$ob->parent_id,$ob->menu_item,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        $object->id = mysqli_insert_id($db_conn);

        return true;
    }

    public static function Delete($object) {
        global $db_conn;
        $query = "DELETE FROM core_gui WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Erase($object) {
        global $db_conn;
        $query = "UPDATE core_gui SET erased=1 WHERE id=" . $object->id . " AND erased=0";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Update($object) {
        global $db_conn;
        GuiFactoryBase::cleanData($object);
        GuiFactory::CheckData($object);
        $query = "UPDATE core_gui SET oid =$object->oid,name =$object->name,description =$object->description,parent_id = $object->parent_id,menu_item = $object->menu_item,create_date = $object->create_date,valid_from = $object->valid_from,valid_to = $object->valid_to,erased = $object->erased,last_update=now() WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    /** @return Gui */
    public static function Get($id) {
        global $db_conn;
        $query = "SELECT * FROM core_gui WHERE id=" . $id;
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

    /** @return Gui[] */
    public static function GetList($where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT * FROM core_gui $where_";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res, 'Gui')) {
            $object_list[] = self::LoadField($row);
        }
        return $object_list;
    }

    public static function CheckData($object, $clean = true) {
        
    }

    public static function cleanData($object) {
        $object->id = $object->id;
        if ($object->oid === null || $object->oid === '') {
            $object->oid = 'null';
        } else {
            $object->oid = "'$object->oid'";
        }
        if ($object->name === null || $object->name === '') {
            $object->name = 'null';
        } else {
            $object->name = "'$object->name'";
        }
        if ($object->description === null || $object->description === '') {
            $object->description = 'null';
        } else {
            $object->description = "'$object->description'";
        }
        if ($object->parent_id === null || $object->parent_id === '') {
            $object->parent_id = 'null';
        }
        if ($object->menu_item === null || $object->menu_item === '') {
            $object->menu_item = 'null';
        }
        if ($object->menu_item == '1') {
            $object->menu_item = 'true';
        } else {
            $object->menu_item = 'false';
        }
        if (empty($object->create_date)) {
            $object->create_date = 'null';
        } else {
            $object->create_date = "FROM_UNIXTIME('" . parseDateToMysql($object->create_date) . "')";
        }
        if (empty($object->valid_from)) {
            $object->valid_from = 'null';
        } else {
            $object->valid_from = "FROM_UNIXTIME('" . parseDateToMysql($object->valid_from) . "')";
        }
        if (empty($object->valid_to)) {
            $object->valid_to = 'null';
        } else {
            $object->valid_to = "FROM_UNIXTIME('" . parseDateToMysql($object->valid_to) . "')";
        }
        if ($object->erased === null || $object->erased === '') {
            $object->erased = 'null';
        }
        if ($object->erased == '1') {
            $object->erased = 'true';
        } else {
            $object->erased = 'false';
        }
    }

    public static function LoadField($object) {
        $object->create_date = strtotime($object->create_date);
        $object->valid_from = strtotime($object->valid_from);
        $object->valid_to = strtotime($object->valid_to);
        $object->last_update = strtotime($object->last_update);
        return $object;
    }

    public static function getMaxId() {
        global $db_conn;
        $query = "SELECT max(id) as max_id FROM core_gui";
        $res = mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return 1;
        $object = mysqli_fetch_object($res);
        $id = $object->max_id;
        if ($id == null) {
            return 1;
        }
        return $id;
    }

    public function __construct($handle) {
        
    }

}

?>
