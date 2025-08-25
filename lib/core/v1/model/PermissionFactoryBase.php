<?php

/**
 * Class for PermissionFactory
 * 
 * @author: N3 S.r.l.
 */
class PermissionFactoryBase {

    public static function Save($object) {
        global $db_conn;
        $ob = clone $object;
        PermissionFactoryBase::cleanData($ob);
        PermissionFactory::CheckData($ob);
        $query = "INSERT INTO core_permission (id,oid,ext_oid,person_id,group_id,execute,read,write,active,username,secret_token,modified_by,created_by,assigned,create_date,valid_from,valid_to,erased,last_update) VALUES ( null,$ob->oid,$ob->ext_oid,$ob->person_id,$ob->group_id,$ob->execute,$ob->read,$ob->write,$ob->active,$ob->username,$ob->secret_token,$ob->modified_by,$ob->created_by,$ob->assigned,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        $object->id = mysqli_insert_id($db_conn);

        return true;
    }

    public static function Delete($object) {
        global $db_conn;
        $query = "DELETE FROM core_permission WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Erase($object) {
        global $db_conn;
        $query = "UPDATE core_permission SET erased=1 WHERE id=" . $object->id . " AND erased=0";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Update($object) {
        global $db_conn;
        PermissionFactoryBase::cleanData($object);
        PermissionFactory::CheckData($object);
        $query = "UPDATE core_permission SET oid =$object->oid,ext_oid =$object->ext_oid,person_id = $object->person_id,group_id = $object->group_id,execute = $object->execute,read = $object->read,write = $object->write,active = $object->active,username =$object->username,secret_token =$object->secret_token,modified_by = $object->modified_by,created_by = $object->created_by,assigned = $object->assigned,create_date = $object->create_date,valid_from = $object->valid_from,valid_to = $object->valid_to,erased = $object->erased,last_update=now() WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    /** @return Permission */
    public static function Get($id) {
        global $db_conn;
        $query = "SELECT * FROM core_permission WHERE id=" . $id;
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

    /** @return Permission[] */
    public static function GetList($where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT * FROM core_permission $where_";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res, 'Permission')) {
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
        if ($object->ext_oid === null || $object->ext_oid === '') {
            $object->ext_oid = 'null';
        } else {
            $object->ext_oid = "'$object->ext_oid'";
        }
        if ($object->person_id === null || $object->person_id === '') {
            $object->person_id = 'null';
        }
        if ($object->group_id === null || $object->group_id === '') {
            $object->group_id = 'null';
        }
        if ($object->execute === null || $object->execute === '') {
            $object->execute = 'null';
        }
        if ($object->execute == '1') {
            $object->execute = 'true';
        } else {
            $object->execute = 'false';
        }
        if ($object->read === null || $object->read === '') {
            $object->read = 'null';
        }
        if ($object->read == '1') {
            $object->read = 'true';
        } else {
            $object->read = 'false';
        }
        if ($object->write === null || $object->write === '') {
            $object->write = 'null';
        }
        if ($object->write == '1') {
            $object->write = 'true';
        } else {
            $object->write = 'false';
        }
        if ($object->active === null || $object->active === '') {
            $object->active = 'null';
        }
        if ($object->active == '1') {
            $object->active = 'true';
        } else {
            $object->active = 'false';
        }
        if ($object->username === null || $object->username === '') {
            $object->username = 'null';
        } else {
            $object->username = "'$object->username'";
        }
        if ($object->secret_token === null || $object->secret_token === '') {
            $object->secret_token = 'null';
        } else {
            $object->secret_token = "'$object->secret_token'";
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
        $query = "SELECT max(id) as max_id FROM core_permission";
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
