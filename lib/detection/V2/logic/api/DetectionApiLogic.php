<?php

/**
 *
 * @author: N3 S.r.l.
 */
class DetectionApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Detection();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = DetectionLogic::Save($ob);
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

            $ob = new Detection();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = DetectionLogic::Update($ob);
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

            $ob = new Detection();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = DetectionLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DetectionLogic::Erase($ob);
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

            $ob = new Detection();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = DetectionLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DetectionLogic::Delete($ob);
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
            $ob = DetectionLogic::Get();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = DetectionLogic::GetList();
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
            $codes = DetectionFactory::GetListFilter($columnName, $_GET['term']);
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
                    $foreignKey = end(DetectionFactory::GetForeignKeyParams($columnName));
                    $codes = DetectionFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
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
    
    // Get the station name parsing freeture configuration file
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
    
    // Scan filesystem to get events folder
    public static function getDetectionsFiles($start, $end, $clean = true) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/"; // /freeture/STATION_NAME/
        $reply = null;
        $tmp_png_dir = _FREETURE_DATA_;
        if ($clean) {
            shell_exec("rm " . $tmp_png_dir . "*.png");
        }

        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {
            // /freeture/STATION_NAME/STATION_NAME_DAY/events/
            $n_day_detections = self::getDirectoryFilesCount($data_dir . $day_dir . "/events/*"); 
            if ('.' === $day_dir) {
                continue;
            }
            if ('..' === $day_dir) {
                continue;
            }
            $day_detections = scandir($data_dir . $day_dir . "/events", SCANDIR_SORT_DESCENDING);
            foreach ($day_detections as $detection_dir) {
                if ($i < $start) {
                    $i++;
                    continue;
                }
                if ($i > $end) {
                    return $reply;
                }
                if ('.' === $detection_dir) {
                    continue;
                }
                if ('..' === $detection_dir) {
                    continue;
                }
                $name = explode("_", $detection_dir);
                // /freeture/STATION_NAME/STATION_NAME_DAY/events/STATION_NAME_DAY_HOUR
                if (isset($name[1])) {
                    $datetime = date_create($name[1]);
                    $day = $datetime->format('Y-m-d');
                    $hour = $datetime->format('H:i:s');
                    // /freeture/STATION_NAME/STATION_NAME_DAY/events/STATION_NAME_DAY_HOUR/*.fit
                    $fits = glob($data_dir . $day_dir . "/events/" . $detection_dir . "/*.fit");
                    $exp_fit = explode("/", $fits[0]);
                    $png_name =  str_replace(".fit", ".png", $exp_fit[6]);
                    $fit_path = $fits[0];
                    $png_path = $tmp_png_dir . $png_name;
                    shell_exec("fitspng -o $png_path $fit_path");
                    $reply[] = array($detection_dir, $day . ":" . $n_day_detections, $hour, $png_name,
                                     $day_dir . "_" . $detection_dir, // STATION_NAME_DAY_STATION_NAME_DAY_HOUR
                                     $day_dir . "_" . $detection_dir,
                                     $day_dir . "_" . $detection_dir);
                    $i++;
                }
            }
        }

        return $reply;
    }

    public static function getAllDaysFilesCount($path) {

        $n_files = 0;
        if ($handle = opendir($path)) {
            while (false !== ($day = readdir($handle))) {
                $n_files += self::getDirectoryFilesCount($path . $day . "/events/*");
            }
            closedir($handle);
        }
        return $n_files;
    }

    public static function getDirectoryFilesCount($path) {

        $n_files = 0;
        $files = glob($path);
        if ($files) {
            $n_files = count($files);
        }
        return $n_files;
    }

    public static function GetListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;
        $directory = _FREETURE_DATA_ . "/" . self::getStationName() . "/";
        $iTotal = self::getAllDaysFilesCount($directory);
        
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getDetectionsFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

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
    
    public static function GetPng($detection) {
        $path = "";
        if ($detection === "last-detection") {
            $path = self::GetLastDetection();
        } else {
            $path = _FREETURE_DATA_ . $detection;
        }
        return $path;
    }
    
    public static function GetLastDetection() {
        $files = self::getDetectionsFiles(0, 0, false);
        $lastfile = _FREETURE_DATA_ . $files[0][3];
        return $lastfile;
    }

    public static function GetGeMap($detection) {
        $base_path = self::getDetectionBasePath($detection);
        $path = $base_path . "/GeMap.bmp";
        return $path;
    }

    public static function GetDirMap($detection) {
        $base_path = self::getDetectionBasePath($detection);
        $path = $base_path . "/DirMap.bmp";
        return $path;
    }
    
    public static function GetZip($zip) {
        return  _FREETURE_DATA_ . $zip;
    }

    public static function CreateZip($detection) {
        $detection_folder = self::getDetectionBasePath($detection);
        $detection_info = explode("_", $detection);
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];
        if(file_exists(_FREETURE_DATA_ . $detection_name . ".zip")) {
            return $detection_name . ".zip";
        }
        shell_exec("rm " .  _FREETURE_DATA_ . "*.zip");
        $zipcreated = self::zipFolder($detection_folder, $detection_name);
        return $zipcreated;
    }
    
    // Create the zip of the passed folder and put it in /freeture/
    public static function zipFolder($pathdir, $zipname) {
        $zipcreated = _FREETURE_DATA_ . $zipname . ".zip";
        /*
        $zip = new ZipArchive;
        if($zip -> open($zipcreated, ZipArchive::CREATE ) === true) {
            $dir = opendir($pathdir);
            while($file = readdir($dir)) {
                if(is_file($pathdir.$file)) {
                    $zip -> addFile($pathdir.$file, $file);
                }
            }
            $zip ->close();
        }*/
        shell_exec("zip -r $zipcreated $pathdir");
        return $zipname . ".zip";
    }
      
    public static function getDetectionBasePath($detection){
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/";
        $detection_info = explode("_", $detection);
        $day = $detection_info[0] . "_" . $detection_info[1];
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];
        $base_path = $data_dir . $day . "/events/" . $detection_name;
        return $base_path;
    }
    
    
}
