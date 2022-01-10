<?php
/**
*
* @author: N3 S.r.l.
*/

class FreetureApiLogic
{
	public static function Save($request) {
		try {

			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$ob = new Freeture();

			CoreLogic::getFromArray($ob, $request->get("data"));
			CoreLogic::beginTransaction();
			$res = FreetureLogic::Save($ob);
			if(!$res)
				throw new ApiException(ApiException::$Generic);
			CoreLogic::commitTransaction();
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function Update($request){

		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$ob = new Freeture();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;
                        $ob->show = $tmp["show"] ;

			$res = self::updateCfg($ob);
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function Erase($request) {
		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$ob = new Freeture();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;

			$ob = FreetureLogic::Get($ob->id);

			CoreLogic::beginTransaction();
			$res = FreetureLogic::Erase($ob);
			CoreLogic::commitTransaction();
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function Delete($request) {
		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$ob = new Freeture();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;

			$ob = FreetureLogic::Get($ob->id);

			CoreLogic::beginTransaction();
			$res = FreetureLogic::Delete($ob);
			CoreLogic::commitTransaction();
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function Get($id) {
		try {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			$ob = self::getCfg($id);
			$res = true;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function GetList() {
		try {
			$Person = CoreLogic::VerifyPerson();
			$ob = FreetureLogic::GetList();
			$res = true;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function GetListFilterAjax($columnName) {
		try {
			$Person = CoreLogic::VerifyPerson();
			$results = array();
			$data = new stdClass();
		$codes = FreetureFactory::GetListFilter($columnName,$_GET['term']);
			foreach ($codes as $code){ 
				$obj = new stdClass(); 
				$obj->id = $code->{$columnName}; 
				$obj->text = $code->{$columnName}; 
				$results[] = $obj; 
			}
			$data->results = $results;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
			return $data;
	}

	public static function GetListFKAjax($columnName) {
		try {
			$Person = CoreLogic::VerifyPerson();
			$results = array();
			$data = new stdClass();
			switch ($columnName) {
				/* ** ESEMPIO **
				case "created_by":
					$foreignKey = end(FreetureFactory::GetForeignKeyParams($columnName));
					$codes = FreetureFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,array("id","CONCAT(last_name, ' ', first_name) AS full_name"),$_GET['term']);
					$data = new stdClass();
					foreach ($codes as $code){ 
						$obj = new stdClass(); 
						$obj->id = $code->id; 
						$obj->text = $code->full_name; 
						$results[] = $obj; 
					}
				break;
				*/
				default:
					$foreignKey = end(FreetureFactory::GetForeignKeyParams($columnName));
					$codes = FreetureFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,$foreignKey->REFERENCED_COLUMN_NAME,$_GET['term']);
					$data = new stdClass();
					foreach ($codes as $code){ 
						$obj = new stdClass(); 
						$obj->id = $code->{$foreignKey->REFERENCED_COLUMN_NAME}; 
						$obj->text = $code->{$foreignKey->REFERENCED_COLUMN_NAME}; 
						$results[] = $obj; 
					}
				}
			$data->results = $results;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
			return $data;
	}

	public static function GetListDatatable() {
        global $db_conn;
        try {
            $Person = CoreLogic::VerifyPerson();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        $aColumns = array('`key`', '`value`', '`description`', '`show`', '`id`');
        $aColumnsName = array('key', 'value', 'description', 'show', 'id');
        $sIndexColumn = 'id';
        $sTable = 'inaf_freeture';
        $gaSql['link'] = $db_conn;
        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP server-side, there is
         * no need to edit below this line
         */
        /* Local functions */

        function fatal_error($sErrorMessage = '') {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
            die($sErrorMessage);
        }

        /* Ordering */
        $sOrder = '';
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = 'ORDER BY  ';
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == 'true') {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . " 
						 " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, '', -2);
            if ($sOrder == 'ORDER BY') {
                $sOrder = '';
            }
        }
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = ' WHERE erased = 0 ';
        foreach ($aColumnsName as $index => $col) {
            if (isset($_GET[$col]) && !empty($_GET[$col])) {
                $sWhere .= ' AND(';
                if (is_array($_GET[$col])) {
                    foreach ($_GET[$col] as $c) {
                        $filter = mysqli_escape_string($db_conn, $c);
                        $sWhere .= "  $aColumns[$index] = '$filter' OR ";
                    }
                    $sWhere = substr_replace($sWhere, '', -3);
                    $sWhere .= ') ';
                } else {
                    $filter = mysqli_escape_string($db_conn, $_GET[$col]);
                    $sWhere .= "  $aColumns[$index] = '$filter' OR ";
                    $sWhere = substr_replace($sWhere, '', -3);
                    $sWhere .= ') ';
                }
            }
        }
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != '') {
            $sWhere .= ' AND(';
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == 'true') {
;
                    $sWhere .= "UPPER(" . $aColumns[$i] . ") like UPPER('%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%') collate utf8_bin OR ";
                }
            }
            $sWhere = substr_replace($sWhere, '', -3);
            $sWhere .= ')';
        }
        /* Individual column filtering */
        for ($i = 0; $i < count($aColumnsName); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == 'true' && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == '') {
                    $sWhere = 'WHERE ';
                } else {
                    $sWhere .= ' AND ';
                }
                $sWhere .= "UPPER(" . $aColumns[$i] . ") LIKE UPPER('%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%') collate utf8_bin ";
            }
        }
        /* DATATABLE show page by id */
        $sLimit = '';
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }
        if (isset($_GET['searchPageById']) && !empty($_GET['searchPageById']) && $_GET['searchPageById'] != -1) {
            if ($_GET['iDisplayLength'] != '-1') {
                $iDisplayLength = intval($_GET['iDisplayLength']);
                $bsearchPageById = mysqli_escape_string($gaSql['link'], $_GET['searchPageById']);
                $sQuery = "select FLOOR(row_number / $iDisplayLength) * $iDisplayLength from("
                        . "SELECT @row_number:= @row_number + 1 AS row_number, $sIndexColumn FROM $sTable, (SELECT @row_number:= 0) AS t "
                        . "$sWhere"
                        . "$sOrder"
                        . ") as a where a.$sIndexColumn =  $bsearchPageById";
                $rResultTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
                $aResultTotal = mysqli_fetch_array($rResultTotal);
                $iDisplayStart = $aResultTotal[0];
                if (empty($iDisplayStart)) {
                    $iDisplayStart = 0;
                }
                if ($iDisplayStart <= $iDisplayLength) {
                    $pageNumber = 0;
                } else {
                    $pageNumber = ($iDisplayStart / $iDisplayLength);
                }
            }
        } else {
            $pageNumber = null;
        }
        /*
         * SQL queries
         * Get data to display
         */
        mysqli_query($gaSql['link'], 'SET CHARACTER SET utf8') or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
        $rResult = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
        /* Data set length after filtering */
        $sQuery = "
		SELECT FOUND_ROWS()";
        $rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
        $aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
        $iFilteredTotal = $aResultFilterTotal[0];
        /* Total data set length */
        $sQuery = "
		SELECT COUNT(" . $sIndexColumn . ")
		FROM   $sTable
		";
        $rResultTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
        $aResultTotal = mysqli_fetch_array($rResultTotal);
        $iTotal = $aResultTotal[0];
        /*
         * Output
         */
        
        $reply = self::parseCfg();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => 0,
            "iTotalRecords" => count($reply),
            "iTotalDisplayRecords" => count($reply),
            "aaData" => $reply
        );

        
        return $output;
    }

    public static function trim(String $raw) {
        return str_replace(array(" ", "\n", "\r"), "", $raw);
    }

    public static function getValue(String $raw) {
        $value1 = explode("=", $raw)[1];
        return self::trim(self::cleanComments($value1));
    }

    public static function getKey(String $raw) {
        $key1 = explode("=", $raw)[0];
        return self::trim($key1);
    }
    
    public static function isVisible(String $raw) {
        return strpos($raw, "#nv") === false;
    }

    public static function parseCfg() {
        $freetureConf = "/Users/lorenzobottini/Desktop/configuration.cfg";
        $list = array();
        $i = 0;
        $descr = "no description";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" 
                        && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    
                    //Add parameter to the list
                    if(self::isVisible($line)){
                        $list[] = array(self::getKey($line), self::getValue($line), $descr, 1, 0,  $i);
                    }else{
                        $list[] = array(self::getKey($line), self::getValue($line), $descr, 0, 0,  $i);
                    }
                    $i++;
                }else{
                    if($line[0] === "#"){ //Comments contains the description
                        $descr = $line;
                    }
                }
            }
        }

        return $list;
    }
    
    public static function getCfg($id) {
        $freetureConf = "/Users/lorenzobottini/Desktop/configuration.cfg";
        $i = 0;
        $descr = "no description";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" 
                        && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    
                    //Return requested data
                    if("$i"===$id){
                        $ft = new Freeture();
                        $ft->id = $id;
                        $ft->key = self::getKey($line);
                        $ft->value = self::getValue($line);
                        $ft->description = $descr;
                        $ft->show = 1;
                        return $ft;
                    }
                    $i++;
                    
                }else{
                    if($line[0] === "#"){ //Comments contains the description
                        $descr = $line;
                    }
                }
            }
        }
        return false;
    }
    
    public static function cleanComments(String $raw) {
        if(!strpos($raw, "#") === false){
            return substr($raw, 0, strpos($raw, "#"))."\n";
        }else{
            return $raw;
        }
    }
    
    public static function setVisible(String $raw) {
        if(!strpos($raw, "#nv") === false){
            return substr($raw, 0, strpos($raw, "#"))."\n";
        }
    }
    
    public static function setInvisible(String $raw) {
        return str_replace("\n", " #nv\n", $raw);
    }
    
    public static function updateCfg($ob) {
        $freetureConf = "/Users/lorenzobottini/Desktop/configuration.cfg";
        $reply = "";
        $i = 0;
        $descr = "no description";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            
            $contents = file($freetureConf);
            
            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" 
                        && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    
                    //In the requested param set visibility
                    if("$i"===$ob->id){
                        if($ob->show==="0"){
                            $line = self::setInvisible($line);
                        }else{
                            $line = self::setVisible($line);
                        }
                    }
                    
                    $reply .= $line;
                    $i++;
                    
                }else{
                    if($line[0] === "#"){ //Comments contains the description
                        $reply .= $line;
                    }
                }
            }
            $myfile = fopen($freetureConf, "w"); 
            fwrite($myfile, $reply);
            fclose($myfile);
        }
        return true;
    }


}


/*
while ($aRow = mysqli_fetch_array($rResult)) {
			$row = array();
			$id = $aRow['id'];
			for ($i = 0; $i < count($aColumnsName); $i++) {
				if ($aColumnsName[$i] == 'id') {
					$row[] = $id;
				} else if ($aColumnsName[$i] != ' ') {
					$row[] = stripslashes($aRow[$aColumnsName[$i]]);
				}
			}
			$output['aaData'][] = $row;
		}
 *  */