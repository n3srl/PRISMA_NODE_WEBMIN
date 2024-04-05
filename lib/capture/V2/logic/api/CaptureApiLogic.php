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

    /* FILESYSTEM OPERATIONS */
    
    // Get list of all captures in a day
    public static function GetFilesListDatatable($request) {
        $reply = array();
        $iDisplayStart = 1;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $enable_preview = $_GET['enablePreview'] === 'true' ? true : false;
            $directory = self::getDataPath() . $day_dir . "/captures/*";
            $iTotal = self::getDirectoryFilesCount($directory);
            $reply = self::getCapturesFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir, $enable_preview);

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
        /*
          //Ordering
          if (isset($_GET['iSortCol_0'])) {
          if ($_GET['bSortable_' . intval($_GET['iSortCol_0'])] == 'true') {
          $i = $_GET['iSortCol_0'];
          $sort = array_column($reply, $i);
          if ($_GET['sSortDir_' . $i] === 'asc') {
          array_multisort($sort, SORT_ASC, $reply);
          } else {
          array_multisort($sort, SORT_DESC, $reply);
          }}}
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => $reply
        );
        return $output;
    }

    public static function GetDaysListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;
        $directory = self::getDataPath() . "*";
        $iTotal = self::getCapturesDays(0, 0, false);
		
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getCapturesDays($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

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
			} else {
				$iDisplayStart = 0;
				$pageNumber = 0;
				$pageNumber = 0;
			}
        }
        /*
          //Ordering
          if (isset($_GET['iSortCol_0'])) {
          if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == 'true') {
          $i = $_GET['iSortCol_0'];
          $sort = array_column($reply, $i);
          if ($_GET['sSortDir_' . $i] === 'asc') {
          array_multisort($sort, SORT_ASC, $reply);
          } else {
          array_multisort($sort, SORT_DESC, $reply);}}}
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => $pageNumber,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iTotal,
            "aaData" => $reply
        );
        return $output;
    }

    // Get fit file path from given file info
    // File info given is 
    public static function GetFitFile($file) {
        $data_dir = self::getDataPath();
        $file_info = explode("_", $file);
        $day = $file_info[0] . "_" . $file_info[1];
        $fit_name = $file_info[2] . "_" . $file_info[3] . "_" . $file_info[4];
        $path = $data_dir . $day . "/captures/" . $fit_name;
        return $path;
    }

    // Get last capture in time data
    public static function GetLastCapture() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $days = self::getCapturesDays(0, 0, false);
            $files = self::getCapturesFiles(0, 0, $days[0][2], true);
            $lastcapture = $files[0];
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $lastcapture);
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

    // Get captures prefix string
    public static function getCapturePrefix() {
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
    
    // Get the data path parsing freeture configuration file
    public static function getDataPath() {
        $freetureConf = _FREETURE_;
        $dataPath = _FREETURE_DATA_ . "/"._DEFAULT_STATION_CODE_."/";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "STATION_CODE") {
                        $tmp = self::getValue($line);
                        $dataPath = _FREETURE_DATA_ . $tmp . "/";
                    }
                }
            }
        }
        return $dataPath;
    }

    // Encode image to base64
    public static function encodeCapture($path) {
        if (!file_exists($path)) {
            return "";
        }
        $data = file_get_contents($path);
        $base64 = 'data:image/png;base64,' . base64_encode($data);
        return $base64;
    }

    // Process capture fit file, apply watermark and convert image to base64
    public static function processCapture($file, $data_dir) {
        $png_dir = _WEBROOTDIR_ . "tmp-media/";
        $logo_path = _WEBROOTDIR_ . "img/watermark.png";
        
        // Convert fit to png by Fitspng, save in webroot temporary directory
        $png_name_tmp = str_replace(".fit", "-tmp.png", $file);
        $png_path_tmp = $png_dir . $png_name_tmp;
        $fit_path = $data_dir . "/" . $file;
        shell_exec("fitspng -o $png_path_tmp $fit_path");
        
        // Apply watermark by Imagemagick
        $png_name = str_replace(".fit", ".png", $file);
        $png_path = $png_dir . $png_name;
        shell_exec("composite -gravity SouthEast $logo_path $png_path_tmp $png_path");
        
        //apply file name fo image
        $stamp = str_replace(".png","",$png_name);
        $named_png_name = "named_".$png_name;
        $png_named_path = $png_dir .$named_png_name;
        shell_exec("convert $png_path -gravity NorthWest -pointsize 22 -fill white -annotate 0 \"$stamp\" $png_named_path");
                
        $base64 = self::encodeCapture($png_named_path);
        shell_exec("rm " . $png_dir . "*.png"); // Clean temporary png files
        return $base64;
    }

    // Get captures data given starting and ending index and the day directory
    // If preview enabled, convert images to base64
    public static function getCapturesFiles($start, $end, $day_dir, $enablePreview = false) {
        $i = 0;
        // Day directory with captures /freeture/PREFIX/PREFIX_DATE/captures
        $data_dir = self::getDataPath() . $day_dir . "/captures";
        $reply = array();
        // If there isn't data for this day returns an empty array
        if (!is_dir($data_dir)) {
            return $reply;
        }
        $n_day_files = self::getDirectoryFilesCount($data_dir . "/*.fit");
        $files = scandir($data_dir, SCANDIR_SORT_DESCENDING);
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
            
            if (isset($name[1])) { // Check if file name is correct
                
                $datetime = date_create($name[1]);
                //$day = $datetime->format('Y-m-d');
                //$hour = $datetime->format('H:i:s');
                $day = date('Y-m-d', strtotime($name[1]));
                $hour = date('H:i:s', strtotime($name[1]));
                $base64 = $enablePreview ? self::processCapture($file, $data_dir) : "";
                $reply[] = array($file, $day . ":" . $n_day_files, $hour, $base64, $day_dir . "_" . $file);
                $i++;
            }
        }
        return $reply;
    }

    // Get all days and compute number of capture in that day
    public static function getCapturesDays($start, $end) {
        $i = 0;
        // Main directory with days /freeture/PREFIX/
        $data_dir = self::getDataPath();
        $reply = array();
        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {
            
			if (!is_dir($data_dir."/" .$day_dir))
				continue;
			
			if ('.' === $day_dir) {
                continue;
            }
			
            if ('..' === $day_dir) {
                continue;
            }
			
            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/captures/*.fit");
            
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
                //$day = $datetime->format('Y-m-d');
                $day = date('Y-m-d',strtotime($name[1]));
                $reply[] = array($day, $n_day_files, $day_dir);
                $i++;
            }
        }
        return $reply;
    }

    // Compute number of a 2-level directories structure
    public static function getAllDaysFilesCount($path) {
        $n_files = 0;
        if ($handle = opendir($path)) {
            while (false !== ($day = readdir($handle))) {
                $n_files += self::getDirectoryFilesCount($path . $day . "/captures/*.fit");
            }
            closedir($handle);
        }
        return $n_files;
    }

    // Compute number of file in given directory
    public static function getDirectoryFilesCount($path) {
        $n_files = 0;
        $files = glob($path);
        if ($files) {
            $n_files = count($files);
        }
        return $n_files;
    }
}
