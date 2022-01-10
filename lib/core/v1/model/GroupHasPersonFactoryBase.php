<?php

/**
 * Class for GroupHasPersonFactory
 * 
 * @author: N3 S.r.l.
 */
class GroupHasPersonFactoryBase {

    public static function Save($object) {
        global $db_conn;
        $ob = clone $object;
        GroupHasPersonFactoryBase::cleanData($ob);
        GroupHasPersonFactory::CheckData($ob);

        $query = "INSERT INTO core_group_has_person (id,oid,person_id,group_id,modified_by,created_by,assigned,create_date,valid_from,valid_to,erased,last_update) VALUES ( null,$ob->oid,$ob->person_id,$ob->group_id,$ob->modified_by,$ob->created_by,$ob->assigned,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        $object->id = mysqli_insert_id($db_conn);

        return true;
    }

    public static function Delete($object) {
        global $db_conn;
        $query = "DELETE FROM core_group_has_person WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Erase($object) {
        global $db_conn;
        $query = "UPDATE core_group_has_person SET erased=1 WHERE id=" . $object->id . " AND erased=0";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Update($object) {
        global $db_conn;
        GroupHasPersonFactoryBase::cleanData($object);
        GroupHasPersonFactory::CheckData($object);
        $query = "UPDATE core_group_has_person SET oid =$object->oid,person_id = $object->person_id,group_id = $object->group_id,modified_by = $object->modified_by,created_by = $object->created_by,assigned = $object->assigned,create_date = $object->create_date,valid_from = $object->valid_from,valid_to = $object->valid_to,erased = $object->erased,last_update=now() WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    /** @return GroupHasPerson */
    public static function Get($id) {
        global $db_conn;
        $query = "SELECT * FROM core_group_has_person WHERE id=" . $id;
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

    /** @return GroupHasPerson[] */
    public static function GetList($where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT * FROM core_group_has_person $where_";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res, 'GroupHasPerson')) {
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
        if ($object->person_id === null || $object->person_id === '') {
            $object->person_id = 'null';
        }
        if ($object->group_id === null || $object->group_id === '') {
            $object->group_id = 'null';
        }
        if ($object->modified_by === null || $object->modified_by === '') {
            $object->modified_by = 'null';
        }
        if ($object->created_by === null || $object->created_by === '') {
            $object->created_by = 'null';
        }
        if ($object->assigned === null || $object->assigned === '') {
            $object->assigned = 'null';
        }
        if (empty($object->create_date)) {
            $object->create_date = 'null';
        } else {
            $object->create_date = "FROM_UNIXTIME('" . DateLogic::fromUser($object->create_date) . "')";
        }
        if (empty($object->valid_from)) {
            $object->valid_from = 'null';
        } else {
            $object->valid_from = "FROM_UNIXTIME('" . DateLogic::fromUser($object->valid_from) . "')";
        }
        if (empty($object->valid_to)) {
            $object->valid_to = 'null';
        } else {
            $object->valid_to = "FROM_UNIXTIME('" . DateLogic::fromUser($object->valid_to) . "')";
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
        $query = "SELECT max(id) as max_id FROM core_group_has_person";
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

    public static function GetListFilter($column, $code, $where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT distinct " . $column . " FROM core_group_has_person $where_ and UPPER(" . $column . ") like UPPER('%$code%') collate utf8_bin; ";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res)) {
            $object_list[] = $row;
        }
        return $object_list;
    }

    public static function GetForeignKeyParams($column) {
        global $db_conn;
        $object_list = array();
        $query = "SELECT distinct REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'core_group_has_person' and COLUMN_NAME = '" . $column . "' limit 1; ";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res)) {
            $object_list[] = $row;
        }
        return $object_list;
    }

    public static function GetListFK($table, $column, $code, $where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT distinct " . $column . " FROM $table $where_ and UPPER(" . $column . ") like UPPER('%$code%') collate utf8_bin; ";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res)) {
            $object_list[] = $row;
        }
        return $object_list;
    }

    public static function GetQuery($group_id) {
        return "select distinct person_id from core_group_has_person where group_id = $group_id";
    }

    public function __construct($handle) {
        
    }

}

?>
