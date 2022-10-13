<?php

/**
 *
 * @author: N3 S.r.l.
 */
class StackApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Stack();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = StackLogic::Save($ob);
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

            $ob = new Stack();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = StackLogic::Update($ob);
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

            $ob = new Stack();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = StackLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = StackLogic::Erase($ob);
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

            $ob = new Stack();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = StackLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = StackLogic::Delete($ob);
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
            $ob = StackLogic::Get();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = StackLogic::GetList();
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
            $codes = StackFactory::GetListFilter($columnName, $_GET['term']);
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
                    $foreignKey = end(StackFactory::GetForeignKeyParams($columnName));
                    $codes = StackFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
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

    // Get list of all stacks in a day
    public static function GetFilesListDatatable($request) {
        $reply = array();
        $iDisplayStart = 1;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $enable_preview = $_GET['enablePreview'] === 'true' ? true : false;
            $directory = self::getDataPath() . $day_dir . "/stacks/*";
            $iTotal = self::getDirectoryFilesCount($directory);
            $reply = self::getStacksFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir, $enable_preview);

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
        $iTotal = self::getDirectoryFilesCount($directory);

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getStacksDays($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);

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
        $path = $data_dir . $day . "/stacks/" . $fit_name;
        return $path;
    }

    // Get last stack in time data
    public static function GetLastStack() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $days = self::getStacksDays(0, 0, false);
            $files = self::getStacksFiles(0, 0, $days[0][2], true);
            $laststack = $files[0];
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $laststack);
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

    // Get stacks prefix string
    public static function getStackPrefix() {
        $freetureConf = _FREETURE_;
        $stationName = "NO_TELESCOP";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "TELESCOP") {
                        $stationName = self::getValue($line);
                    }
                }
            }
        }
        return $stationName;
    }

    // Get the data path parsing freeture configuration file
    public static function getDataPath() {
        $freetureConf = _FREETURE_;
        $dataPath = _FREETURE_DATA_ . "/NO_NAME/";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "DATA_PATH") {
                        $tmp = self::getValue($line);
                        $dataPath = _FREETURE_DATA_ . explode("/", $tmp)[2] . "/";
                    }
                }
            }
        }
        return $dataPath;
    }

    // Encode image to base64
    public static function encodeStack($path) {
        if (!file_exists($path)) {
            return "";
        }
        $data = file_get_contents($path);
        $base64 = 'data:image/png;base64,' . base64_encode($data);
        return $base64;
    }

    // Process stack fit file, apply watermark and convert image to base64
    public static function processStack($file, $data_dir) {
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
        shell_exec("convert $png_name -gravity NorthWest -pointsize 22 -fill white -annotate 0 \"$stamp\" $named_png_name");
        
        
        $base64 = self::encodeStack($named_png_name);
        shell_exec("rm " . $png_dir . "*.png"); // Clean temporary png files
        return $base64;
    }

    // Get stacks data given starting and ending index and the day directory
    // If preview enabled, convert images to base64
    public static function getStacksFiles($start, $end, $day_dir, $enablePreview = false) {
        $i = 0;
        // Day directory with stacks /freeture/PREFIX/PREFIX_DATE/stacks
        $data_dir = self::getDataPath() . $day_dir . "/stacks";
        $reply = array();
        // If there isn't data for this day return an empty array
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
                $day = $datetime->format('Y-m-d');
                $hour = $datetime->format('H:i:s');
                $base64 = $enablePreview ? self::processStack($file, $data_dir) : "";
                $reply[] = array($file, $day . ":" . $n_day_files, $hour, $base64, $day_dir . "_" . $file);
                $i++;
            }
        }
        return $reply;
    }

    // Get all days and compute number of stack in that day
    public static function getStacksDays($start, $end) {
        $i = 0;
        // Main directory with days /freeture/PREFIX/
        $data_dir = self::getDataPath();
        $reply = array();
        // If there isn't data for this day returns an empty array
        if (!is_dir($data_dir)) {
            return $reply;
        }
        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {

            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/stacks/*.fit");

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

    // Compute number of a 2-level directories structure
    public static function getAllDaysFilesCount($path) {
        $n_files = 0;
        if ($handle = opendir($path)) {
            while (false !== ($day = readdir($handle))) {
                $n_files += self::getDirectoryFilesCount($path . $day . "/stacks/*.fit");
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
