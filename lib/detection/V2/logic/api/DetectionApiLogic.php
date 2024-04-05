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

    /* FILESYSTEM OPERATIONS */

    // Get list of all detections in a day
    public static function GetFilesListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $enable_preview = $_GET['enablePreview'] === 'true' ? true : false;
            $directory = _FREETURE_DATA_ . self::getStationCode() . "/" . $day_dir . "/events/*";
            $iTotal = self::getDirectoryFilesCount($directory);
            $reply = self::getDetectionsFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir, $enable_preview);

			if (!empty($reply)){
				$test = $reply[0];
				if (empty($test)) {
					$iDisplayStart = 0;
				}
				if ($iDisplayStart < $iDisplayLength) {
					$pageNumber = 0;
				} else {
					$pageNumber = ($iDisplayStart / $iDisplayLength);
				}
			} else {
				$iDisplayStart = 0;
				$pageNumber = 0;
				$pageNumber = 0;
			}
        }

        /* Ordering 
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
          } */

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
        $directory = _FREETURE_DATA_ . self::getStationCode() . "/*";
        $iTotal = count(self::getDetectionsDays(0,365,false));

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getDetectionsDays($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

			if (!empty($reply)) {
				$test = $reply[0];
				if (empty($test)) {
					$iDisplayStart = 0;
				}
				if ($iDisplayStart < $iDisplayLength) {
					$pageNumber = 0;
				} else {
					$pageNumber = ($iDisplayStart / $iDisplayLength);
				}

			}else {
				$iDisplayStart = 0;
				$pageNumber = 0;
				$pageNumber = 0;
			}
        }

        /* Ordering 
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
          } */

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => $reply
        );
        return $output;
    }

    // Get path to last detection info
    public static function GetLastDetection() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $days = self::getDetectionsDays(0, 0);
            $files = self::getDetectionsFiles(0, 0, $days[0][2], true);
            $lastdetection = $files[0];
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $lastdetection);
    }

    // Get already created detection zip
    public static function GetZip($zip) {
        return _WEBROOTDIR_ . "tmp-media/" . $zip;
    }

    // Reset zip in progress
    public static function ResetZip() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $res = self::cancelZips();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $res);
    }

    // Create zip of detection
    public static function CreateZip($detection) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $zip = self::processDetectionZip($detection);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $zip);
    }

    // Get already created detection video
    public static function GetVideo($video) {
        return _WEBROOTDIR_ . "tmp-media/" . $video;
    }

    // Reset video in progress
    public static function ResetVideo() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $res = self::cancelVideos();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $res);
    }

    // Create video of detection
    public static function CreateVideo($detection) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $zip = self::processDetectionVideo($detection);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $zip);
    }

    // Get number of detection of last day
    public static function GetLastDayDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $now = new DateTime();
            $date_dir = self::getDetectionPrefix() . "_" . $now->format('Ymd');
            $path = _FREETURE_DATA_ . self::getStationCode() . "/" . $date_dir . "/events/*";
            $n_files = self::getDirectoryFilesCount($path);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }

    // Get number of detection of last month
    public static function GetLastMonthDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $n_files = self::getLastMonthTotal();
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }

    // Get number of detection of all time
    public static function GetAllDetectionNumber() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $path = _FREETURE_DATA_ . self::getStationCode() . "/";
            $n_files = self::getAllDaysFilesCount($path);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $n_files);
    }

    // Compute number of detection of current month
    public static function getLastMonthTotal() {
        $path = _FREETURE_DATA_ . self::getStationCode() . "/";
        $now = new DateTime();
        $month1 = $now->format('m');
        $n_files = 0;

        try {
            if ($handle = opendir($path)) {
                
                while (false !== ($day = readdir($handle))) {
                    $name2 = explode("_", $day);
                    $datetime2 = date_create($name2[1]);
                    $month2 = date('m', strtotime($name2[1]));
    
                    // Find folder of the right month
                    if ($month1 === $month2) {
                        $n_files += self::getDirectoryFilesCount($path . $day . "/events/*");
                    } else if (intval($month1) < intval($month2)) {
                        break;
                    }
                }
                closedir($handle);
            }
        } catch(Exception $e) {
            // DO nothing
        }
        return $n_files;
    }

    // Kill any active zip process
    public static function cancelZips() {
        shell_exec("killall zip");
        return true;
    }

    // Create the zip of the passed folder and put it in /tmp-media/ in webroot
    public static function processDetectionZip($detection) {
        self::cancelZips();
        $detection_folder = self::getDetectionBasePath($detection);
        $detection_info = explode("_", $detection);
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];

        if (file_exists(_WEBROOTDIR_ . "tmp-media/" . $detection_name . ".zip")) {
            return $detection_name . ".zip";
        }

        //shell_exec("rm " . _WEBROOTDIR_ . "tmp-media/" . "*.zip");
        $zipcreated = _WEBROOTDIR_ . "tmp-media/" . $detection_name . ".zip";
        shell_exec("zip -r $zipcreated $detection_folder");
        return $detection_name . ".zip";
    }

    // Kill any active video process
    public static function cancelVideos() {
        $_SESSION['cancel_video'] = true;
        shell_exec("killall fitspng");
        shell_exec("killall ffmpeg");
        $frames_dir = _WEBROOTDIR_ . "tmp-media/tmp-video/";
        shell_exec("rm -r $frames_dir");
        return true;
    }

    // Create the video of detection and put it in /tmp-media/ in webroot
    public static function processDetectionVideo($detection) {
        self::cancelVideos();
        $_SESSION['cancel_video'] = false;

        $detection_folder = self::getDetectionBasePath($detection);
        $detection_info = explode("_", $detection);
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];

        if (file_exists(_WEBROOTDIR_ . "tmp-media/" . $detection_name . ".mkv")) {
            return $detection_name . ".mkv";
        }

        $video = self::makeVideo($detection_folder . "/", $detection_name);
        return $video;
    }

    // Get base path to passed detection files
    public static function getDetectionBasePath($detection) {
        $data_dir = _FREETURE_DATA_ . self::getStationCode() . "/";
        $detection_info = explode("_", $detection);
        $day = $detection_info[0] . "_" . $detection_info[1];
        $detection_name = $detection_info[2] . "_" . $detection_info[3] . "_" . $detection_info[4];
        $base_path = $data_dir . $day . "/events/" . $detection_name;
        return $base_path;
    }

    // Get the value from the line
    public static function getValue(String $raw) {
        $value1 = explode("=", $raw)[1];
        return trim(self::cleanComments($value1));
    }

    // Get the key from the line
    public static function getKey(String $raw) {
        $key1 = explode("=", $raw)[0];
        return self::trim($key1);
    }

    // Clean string 
    public static function trim(String $raw) {
        return str_replace(array(" ", "\n", "\r"), "", $raw);
    }

    // Clean comments in the end of the string
    public static function cleanComments(String $raw) {
        if (!strpos($raw, "#") === false) {
            return substr($raw, 0, strpos($raw, "#")) . "\n";
        } else {
            return $raw;
        }
    }

    // Get the detection prefix parsing freeture configuration file
    public static function getDetectionPrefix() {
        $freetureConf = _FREETURE_;
        $stationName = _DEFAULT_STATION_CODE_;

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "STATION_NAME") {
                        $stationName = self::getValue($line);
                    }
                }
            }
        }
        return $stationName;
    }
    
    // Get the station code parsing freeture configuration file
    public static function getStationCode() {
        $freetureConf = _FREETURE_;
        $stationCode = _DEFAULT_STATION_CODE_;

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "STATION_CODE") {
                        $stationCode = self::getValue($line);
                    }
                }
            }
        }
        return $stationCode;
    }

    // Get all days and compute number of detections in that day
    public static function getDetectionsDays($start, $end) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationCode() . "/";
        $reply = array();
        // If there isn't data for this day returns an empty array
        if (!is_dir($data_dir)) {
            return $reply;
        }
        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {

			if (!is_dir($data_dir . "/" . $day_dir))
				continue;
			
			if ('.' === $day_dir) {
                continue;
            }
            if ('..' === $day_dir) {
                continue;
            }
			
            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/events/*");

			if ($n_day_files == 0) {
				continue;
			}
			
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }
           

            $name = explode("_", $day_dir);

            if (isset($name[1])) {
                $datetime = date_create($name[1]);
                $day = date('Y-m-d', strtotime($name[1]));
                $reply[] = array($day, $n_day_files, $day_dir);
                $i++;
            }
        }

        return $reply;
    }

    // Scan filesystem to get events folder
    public static function getDetectionsFiles($start, $end, $date_dir, $enablePreview = false) {
        $i = 0;
        $data_dir = _FREETURE_DATA_ . self::getStationCode() . "/" . $date_dir . "/events";
        $reply = array();

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
                $day = date('Y-m-d', strtotime($name[1]));
                $hour = date('H:i:s', strtotime($name[1]));

                $processedFiles = $enablePreview ? self::processDetection($detection, $data_dir) : array("", "", "");

                $preview_base64 = $processedFiles[0];
                $dirmap_base64 = $processedFiles[1];
                $gemap_base64 = $processedFiles[2];

                $reply[] = array($detection, $day . ":" . $n_day_detections, $hour,
                    $preview_base64,
                    $dirmap_base64,
                    $gemap_base64,
                    $date_dir . "_" . $detection,
                    $date_dir . "_" . $detection); // STATION_NAME_DAY_STATION_NAME_DAY_HOUR
                $i++;
            }
        }
        return $reply;
    }

    // Process capture fit file, apply watermark and convert image to base64, create video
    public static function processDetection($detection, $data_dir) {

        // Path strcuture: /freeture/STATION_NAME/STATION_NAME_DAY/events/STATION_NAME_DAY_HOUR/*.fit
        $tmp_png_dir = _WEBROOTDIR_ . "tmp-media/";
        $logo_path = _WEBROOTDIR_ . "img/watermark.png";
        $fits = glob($data_dir . "/" . $detection . "/*.fit");
        $fit_path = $fits[0];
        $exp_fit = explode("/", $fits[0]);

        // Convert fit to png
        $png_name_tmp = str_replace(".fit", "-tmp.png", $exp_fit[6]);
        $png_path_tmp = $tmp_png_dir . $png_name_tmp;
        shell_exec("fitspng -o $png_path_tmp $fit_path");

        // Apply watermark
        $png_name = str_replace(".fit", ".png", $exp_fit[6]);
        $png_path = $tmp_png_dir . $png_name;
        shell_exec("composite -gravity SouthEast $logo_path $png_path_tmp $png_path");

        $gemap_path = $data_dir . "/" . $detection . "/GeMap.bmp";
        $dirmap_path = $data_dir . "/" . $detection . "/DirMap.bmp";

        
        //apply file name fo image
        $stamp = str_replace(".png","",$png_name);
        $named_png_name = "named_".$png_name;
        $png_named_path = $tmp_png_dir .$named_png_name;
        shell_exec("convert $png_path -gravity NorthWest -pointsize 22 -fill white -annotate 0 \"$stamp\" $png_named_path");
              
        
        $preview_base64 = self::encodeDetection($png_named_path);
        $gemap_base64 = self::encodeDetection($gemap_path, "bmp");
        $dirmap_base64 = self::encodeDetection($dirmap_path, "bmp");

        shell_exec("rm " . $tmp_png_dir . "*.png");
        return array($preview_base64, $dirmap_base64, $gemap_base64);
    }

    // Convert media to base64 (default png)
    public static function encodeDetection($path, $type = "png") {
        if (!file_exists($path)) {
            return "";
        }
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    // Make video of passed detection
    public static function makeVideo($detection_dir, $video_name) {

        $media_dir = _WEBROOTDIR_ . "tmp-media/";
        $frames_dir = $media_dir . "tmp-video/";
        $logo_path = _WEBROOTDIR_ . "img/watermark.png";
        mkdir($frames_dir);

        // Convert each frame to png
        $frames = scandir($detection_dir . "fits2D", SCANDIR_SORT_ASCENDING);
        foreach ($frames as $frame) {
            if ($_SESSION['cancel_video']) {
                return;
            }
            if ('.' === $frame) {
                continue;
            }
            if ('..' === $frame) {
                continue;
            }
            $frame_png = str_replace(".fit", ".png", $frame);
            $frame_path = $detection_dir . "fits2D/" . $frame;
            shell_exec("fitspng -o " . $frames_dir . $frame_png . " " . $frame_path);
        }
        $video_path = $media_dir . $video_name . ".mkv";
        $video_path_tmp = $media_dir . $video_name . "_tmp.mkv";
        shell_exec("cat " . $frames_dir . "*.png | ffmpeg -f image2pipe -i - $video_path_tmp");
        shell_exec("ffmpeg -i $video_path_tmp -i $logo_path -filter_complex 'overlay=W-w-5:H-h-5' $video_path");
        shell_exec("rm -r $frames_dir");
        shell_exec("rm $video_path_tmp");
        return $video_name . ".mkv";
    }

    // Count number of files in 2-level directories
    public static function getAllDaysFilesCount($path) {
        $n_files = 0;
        try {
            if ($handle = opendir($path)) {
                while (false !== ($day = readdir($handle))) {
                    $n_files += self::getDirectoryFilesCount($path . $day . "/events/*");
                }
                closedir($handle);
            }
        } catch(Exception $e) {
            // DO nothing prevent print warning on stdout
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

}
