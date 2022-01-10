<?php

/**
 * Class for PersonFactory
 * 
 * @author: N3 S.r.l.
 */
class PersonFactoryBase {

    public static function Save($object) {
        global $db_conn;
        $ob = clone $object;
        PersonFactoryBase::cleanData($ob);
        PersonFactory::CheckData($ob);
        $query = "INSERT INTO core_person (id,oid,username,password,title,first_name,middle_name,last_name,suffix,company,job_title,email,web_page_address,im_address,phone,address,postcode,number,city,province,country,timezone,modified_by,created_by,assigned,create_date,valid_from,valid_to,erased,last_update) VALUES ( null,$ob->oid,$ob->username,$ob->password,$ob->title,$ob->first_name,$ob->middle_name,$ob->last_name,$ob->suffix,$ob->company,$ob->job_title,$ob->email,$ob->web_page_address,$ob->im_address,$ob->phone,$ob->address,$ob->postcode,$ob->number,$ob->city,$ob->province,$ob->country,$ob->timezone,$ob->modified_by,$ob->created_by,$ob->assigned,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        $object->id = mysqli_insert_id($db_conn);

        return true;
    }

    public static function Delete($object) {
        global $db_conn;
        $query = "DELETE FROM core_person WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Erase($object) {
        global $db_conn;
        $query = "UPDATE core_person SET erased=1 WHERE id=" . $object->id . " AND erased=0";
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    public static function Update($object) {
        global $db_conn;
        PersonFactoryBase::cleanData($object);
        PersonFactory::CheckData($object);
        $query = "UPDATE core_person SET oid =$object->oid,username =$object->username,password =$object->password,title =$object->title,first_name =$object->first_name,middle_name =$object->middle_name,last_name =$object->last_name,suffix =$object->suffix,company =$object->company,job_title =$object->job_title,email =$object->email,web_page_address =$object->web_page_address,im_address =$object->im_address,phone =$object->phone,address =$object->address,postcode =$object->postcode,number =$object->number,city =$object->city,province =$object->province,country =$object->country,timezone =$object->timezone,modified_by = $object->modified_by,created_by = $object->created_by,assigned = $object->assigned,create_date = $object->create_date,valid_from = $object->valid_from,valid_to = $object->valid_to,erased = $object->erased,last_update=now() WHERE id=" . $object->id;
        $res = mysqli_query($db_conn, $query);
        if ($res === false)
            return false;
        else
            return true;
    }

    /** @return Person */
    public static function Get($id) {
        global $db_conn;
        $query = "SELECT * FROM core_person WHERE id=" . $id;
        $res = @mysqli_query($db_conn, $query);
        if ($res === false || mysqli_num_rows($res) <= 0)
            return false;
        $object = mysqli_fetch_object($res);

        return self::LoadField($object);
    }

    /** @return Person[] */
    public static function GetList($where = '') {
        global $db_conn;
        $object_list = array();
        $where_ = "WHERE erased = 0";
        if ($where != '') {
            $where_ .= ' AND ' . $where;
        }
        $query = "SELECT * FROM core_person $where_";
        $res = mysqli_query($db_conn, $query);
        if (!$res || mysqli_num_rows($res) <= 0)
            return array();
        while ($row = mysqli_fetch_object($res, 'Person')) {
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
        if ($object->username === null || $object->username === '') {
            $object->username = 'null';
        } else {
            $object->username = "'$object->username'";
        }
        if ($object->password === null || $object->password === '') {
            $object->password = 'null';
        } else {
            $object->password = "'$object->password'";
        }
        if ($object->title === null || $object->title === '') {
            $object->title = 'null';
        } else {
            $object->title = "'$object->title'";
        }
        if ($object->first_name === null || $object->first_name === '') {
            $object->first_name = 'null';
        } else {
            $object->first_name = "'$object->first_name'";
        }
        if ($object->middle_name === null || $object->middle_name === '') {
            $object->middle_name = 'null';
        } else {
            $object->middle_name = "'$object->middle_name'";
        }
        if ($object->last_name === null || $object->last_name === '') {
            $object->last_name = 'null';
        } else {
            $object->last_name = "'$object->last_name'";
        }
        if ($object->suffix === null || $object->suffix === '') {
            $object->suffix = 'null';
        } else {
            $object->suffix = "'$object->suffix'";
        }
        if ($object->company === null || $object->company === '') {
            $object->company = 'null';
        } else {
            $object->company = "'$object->company'";
        }
        if ($object->job_title === null || $object->job_title === '') {
            $object->job_title = 'null';
        } else {
            $object->job_title = "'$object->job_title'";
        }
        if ($object->email === null || $object->email === '') {
            $object->email = 'null';
        } else {
            $object->email = "'$object->email'";
        }
        if ($object->web_page_address === null || $object->web_page_address === '') {
            $object->web_page_address = 'null';
        } else {
            $object->web_page_address = "'$object->web_page_address'";
        }
        if ($object->im_address === null || $object->im_address === '') {
            $object->im_address = 'null';
        } else {
            $object->im_address = "'$object->im_address'";
        }
        if ($object->phone === null || $object->phone === '') {
            $object->phone = 'null';
        } else {
            $object->phone = "'$object->phone'";
        }
        if ($object->address === null || $object->address === '') {
            $object->address = 'null';
        } else {
            $object->address = "'$object->address'";
        }
        if ($object->postcode === null || $object->postcode === '') {
            $object->postcode = 'null';
        } else {
            $object->postcode = "'$object->postcode'";
        }
        if ($object->number === null || $object->number === '') {
            $object->number = 'null';
        } else {
            $object->number = "'$object->number'";
        }
        if ($object->city === null || $object->city === '') {
            $object->city = 'null';
        } else {
            $object->city = "'$object->city'";
        }
        if ($object->province === null || $object->province === '') {
            $object->province = 'null';
        } else {
            $object->province = "'$object->province'";
        }
        if ($object->country === null || $object->country === '') {
            $object->country = 'null';
        } else {
            $object->country = "'$object->country'";
        }
        $object->timezone = "'$object->timezone'";
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
        $query = "SELECT max(id) as max_id FROM core_person";
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
        $query = "SELECT distinct " . $column . " FROM core_person $where_ and UPPER(" . $column . ") like UPPER('%$code%') collate utf8_bin; ";
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
        $query = "SELECT distinct REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'core_person' and COLUMN_NAME = '" . $column . "' limit 1; ";
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

    public function __construct($handle) {
        
    }

}

?>
