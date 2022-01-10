<?php
/**
 * Class for DockerFactory
 * 
 * @author: N3 S.r.l.
 */
class DockerFactoryBase
{
	public static function Save( $object )
	{
		global $db_conn;
		$ob = clone $object;
		DockerFactoryBase::cleanData( $ob );
		DockerFactory::CheckData( $ob );
		$query = "INSERT INTO inaf_docker (`id`,`name`,`image`,`command`,`status`,`created`,`create_date`,`valid_from`,`valid_to`,`erased`,`last_update`) VALUES ( $ob->id,$ob->name,$ob->image,$ob->command,$ob->status,$ob->created,$ob->create_date,$ob->valid_from,$ob->valid_to,0,now())";
		$res = mysqli_query($db_conn,$query);
		if ($res === false)
			return false;
		$object->id = mysqli_insert_id($db_conn);

		return true;
	}

	public static function Delete( $object )
	{
		global $db_conn;
		$query = "DELETE FROM inaf_docker WHERE id=" . $object->id;
		$res = mysqli_query($db_conn, $query);
		if ($res===false) return false; else return true;
	}

	public static function Erase($object)
	{
		global $db_conn;
		$query = "UPDATE inaf_docker SET erased=1 WHERE id=" . $object->id . " AND erased=0";
		$res = mysqli_query($db_conn,$query);
		if ($res === false) return false; else return true;
	}

	public static function Update( $object )
	{
		global $db_conn;
		DockerFactoryBase::cleanData( $object );
		DockerFactory::CheckData( $object );
		$query = "UPDATE inaf_docker SET `id` = $object->id,`name` =$object->name,`image` =$object->image,`command` =$object->command,`status` =$object->status,`created` =$object->created,`valid_from` = $object->valid_from,`valid_to` = $object->valid_to,`erased` = $object->erased,`last_update`=now() WHERE id=" . $object->id;
		$res = mysqli_query($db_conn,$query);
		if ($res === false) return false; else return true;
	}

	/** @return Docker */ 
	public static function Get( $id )
	{
		global $db_conn;
		$query = "SELECT * FROM inaf_docker WHERE id=" . $id;
		$res = @mysqli_query($db_conn,$query);
		if ($res === false || mysqli_num_rows($res) <= 0)
			return false;
		$object = mysqli_fetch_object($res,'Docker');
		
		return self::LoadField($object);
	}

	/** @return Docker[] */ 
	public static function GetList($where = '')
	{
		global $db_conn;
		$object_list   =   array();
		$where_ = "WHERE erased = 0";
		if ($where != '') {
			$where_ .= ' AND ' . $where;
		}
		$query = "SELECT * FROM inaf_docker $where_";
		$res = mysqli_query( $db_conn, $query);
		if (!$res  || mysqli_num_rows($res) <= 0)
			return array();
		while ($row = mysqli_fetch_object($res,'Docker')) {
			$object_list[] = self::LoadField($row);
		}
		return $object_list;
	}

	public static function CheckData($object, $clean = true){
	}

	public static function cleanData($object){
		$object->id = $object->id ;
		if($object->name === null || $object->name === ''){
			$object->name = 'null';
		} else {
			$object->name = "'$object->name'" ;
		}
		if($object->image === null || $object->image === ''){
			$object->image = 'null';
		} else {
			$object->image = "'$object->image'" ;
		}
		if($object->command === null || $object->command === ''){
			$object->command = 'null';
		} else {
			$object->command = "'$object->command'" ;
		}
		if($object->status === null || $object->status === ''){
			$object->status = 'null';
		} else {
			$object->status = "'$object->status'" ;
		}
		if($object->created === null || $object->created === ''){
			$object->created = 'null';
		} else {
			$object->created = "'$object->created'" ;
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
		$query = "SELECT max(id) as max_id FROM inaf_docker";
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
			$query = "SELECT distinct ".$column." FROM inaf_docker $where_ and ".$column." like '%$code%'; ";
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
			$query = "SELECT distinct REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'inaf_docker' and COLUMN_NAME = '".$column."' limit 1; ";
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
