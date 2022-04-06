<?php

/**
 *
 * @author: N3 S.r.l.
 */
class CaptureApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Capture();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = CaptureLogic::Save($ob);
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

            $ob = new Capture();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = CaptureLogic::Update($ob);
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

            $ob = new Capture();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = CaptureLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = CaptureLogic::Erase($ob);
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

            $ob = new Capture();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = CaptureLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = CaptureLogic::Delete($ob);
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
            $ob = CaptureLogic::Get();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = CaptureLogic::GetList();
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
            $codes = CaptureFactory::GetListFilter($columnName, $_GET['term']);
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
            switch ($columnName) {
                default:
                    $foreignKey = end(CaptureFactory::GetForeignKeyParams($columnName));
                    $codes = CaptureFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
                    $data = new stdClass();
                    foreach ($codes as $code) {
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

    public static function GetListDatatable($request) {
        $reply;
        $iDisplayStart;
        $iTotal = 300;
        
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            for ($i = $iDisplayStart; $i <= $iDisplayStart + $iDisplayLength; $i++) {
                $reply[] = array("nome" . $i, "data" . 0, "preview" . $i, "download" . $i);
                $reply[] = array("nome" . $i, "data" . 1, "preview" . $i, "download" . $i);
                $reply[] = array("nome" . $i, "data" . 2, "preview" . $i, "download" . $i);
            }

            $test = $reply[0];
            if (empty($test)) {
                $iDisplayStart = 0;
            }
            if ($iDisplayStart < $iDisplayLength) {
                $pageNumber = 0;
            } else {
                $pageNumber = ($iDisplayStart / $iDisplayLength);
            }
        }
        
        /* Ordering */
        if (isset($_GET['iSortCol_0'])) {
            //for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == 'true') {
                    $i = $_GET['iSortCol_0'];
                    $sort = array_column($reply, $i);
                    if($_GET['sSortDir_' . $i] === 'asc'){
                        array_multisort($sort, SORT_ASC, $reply);
                    } else {
                        array_multisort($sort, SORT_DESC, $reply);
                    }
                    
                }
            //}
           
        }
            
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iDisplayLength,
            "aaData" => $reply
        );
        return $output;
    }

    public static function getFileList() {
        $reply;
        

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            for ($i = $iDisplayStart; $i <= $iDisplayStart + $iDisplayLength; $i++) {
                $reply[] = array("nome" . $i, "data" . $i, "preview" . $i, "download" . $i);
            }

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
       
    }

}
