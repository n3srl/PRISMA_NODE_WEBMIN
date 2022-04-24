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
    public static function getDetectionsFiles($start, $end, $date_dir, $clean = true) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/" . $date_dir . "/events";
        $reply = array();
        $tmp_png_dir = _WEBROOTDIR_ . "tmp-media/";
        $logo_path = _WEBROOTDIR_ . "img/watermark.png";

        if ($clean) {
            shell_exec("rm " . $tmp_png_dir . "*.png");
            shell_exec("rm " . $tmp_png_dir . "*.mkv");
        }
        if (!is_dir($data_dir)) {
            return $reply;
        }
        $n_day_detections = self::getDirectoryFilesCount($data_dir . "/*");
        $detections = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($detections as $detection) {
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }
            if ('.' === $detection) {
                continue;
            }
            if ('..' === $detection) {
                continue;
            }
            $name = explode("_", $detection);
            if (isset($name[1])) {
                $datetime = date_create($name[1]);
                $day = $datetime->format('Y-m-d');
                $hour = $datetime->format('H:i:s');
                // /freeture/STATION_NAME/STATION_NAME_DAY/events/STATION_NAME_DAY_HOUR/*.fit
                $fits = glob($data_dir . "/" . $detection . "/*.fit");
                $fit_path = $fits[0];
                $exp_fit = explode("/", $fits[0]);
                $png_name_tmp = str_replace(".fit", "-tmp.png", $exp_fit[6]);
                $png_path_tmp = $tmp_png_dir . $png_name_tmp;
                shell_exec("fitspng -o $png_path_tmp $fit_path");
                $png_name = str_replace(".fit", ".png", $exp_fit[6]);
                $png_path = $tmp_png_dir . $png_name;
                shell_exec("composite -gravity SouthEast $logo_path $png_path_tmp $png_path");
                //$video = self::makeVideo($data_dir . "/" . $detection . "/", $detection);
                $reply[] = array($detection, $day . ":" . $n_day_detections, $hour, $png_name,
                    $date_dir . "_" . $detection, // STATION_NAME_DAY_STATION_NAME_DAY_HOUR
                    $date_dir . "_" . $detection,
                    $date_dir . "_" . $detection);
                $i++;
            }
        }

        return $reply;
    }

    public static function makeVideo($detection_dir, $video_name) {
        $video_dir = _WEBROOTDIR_ . "detection-video/";
        $media_dir = _WEBROOTDIR_ . "tmp-media/";
        $frames = array();
        $positions = $detection_dir . "positions.txt";

        if (file_exists($positions) && is_file($positions)) {
            $contents = file($positions);
            foreach ($contents as $line) {
                $info = explode(" ", $line);
                $frames[] = $info[0];
            }
        }
        foreach ($frames as $frame) {
            $frame_path = $detection_dir . "fits2D/frame_" . $frame . ".fit";
            shell_exec("fitspng -o " . $video_dir . $frame . ".png " . $frame_path);
        }
        /*
          $frames = scandir($detection_dir . "fits2D", SCANDIR_SORT_DESCENDING);
          foreach($frames as $frame){
          $frame_path = $detection_dir . "fits2D/" . $frame;
          shell_exec("fitspng -o " . $video_dir . $frame . ".png " . $frame_path);
          } */

        shell_exec("cat " . $video_dir . "*.png | ffmpeg -f image2pipe -i - " . $media_dir . $video_name . ".mkv");
        shell_exec("rm " . $video_dir . "*.png");
        return $video_name . ".mkv";
    }

    public static function getDetectionsDays($start, $end) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/";
        $reply = array();

        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {
            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/events/*");
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }
            if ('.' === $day_dir) {
                continue;
            }
            if ('..' === $day_dir) {
                continue;
            }
            $name = explode("_", $day_dir);
            if (isset($name[1])) {
                $datetime = date_create($name[1]);
                $day = $datetime->format('Y-m-d');
                $reply[] = array($day, $n_day_files, $day_dir);
                $i++;
            }
        }

        return $reply;
    }
    
    // Count number of files in 2-level directories
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
    
    // Count number of file in a directory
    public static function getDirectoryFilesCount($path) {

        $n_files = 0;
        $files = glob($path);
        if ($files) {
            $n_files = count($files);
        }
        return $n_files;
    }
    
    // Get list of all detection in a day
    public static function GetFilesListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $directory = _FREETURE_DATA_ . self::getStationName() . "/" . $day_dir . "/events/*";
            $iTotal = self::getDirectoryFilesCount($directory);
            $reply = self::getDetectionsFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir);

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
            if ($_GET['bSortable_' . intval($_GET['iSortCol_0'])] == 'true') {
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
    
    // Get list of all days detections folders
    public static function GetDaysListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;
        $directory = _FREETURE_DATA_ . "/" . self::getStationName() . "/*";
        $iTotal = self::getDirectoryFilesCount($directory);

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getDetectionsDays($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

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
    
    // Return path to detection png
    public static function GetPng($detection) {
        $path = "";
        if ($detection === "lastdetection") {
            $path = self::GetLastDetection();
        } else {
            $path = _WEBROOTDIR_ . "tmp-media/" . $detection;
        }
        return $path;
    }
    
    // Return path to last detection png
    public static function GetLastDetection() {
        $days = self::getDetectionsDays(0, 0, false);
        $files = self::getDetectionsFiles(0, 0, $days[0][2], false);
        $lastfile = _WEBROOTDIR_ . "tmp-media/" . $files[0][3];
        return $lastfile;
    }
    
    // Return last detection name
    public static function GetLastDetectionInfo() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $days = self::getDetectionsDays(0, 0, false);
            $files = self::getDetectionsFiles(0, 0, $days[0][2], false);
            $lastfile = $files[0][3];
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $lastfile);
    }
    
    // Return detection geMap
    public static function GetGeMap($detection) {
        $base_path = self::getDetectionBasePath($detection);
        $path = $base_path . "/GeMap.bmp";
        return $path;
    }
    
    // Return detection dirMap
    public static function GetDirMap($detection) {
        $base_path = self::getDetectionBasePath($detection);
        $path = $base_path . "/DirMap.bmp";
        return $path;
    }
    
    // Return detection zip 
    public static function GetZip($zip) {
        return _FREETURE_DATA_ . $zip;
    }
    
    // Create zip of detection
    public static function CreateZip($detection) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $zip = self::zipDetection($detection);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $zip);
    }

    public static function zipDetection($detection) {
        $detection_folder = self::getDetectionBasePath($detection);
        $detection_info = explode("_", $detection);
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];
        if (file_exists(_WEBROOTDIR_ . "tmp-media/" . $detection_name . ".zip")) {
            return $detection_name . ".zip";
        }
        shell_exec("rm " . _WEBROOTDIR_ . "tmp-media/" . "*.zip");
        $zipcreated = self::zipFolder($detection_folder, $detection_name);
        return $zipcreated;
    }

    // Create the zip of the passed folder and put it in /tmp-media/ in webroot
    public static function zipFolder($pathdir, $zipname) {
        $zipcreated = _WEBROOTDIR_ . "tmp-media/" . $zipname . ".zip";
        shell_exec("zip -r $zipcreated $pathdir");
        return $zipname . ".zip";
    }

    public static function getDetectionBasePath($detection) {
        $data_dir = _FREETURE_DATA_ . self::getStationName() . "/";
        $detection_info = explode("_", $detection);
        $day = $detection_info[0] . "_" . $detection_info[1];
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];
        $base_path = $data_dir . $day . "/events/" . $detection_name;
        return $base_path;
    }
    
    // Return number of detection of last day
    public static function GetLastDayDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $png = self::GetLastDetectionInfo();
            $now = new DateTime();
            $date_dir = self::getStationName() . "_" . $now->format('Ymd');
            $path = _FREETURE_DATA_ . self::getStationName() . "/" . $date_dir . "/events/*";
            $n_files = self::getDirectoryFilesCount($path);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }
    
    // Return number of detection of last month
    public static function GetLastMonthDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $n_files = self::getLastMonthTotal();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }
    
    public static function getLastMonthTotal() {
        $png = self::GetLastDetectionInfo();
        $path = _FREETURE_DATA_ . self::getStationName() . "/";
        $now = new DateTime();
        $month1 = $now->format('m');
        $n_files = 0;
        if ($handle = opendir($path)) {
            while (false !== ($day = readdir($handle))) {
                $name2 = explode("_", $day);
                $datetime2 = date_create($name2[1]);
                $month2 = $datetime2->format('m');
                if ($month1 === $month2) {
                    $n_files += self::getDirectoryFilesCount($path . $day . "/events/*");
                } else if (intval($month1) < intval($month2)) {
                    break;
                }
            }
            closedir($handle);
        }
        return $n_files;
    }
    
    // Return number of detection of all time
    public static function GetAllDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $path = _FREETURE_DATA_ . self::getStationName() . "/";
            $n_files = self::getAllDaysFilesCount($path);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }

}
