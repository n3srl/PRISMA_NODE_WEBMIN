<?php
/**
 * Class for FreetureFactory
 * 
 * @author: N3 S.r.l.
 */
class FreetureFactoryBase
{
	public static function Save( $object )
	{
		global $db_conn;
		$ob = clone $object;
		FreetureFactoryBase::cleanData( $ob );
		FreetureFactory::CheckData( $ob );
		$query = "INSERT INTO inaf_freeture (`id`,`key`,`value`,`description`,`show`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`) VALUES ( $ob->id,$ob->key,$ob->value,$ob->description,$ob->show,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
		$res = mysqli_query($db_conn,$query);
		if ($res === false)
			return false;
		$object->id = mysqli_insert_id($db_conn);

		return true;
	}

	public static function Delete( $object )
	{
		global $db_conn;
		$query = "DELETE FROM inaf_freeture WHERE id=" . $object->id;
		$res = mysqli_query($db_conn, $query);
		if ($res===false) return false; else return true;
	}

	public static function Erase($object)
	{
		global $db_conn;
		$query = "UPDATE inaf_freeture SET erased=1 WHERE id=" . $object->id . " AND erased=0";
		$res = mysqli_query($db_conn,$query);
		if ($res === false) return false; else return true;
	}

	public static function Update( $object )
	{
		global $db_conn;
		FreetureFactoryBase::cleanData( $object );
		FreetureFactory::CheckData( $object );
		$query = "UPDATE inaf_freeture SET `id` = $object->id,`key` =$object->key,`value` =$object->value,`description` =$object->description,`show` = $object->show,`valid_from` = $object->valid_from,`valid_to` = $object->valid_to,`erased` = $object->erased,`last_update`=now() WHERE id=" . $object->id;
		$res = mysqli_query($db_conn,$query);
		if ($res === false) return false; else return true;
	}

	/** @return Freeture */ 
	public static function Get( $id )
	{
		global $db_conn;
		$query = "SELECT * FROM inaf_freeture WHERE id=" . $id;
		$res = @mysqli_query($db_conn,$query);
		if ($res === false || mysqli_num_rows($res) <= 0)
			return false;
		$object = mysqli_fetch_object($res,'Freeture');
		
		return self::LoadField($object);
	}

	/** @return Freeture[] */ 
	public static function GetList($where = '')
	{
		global $db_conn;
		$object_list   =   array();
		$where_ = "WHERE erased = 0";
		if ($where != '') {
			$where_ .= ' AND ' . $where;
		}
		$query = "SELECT * FROM inaf_freeture $where_";
		$res = mysqli_query( $db_conn, $query);
		if (!$res  || mysqli_num_rows($res) <= 0)
			return array();
		while ($row = mysqli_fetch_object($res,'Freeture')) {
			$object_list[] = self::LoadField($row);
		}
		return $object_list;
	}

	public static function CheckData($object, $clean = true){
	}

	public static function cleanData($object){
		$object->id = $object->id ;
		if($object->key === null || $object->key === ''){
			$object->key = 'null';
		} else {
			$object->key = "'$object->key'" ;
		}
		if($object->value === null || $object->value === ''){
			$object->value = 'null';
		} else {
			$object->value = "'$object->value'" ;
		}
		if($object->description === null || $object->description === ''){
			$object->description = 'null';
		} else {
			$object->description = "'$object->description'" ;
		}
		if($object->show === null || $object->show === ''){
			$object->show = 'null';
		}
		if ($object-> show == '1' ){
			$object->show = 'true';
		} else {
			$object->show = 'false';
		}
		if(empty($object->create_date)){
			$object->create_date = 'null';
		}else{
			$object->create_date = "'".DateLogic::fromUser($object->create_date)."'";
		}
		if(empty($object->valid_from)){
			$object->valid_from = 'null';
		}else{
			$object->valid_from = "'".DateLogic::fromUser($object->valid_from)."'";
		}
		if(empty($object->valid_to)){
			$object->valid_to = 'null';
		}else{
			$object->valid_to = "'".DateLogic::fromUser($object->valid_to)."'";
		}
		if($object->erased === null || $object->erased === ''){
			$object->erased = 'null';
		}
		if ($object-> erased == '1' ){
			$object->erased = 'true';
		} else {
			$object->erased = 'false';
		}
	}

	public static function LoadField($object){
		$object->create_date = DateLogic::fromUser($object->create_date);		$object->valid_from = DateLogic::fromUser($object->valid_from);		$object->valid_to = DateLogic::fromUser($object->valid_to);		$object->last_update = DateLogic::fromUser($object->last_update);		return $object;
	}

	public static function getMaxId() {
		global $db_conn;
		$query = "SELECT max(id) as max_id FROM inaf_freeture";
		$res = mysqli_query( $db_conn, $query);
		if ($res === false  || mysqli_num_rows($res) <= 0)
			return 1;
		$object = mysqli_fetch_object($res);
		$id = $object->max_id;
		if ($id == null){
			return 1;
		}
		return $id;
	}

		public static function GetListFilter($column,$code,$where = '') {
			global $db_conn;
			$object_list = array();
			$where_ = "WHERE erased = 0";
			if ($where != '') {
				$where_ .= ' AND ' . $where;
			}
			$query = "SELECT distinct ".$column." FROM inaf_freeture $where_ and ".$column." like '%$code%'; ";
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
			$query = "SELECT distinct REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'inaf_freeture' and COLUMN_NAME = '".$column."' limit 1; ";
			$res = mysqli_query($db_conn, $query);
			if (!$res || mysqli_num_rows($res) <= 0)
				return array();
			while ($row = mysqli_fetch_object($res)) {
				$object_list[] = $row;
			}
			return $object_list;
		}

		public static function GetListFK($table,$column,$code,$where = '') {
			global $db_conn;
			$object_list = array();
			$where_ = "WHERE erased = 0";
			if ($where != '') {
				$where_ .= ' AND ' . $where;
			}
			$select ="null as placeholder";
			switch(gettype($column)){
				case "array":
					$where_.= " AND(1 = 0 ";
					foreach($column as $col){
						$select.=",$col";
						$colArr = explode(strtoupper(" as "), strtoupper($col));
						$where_.= " OR UPPER(" . $colArr[0] . ") like UPPER('%$code%') collate utf8_bin ";
					}
					$where_.= ")";
					break;
				default:
					$select = $column;
					$where_.= " AND UPPER(" . $column . ") like UPPER('%$code%') collate utf8_bin";
			}
			$query = "SELECT distinct " . $select . " FROM $table $where_  ";
			$res = mysqli_query($db_conn, $query);
			if (!$res || mysqli_num_rows($res) <= 0)
				return array();
			while ($row = mysqli_fetch_object($res)) {
				$object_list[] = $row;
			}
			return $object_list;
		}

	public function __construct($handle){
	}
}
?>
