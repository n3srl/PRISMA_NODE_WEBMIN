<?php

/**
 *
 * @author: N3 S.r.l.
 */
class PersonApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Person();

            CoreLogic::getFromArray($ob, $request->get("data"));

            $ob->password = password_hash($ob->new_password, PASSWORD_BCRYPT);
            CoreLogic::beginTransaction();
            $res = PersonLogic::Save($ob);


            // TODO gestione gruppo (abbinabile da frontend o default?)
            // insert in group default
            $group = new GroupHasPerson();
            $group->person_id = $ob->id;
            $group->group_id = $ob->group_id;

            $res &= GroupHasPersonLogic::Save($group);

            CoreLogic::commitTransaction();
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function Update($request) {

        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Person();

            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = PersonLogic::Get($ob->id);
            $ob->new_password = null;
            $old_password = $ob->password;
            CoreLogic::getFromArray($ob, $request->get("data"));

            if (empty($ob->password))
                $ob->password = $old_password;
            if (!empty($ob->new_password)) {
                $ob->password = password_hash($ob->new_password, PASSWORD_BCRYPT);
            }
            CoreLogic::beginTransaction();
            $res = PersonLogic::Update($ob);
            CoreLogic::commitTransaction();
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

            $ob = new Person();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = PersonLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = PersonLogic::Erase($ob);
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

            $ob = new Person();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = PersonLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = PersonLogic::Delete($ob);
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
            $ob = PersonLogic::Get($id);
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = PersonLogic::GetList();
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
            $codes = PersonFactory::GetListFilter($columnName, $_GET['term']);
            foreach ($codes as $code) {
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
            $foreignKey = end(PersonFactory::GetForeignKeyParams($columnName));
            $codes = PersonFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
            $data = new stdClass();
            foreach ($codes as $code) {
                $obj = new stdClass();
                $obj->id = $code->{$foreignKey->REFERENCED_COLUMN_NAME};
                $obj->text = $code->{$foreignKey->REFERENCED_COLUMN_NAME};
                $results[] = $obj;
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
        $aColumns = array('`username`', '`email`', '`title`', '`first_name`', '`middle_name`', '`last_name`', '`id`', '`suffix`', '`company`', '`web_page_address`', '`im_address`', '`phone`', '`address`', '`postcode`', '`number`', '`city`', '`province`', '`country`', '`timezone`', '`oid`', '`job_title`', '`id`');
        $aColumnsName = array('username', 'email', 'title', 'first_name', 'middle_name', 'last_name', 'id', 'suffix', 'company', 'web_page_address', 'im_address', 'phone', 'address', 'postcode', 'number', 'city', 'province', 'country', 'timezone', 'oid', 'job_title', 'id');
        $sIndexColumn = 'id';
        $sTable = 'core_person';
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
                ;
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
                        $sWhere .= "  $aColumns[$index] like '%$filter%' collate utf8_bin OR ";
                    }
                    $sWhere = substr_replace($sWhere, '', -3);
                    $sWhere .= ') ';
                } else {
                    $filter = mysqli_escape_string($db_conn, $_GET[$col]);
                    $sWhere .= "  $aColumns[$index] like '%$filter%' collate utf8_bin OR ";
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
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' collate utf8_bin OR ";
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
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' collate utf8_bin ";
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
                $sQuery = "select FLOOR((row_number -1) / $iDisplayLength) * $iDisplayLength from("
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
                if ($iDisplayStart < $iDisplayLength) {
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
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        while ($aRow = mysqli_fetch_array($rResult)) {
            $row = array();
            $id = $aRow['id'];
            for ($i = 0; $i < count($aColumnsName); $i++) {
                if ($aColumnsName[$i] == 'id') {
                    $row[] = $id;
                } else if ($aColumnsName[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumnsName[$i]];
                }
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }
    
    public static function GetListDatatableFromFile() {
        try {
            $Person = CoreLogic::VerifyPerson();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
             
        $reply = self::parseUsersFile();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => 0,
            "iTotalRecords" => count($reply),
            "iTotalDisplayRecords" => count($reply),
            "aaData" => $reply
        );
        
        return $output;
    
    }
    
    public static function parseUsersFile(){
        
        $file = _PASSWD_;
        
        if (file_exists($file) && is_file($file)) {

            $contents = file($file);

            //Parse config file line by line
            foreach ($contents as $line) {
                
                $array = explode(" ",$line);
                $user[] = array($array[0], $array[1], $array[2], $array[3], $array[4]);
                 
            }

        }
        return $user;
    }
    
    public static function GetFromFile($id) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = self::getUser($id);
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }
    
     public static function UpdatePassword($request) {
         try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $tmp = $request->get("data");

            $new_password = $tmp["new_password"] ;
            $id = $tmp["id"] ;
            
            $ob = new Person();
            $ob->id = $id;
            $ob->password = $new_password;

            $res = self::updateFilePassword($id, $new_password);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }
    
    public static function getUser($id) {
        $file = _PASSWD_;
        
        if (file_exists($file) && is_file($file)) {

            $contents = file($file);

            // Parse users file line by line
            foreach ($contents as $line) {
                
                $array = explode(" ",$line);
                $user = new Person();
                $user->id = $array[0];
                $user->username = $array[1];
                $user->password = $array[2];
                $user->timezone = $array[3];
                $user->erased = "0";
                 
            }

        }
        return $user;
    }
    
    public static function updateFilePassword($id, $new_password) {
        $file = _PASSWD_;
        $reply = "";
        
        $pwd = password_hash($new_password, PASSWORD_BCRYPT);
        
        if (file_exists($file) && is_file($file)) {

            $contents = file($file);

            // Parse password file line by line
            foreach ($contents as $line) {
               
                
                $array = explode(" ",$line);
                $user = new Person();
                $user->id = $array[0];
                $user->username = $array[1];
                $user->password = $array[2];
                $user->timezone = $array[3];
                $user->erased = $array[4];
                
                if ($id === $user->id) {
                        $reply .= $user->id . " ". $user->username . " ". $pwd . " " . $user->timezone . " ". $user->erased;
                    }else{
                        $reply .= $line;
                    }
                 
            }
            $myfile = fopen($file, "w");
            fwrite($myfile, $reply);
            fclose($myfile);
            return true;

        }
        return false;
    }


}
