<?php

/**
 *
 * @author: N3 S.r.l.
 */
class GroupHasPersonApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new GroupHasPerson();

            CoreLogic::getFromArray($ob, $request->get("data"));
            CoreLogic::beginTransaction();
            $res = GroupHasPersonLogic::Save($ob);
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

            $ob = new GroupHasPerson();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = GroupHasPersonLogic::Get($ob->id);

            CoreLogic::getFromArray($ob, $request->get("data"));

            CoreLogic::beginTransaction();
            $res = GroupHasPersonLogic::Update($ob);
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

            $ob = new GroupHasPerson();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = GroupHasPersonLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = GroupHasPersonLogic::Erase($ob);
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

            $ob = new GroupHasPerson();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = GroupHasPersonLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = GroupHasPersonLogic::Delete($ob);
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
            $ob = GroupHasPersonLogic::Get($id);
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = GroupHasPersonLogic::GetList();
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
            $codes = GroupHasPersonFactory::GetListFilter($columnName, $_GET['term']);
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
            $foreignKey = end(GroupHasPersonFactory::GetForeignKeyParams($columnName));
            $codes = GroupHasPersonFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
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
        $aColumns = array('`oid`', '`person_id`', '`group_id`', '`id`');
        $aColumnsName = array('oid', 'person_id', 'group_id', 'id');
        $sIndexColumn = 'id';
        $sTable = 'core_group_has_person';
        $gaSql['link'] = $db_conn;
        ;
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

}
