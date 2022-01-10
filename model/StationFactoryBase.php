<?php
/**
 * Class for StationFactory
 * 
 * @author: N3 S.r.l.
 */
class StationFactoryBase
{
	public static function Save( $object )
	{
		global $db_conn;
		$ob = clone $object;
		StationFactoryBase::cleanData( $ob );
		StationFactory::CheckData( $ob );
		$query = "INSERT INTO inaf_station (`id`,`camera_id`,`station_name`,`observer`,`camera_name`,`focal`,`aperture`,`logitude`,`latitude`,`elevation`,`acq_day_gain`,`acq_night_gain`,`acq_regular_cfg`,`vpn_config_file`,`acq_mask_file`,`camera_model`,`station_code`,`prometeus_server_ip`) VALUES ( $ob->id,$ob->camera_id,$ob->station_name,$ob->observer,$ob->camera_name,$ob->focal,$ob->aperture,$ob->logitude,$ob->latitude,$ob->elevation,$ob->acq_day_gain,$ob->acq_night_gain,$ob->acq_regular_cfg,$ob->vpn_config_file,$ob->acq_mask_file,$ob->camera_model,$ob->station_code,$ob->prometeus_server_ip,)";
		$res = mysqli_query($db_conn,$query);
		if ($res === false)
			return false;
		$object->id = mysqli_insert_id($db_conn);

		return true;
	}

	public static function Delete( $object )
	{
		global $db_conn;
		$query = "DELETE FROM inaf_station WHERE id=" . $object->id;
		$res = mysqli_query($db_conn, $query);
		if ($res===false) return false; else return true;
	}

	public static function Update( $object )
	{
		global $db_conn;
		StationFactoryBase::cleanData( $object );
		StationFactory::CheckData( $object );
		$query = "UPDATE inaf_station SET `id` = $object->id,`camera_id` = $object->camera_id,`station_name` =$object->station_name,`observer` =$object->observer,`camera_name` =$object->camera_name,`focal` = $object->focal,`aperture` = $object->aperture,`logitude` = $object->logitude,`latitude` = $object->latitude,`elevation` = $object->elevation,`acq_day_gain` = $object->acq_day_gain,`acq_night_gain` = $object->acq_night_gain,`acq_regular_cfg` =$object->acq_regular_cfg,`vpn_config_file` =$object->vpn_config_file,`acq_mask_file` =$object->acq_mask_file,`camera_model` =$object->camera_model,`station_code` =$object->station_code,`prometeus_server_ip` =$object->prometeus_server_ip,WHERE id=" . $object->id;
		$res = mysqli_query($db_conn,$query);
		if ($res === false) return false; else return true;
	}

	/** @return Station */ 
	public static function Get( $id )
	{
		global $db_conn;
		$query = "SELECT * FROM inaf_station WHERE id=" . $id;
		$res = @mysqli_query($db_conn,$query);
		if ($res === false || mysqli_num_rows($res) <= 0)
			return false;
		$object = mysqli_fetch_object($res,'Station');
                
                
		
		return self::LoadField($object);
	}

	/** @return Station[] */ 
	public static function GetList($where = '')
	{
		global $db_conn;
		$object_list   =   array();
		$where_ = '';
		if ($where != '') {
			$where_ .= ' WHERE ' . $where . '";';
		}
		$query = "SELECT * FROM inaf_station $where_";
		$res = mysqli_query( $db_conn, $query);
		if (!$res  || mysqli_num_rows($res) <= 0)
			return array();
		while ($row = mysqli_fetch_object($res,'Station')) {
			$object_list[] = self::LoadField($row);
		}
		return $object_list;
	}

	public static function CheckData($object, $clean = true){
	}

	public static function cleanData($object){
		$object->id = $object->id ;
		if($object->camera_id === null || $object->camera_id === ''){
			$object->camera_id = 'null';
		}
		if($object->station_name === null || $object->station_name === ''){
			$object->station_name = 'null';
		} else {
			$object->station_name = "'$object->station_name'" ;
		}
		if($object->observer === null || $object->observer === ''){
			$object->observer = 'null';
		} else {
			$object->observer = "'$object->observer'" ;
		}
		if($object->camera_name === null || $object->camera_name === ''){
			$object->camera_name = 'null';
		} else {
			$object->camera_name = "'$object->camera_name'" ;
		}
		if($object->focal === null || $object->focal === ''){
			$object->focal = 'null';
		}
		if($object->aperture === null || $object->aperture === ''){
			$object->aperture = 'null';
		}
		if($object->logitude === null || $object->logitude === ''){
			$object->logitude = 'null';
		}
		if($object->latitude === null || $object->latitude === ''){
			$object->latitude = 'null';
		}
		if($object->elevation === null || $object->elevation === ''){
			$object->elevation = 'null';
		}
		if($object->acq_day_gain === null || $object->acq_day_gain === ''){
			$object->acq_day_gain = 'null';
		}
		if($object->acq_night_gain === null || $object->acq_night_gain === ''){
			$object->acq_night_gain = 'null';
		}
		if($object->acq_regular_cfg === null || $object->acq_regular_cfg === ''){
			$object->acq_regular_cfg = 'null';
		} else {
			$object->acq_regular_cfg = "'$object->acq_regular_cfg'" ;
		}
		if($object->vpn_config_file === null || $object->vpn_config_file === ''){
			$object->vpn_config_file = 'null';
		} else {
			$object->vpn_config_file = "'$object->vpn_config_file'" ;
		}
		if($object->acq_mask_file === null || $object->acq_mask_file === ''){
			$object->acq_mask_file = 'null';
		} else {
			$object->acq_mask_file = "'$object->acq_mask_file'" ;
		}
		if($object->camera_model === null || $object->camera_model === ''){
			$object->camera_model = 'null';
		} else {
			$object->camera_model = "'$object->camera_model'" ;
		}
		if($object->station_code === null || $object->station_code === ''){
			$object->station_code = 'null';
		} else {
			$object->station_code = "'$object->station_code'" ;
		}
		if($object->prometeus_server_ip === null || $object->prometeus_server_ip === ''){
			$object->prometeus_server_ip = 'null';
		} else {
			$object->prometeus_server_ip = "'$object->prometeus_server_ip'" ;
		}
	}

	public static function LoadField($object){
		return $object;
	}

	public static function getMaxId() {
		global $db_conn;
		$query = "SELECT max(id) as max_id FROM inaf_station";
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
			$query = "SELECT distinct ".$column." FROM inaf_station $where_ and ".$column." like '%$code%'; ";
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
			$query = "SELECT distinct REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'inaf_station' and COLUMN_NAME = '".$column."' limit 1; ";
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
