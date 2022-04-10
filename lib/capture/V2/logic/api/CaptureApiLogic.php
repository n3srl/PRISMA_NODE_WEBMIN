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

    //Get the value from the line
    public static function getValue(String $raw) {
        $value1 = explode("=", $raw)[1];
        return self::trim(self::cleanComments($value1));
    }

    //Get the key from the line
    public static function getKey(String $raw) {
        $key1 = explode("=", $raw)[0];
        return self::trim($key1);
    }

    //Clean string 
    public static function trim(String $raw) {
        return str_replace(array(" ", "\n", "\r"), "", $raw);
    }

    //Clean comments in the end of the string
    public static function cleanComments(String $raw) {
        if (!strpos($raw, "#") === false) {
            return substr($raw, 0, strpos($raw, "#")) . "\n";
        } else {
            return $raw;
        }
    }

    public static function getStationName() {
        $freetureConf = _FREETURE_;
        $stationName = "NO_NAME";

        if (file_exists($freetureConf) && is_file($freetureConf)) {

            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {

                    if (self::getKey($line) === "ACQ_REGULAR_PRFX") {
                        $stationName = self::getValue($line);
                    }
                }
            }
        }
        return $stationName;
    }

    public static function getCapturesFiles($start, $end) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/";
        $reply = null;     
        $tmp_png_dir = _FREETURE_DATA_;
        shell_exec("rm " . $tmp_png_dir . "*.png");
        $files_paths;

        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {
            if ('.' === $day_dir) {
                continue;
            }
            if ('..' === $day_dir) {
                continue;
            }
            $files = scandir($data_dir . "/" . $day_dir . "/captures", SCANDIR_SORT_DESCENDING);
            foreach ($files as $file) {
                if ($i < $start) {
                    $i++;
                    continue;
                }
                if ($i > $end) {
                    return $reply;
                }
                if ('.' === $file) {
                    continue;
                }
                if ('..' === $file) {
                    continue;
                }
                $name = explode("_", $file);
                if (isset($name[1])) {
                    $datetime = date_create($name[1]);
                    $day = $datetime->format('Y-m-d');
                    $hour = $datetime->format('H:i:s');
                    $png_name = str_replace("fit", "png", $file);
                    $fit_path = $data_dir . $day_dir . "/captures/" . $file;
                    $png_path = $tmp_png_dir . $png_name;
                    shell_exec("fitspng -o " . $png_path . " " . $fit_path);
                    $reply[] = array($file, $day, $hour, $png_name, $day_dir . "_" . $file);
                    $i++;
                }
            }
        }
        
        return $reply;
    }
  
    public static function getFilesCount($dir_name) {

        $iTotal = 0;
        if ($handle = opendir($dir_name)) {
            while (false !== ($dir = readdir($handle))) {
                $files = glob($dir_name . $dir . "/captures/" . "*.fit");
                if ($files) {
                    $iTotal = count($files);
                }
            }
            closedir($handle);
        }
        return $iTotal;
    }

    public static function GetListDatatable($request) {
        $reply = null;
        $iDisplayStart = 0;
        $directory = _FREETURE_DATA_ . "/" . self::getStationName() . "/";
        $iTotal = self::getFilesCount($directory);

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getCapturesFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

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
            if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == 'true') {
                $i = $_GET['iSortCol_0'];
                $sort = array_column($reply, $i);
                if ($_GET['sSortDir_' . $i] === 'asc') {
                    array_multisort($sort, SORT_ASC, $reply);
                } else {
                    array_multisort($sort, SORT_DESC, $reply);
                }
            }
        }

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => $reply
        );
        return $output;
    }
    
    
    public static function GetFitFile($file) {
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/";
        $file_info = explode("_", $file);
        $day = $file_info[0] . "_" . $file_info[1];
        $fit_name = $file_info[2] . "_" . $file_info[3] . "_" . $file_info[4];
        $path = $data_dir . $day . "/captures/" . $fit_name;
        return $path;
    }
    
    public static function GetPngFile($file) {
        $path = _FREETURE_DATA_ . $file;
        return $path;
    }

}
