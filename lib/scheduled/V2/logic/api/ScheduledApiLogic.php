<?php

/**
 *
 * @author: N3 S.r.l.
 */
class ScheduledApiLogic {

    public static function Save($request) {
        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Scheduled();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = ScheduledLogic::Save($ob);
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

            $ob = new Scheduled();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $res = ScheduledLogic::Update($ob);
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

            $ob = new Scheduled();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = ScheduledLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = ScheduledLogic::Erase($ob);
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

            $ob = new Scheduled();
            $tmp = $request->get("data");

            $ob->name = $tmp["name"];
            $ob->date = $tmp["date"];

            $ob = ScheduledLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = ScheduledLogic::Delete($ob);
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
            $ob = ScheduledLogic::Get();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = ScheduledLogic::GetList();
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
            $codes = ScheduledFactory::GetListFilter($columnName, $_GET['term']);
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
                    $foreignKey = end(ScheduledFactory::GetForeignKeyParams($columnName));
                    $codes = ScheduledFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
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

    // Get list of all scheduled acquisitions in a day
    public static function GetFilesListDatatable($request) {
        $reply = array();
        $iTotal = 0;
        $pageNumber = 0;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $enable_preview = $_GET['enablePreview'] === 'true' ? true : false;

            $iTotal = count(self::collectScheduledFiles($day_dir));
            $reply = self::getScheduledFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir, $enable_preview);

            if ($iDisplayLength > 0 && $iDisplayStart >= $iDisplayLength) {
                $pageNumber = intdiv($iDisplayStart, $iDisplayLength);
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

    public static function GetDaysListDatatable($request)
    {

        $draw   = isset($_GET['draw']) ? (int)$_GET['draw'] : (isset($_GET['sEcho']) ? (int)$_GET['sEcho'] : 0);
        $start  = isset($_GET['start']) ? (int)$_GET['start'] : (isset($_GET['iDisplayStart']) ? (int)$_GET['iDisplayStart'] : 0);
        $length = isset($_GET['length']) ? (int)$_GET['length'] : (isset($_GET['iDisplayLength']) ? (int)$_GET['iDisplayLength'] : 10);
        if ($length === -1) { $length = 10; }
        if ($start < 0) { $start = 0; }

        if (isset($_GET['order'][0]['column'])) {
            $orderCol = (int)$_GET['order'][0]['column'];
            $orderDir = (isset($_GET['order'][0]['dir']) && strtolower($_GET['order'][0]['dir']) === 'desc') ? 'desc' : 'asc';
        } else {

            $orderCol = isset($_GET['iSortCol_0']) ? (int)$_GET['iSortCol_0'] : 0;
            $orderDir = (isset($_GET['sSortDir_0']) && strtolower($_GET['sSortDir_0']) === 'desc') ? 'desc' : 'asc';
        }

        $all = self::getScheduledDays(0, 365);
        if (!is_array($all)) { $all = []; }

        $recordsTotal = count($all);

        $filtered = $all;

        $dirMult = ($orderDir === 'desc') ? -1 : 1;
        usort($filtered, function($a, $b) use ($orderCol, $dirMult) {
            $va = $a[$orderCol] ?? null;
            $vb = $b[$orderCol] ?? null;


            $bothNumeric = is_numeric($va) && is_numeric($vb);
            if ($bothNumeric) {
                return $dirMult * ($va <=> $vb);
            }

            return $dirMult * strcasecmp((string)$va, (string)$vb);
        });

        $recordsFiltered = count($filtered);

        if ($start >= $recordsFiltered) { $start = 0; }
        $data = array_slice($filtered, $start, $length);

        $pageNumber = ($length > 0) ? intdiv($start, $length) : 0;


        $output = [

            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,

            "sEcho" => $draw,
            "iTotalRecords" => $recordsTotal,
            "iTotalDisplayRecords" => $recordsFiltered,
            "aaData" => $data,


            "pageToShow" => $pageNumber,
        ];

        return $output;
    }


    // Get fit file path from given file info
    // File info given is FOLDER_PART1_FOLDER_PART2_FILE
    public static function GetFitFile($file) {
        $data_dir = self::getDataPath();
        $file_info = explode("_", $file);
        $day = $file_info[0] . "_" . $file_info[1];
        $fit_name = $file_info[2] . "_" . $file_info[3] . "_" . $file_info[4];
        $path = $data_dir . $day . "/schedules/" . $fit_name;
        return $path;
    }

    // Get last scheduled acquisition in time data.
    // Scans every station/day folder under the data root and picks the file
    // with the most recent timestamp embedded in its name, regardless of which
    // folder holds it.
    public static function GetLastScheduled() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $lastscheduled = null;

            $base_dir = self::getDataPath();
            if (is_dir($base_dir)) {
                $datePattern = '/(\d{8})T(\d{6})/';
                $bestKey = '';
                $bestFile = null;
                $bestFolder = null;
                $bestScheduledDir = null;

                foreach (scandir($base_dir) as $folder) {
                    if ('.' === $folder || '..' === $folder) {
                        continue;
                    }
                    $scheduled_dir = $base_dir . $folder . "/schedules";
                    if (!is_dir($scheduled_dir)) {
                        continue;
                    }
                    foreach (scandir($scheduled_dir) as $entry) {
                        if ('.' === $entry || '..' === $entry) {
                            continue;
                        }
                        if (substr_compare($entry, '.fit', -4, 4, true) !== 0) {
                            continue;
                        }
                        if (!preg_match($datePattern, $entry, $m)) {
                            continue;
                        }
                        $key = $m[1] . $m[2];
                        if ($bestFile === null || strcmp($key, $bestKey) > 0) {
                            $bestKey = $key;
                            $bestFile = $entry;
                            $bestFolder = $folder;
                            $bestScheduledDir = $scheduled_dir;
                        }
                    }
                }

                if ($bestFile !== null) {
                    $datetime = date_create_from_format('YmdHis', $bestKey);
                    $day = $datetime->format('Y-m-d');
                    $hour = $datetime->format('H:i:s');
                    $base64 = self::processScheduled($bestFile, $bestScheduledDir);
                    $lastscheduled = array(
                        $bestFile,
                        $day . ":1",
                        $hour,
                        $base64,
                        $bestFolder . "_" . $bestFile,
                    );
                }
            }
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $lastscheduled);
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

    // Get the data path parsing freeture configuration file
    public static function getDataPath() {
        $freetureConf = _FREETURE_;
        $dataPath = _FREETURE_DATA_ . _DEFAULT_STATION_CODE_."/";

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
    public static function encodeScheduled($path) {
        if (!file_exists($path)) {
            return "";
        }
        $data = file_get_contents($path);
        $base64 = 'data:image/png;base64,' . base64_encode($data);
        return $base64;
    }

    // Process scheduled fit file, apply watermark and convert image to base64
    public static function processScheduled($file, $data_dir) {
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

        $base64 = self::encodeScheduled($png_named_path);
        shell_exec("rm " . $png_dir . "*.png"); // Clean temporary png files
        return $base64;
    }

    // Get scheduled acquisitions data given starting and ending index and the day directory.
    // If preview enabled, convert images to base64.
    // $day_dir can be either a bare date key (YYYYMMDD) — in which case all
    // station folders for that date are merged — or an explicit folder name.
    public static function getScheduledFiles($start, $end, $day_dir, $enablePreview = false) {
        $base_dir = self::getDataPath();
        $scheduled_files = self::collectScheduledFiles($day_dir);
        $n_day_files = count($scheduled_files);
        $datePattern = '/(\d{8})T(\d{6})/';

        $reply = array();
        $i = 0;
        foreach ($scheduled_files as $row) {
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }

            $file = $row['file'];
            $folder = $row['folder'];
            $scheduled_dir = $base_dir . $folder . "/schedules";

            preg_match($datePattern, $file, $matches);
            $datetime = date_create_from_format('YmdHis', $matches[1] . $matches[2]);
            $day = $datetime->format('Y-m-d');
            $hour = $datetime->format('H:i:s');

            $base64 = $enablePreview ? self::processScheduled($file, $scheduled_dir) : "";
            $reply[] = array($file, $day . ":" . $n_day_files, $hour, $base64, $folder . "_" . $file);
            $i++;
        }
        return $reply;
    }

    // Resolve $day_dir (either an 8-digit date key or an explicit folder name)
    // to the full, timestamp-sorted list of {folder, file} scheduled entries.
    // Each entry is a .fit file whose name contains a YYYYMMDDTHHMMSS marker.
    private static function collectScheduledFiles($day_dir) {
        $base_dir = self::getDataPath();
        if (!is_dir($base_dir)) {
            return array();
        }

        $folders = array();
        if (preg_match('/^\d{8}$/', $day_dir)) {
            $dateKey = $day_dir;
            foreach (scandir($base_dir, SCANDIR_SORT_DESCENDING) as $d) {
                if ('.' === $d || '..' === $d) {
                    continue;
                }
                if (!is_dir($base_dir . $d)) {
                    continue;
                }
                if (strpos($d, $dateKey) !== false) {
                    $folders[] = $d;
                }
            }
        } else {
            $folders[] = $day_dir;
        }

        $datePattern = '/(\d{8})T(\d{6})/';
        $scheduled_files = array();
        foreach ($folders as $folder) {
            $scheduled_dir = $base_dir . $folder . "/schedules";
            if (!is_dir($scheduled_dir)) {
                continue;
            }
            foreach (scandir($scheduled_dir, SCANDIR_SORT_DESCENDING) as $entry) {
                if ('.' === $entry || '..' === $entry) {
                    continue;
                }
                if (substr_compare($entry, '.fit', -4, 4, true) !== 0) {
                    continue;
                }
                if (!preg_match($datePattern, $entry)) {
                    continue;
                }
                $scheduled_files[] = array('folder' => $folder, 'file' => $entry);
            }
        }

        usort($scheduled_files, function ($a, $b) use ($datePattern) {
            preg_match($datePattern, $a['file'], $ma);
            preg_match($datePattern, $b['file'], $mb);
            return strcmp($mb[1] . $mb[2], $ma[1] . $ma[2]);
        });

        return $scheduled_files;
    }

    // Get all days and compute number of scheduled acquisitions in that day.
    // Folders with the same date (regardless of station prefix, e.g.
    // ITLI05_20260504 and LASPEZIA_20260504) are merged into a single row;
    // the row's third column is the bare date key (YYYYMMDD).
    public static function getScheduledDays($start, $end) {
        $data_dir = self::getDataPath();
        error_log("[scheduled/daylist] data_dir='$data_dir' exists=" . (is_dir($data_dir) ? 'yes' : 'no'));
        if (!is_dir($data_dir)) {
            return array();
        }

        $byDate = array();
        $dirs = scandir($data_dir, SCANDIR_SORT_DESCENDING);
        foreach ($dirs as $day_dir) {
            if ('.' === $day_dir || '..' === $day_dir) {
                continue;
            }
            if (!is_dir($data_dir . "/" . $day_dir)) {
                continue;
            }
            if (!preg_match('/\d{8}/', $day_dir, $matches)) {
                error_log("[scheduled/daylist] skip '$day_dir': nessuna data YYYYMMDD nel nome");
                continue;
            }
            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/schedules/*.fit");
            error_log("[scheduled/daylist] '$day_dir': scheduled dir " .
                (is_dir($data_dir . "/" . $day_dir . "/schedules") ? 'presente' : 'ASSENTE') .
                ", fit=" . $n_day_files);
            if ($n_day_files == 0) {
                continue;
            }
            $dateKey = $matches[0];
            if (!isset($byDate[$dateKey])) {
                $byDate[$dateKey] = 0;
            }
            $byDate[$dateKey] += $n_day_files;
        }

        krsort($byDate); // most recent date first

        $reply = array();
        $i = 0;
        foreach ($byDate as $dateKey => $count) {
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }
            $day = date('Y-m-d', strtotime($dateKey));
            $reply[] = array($day, $count, $dateKey);
            $i++;
        }
        return $reply;
    }

    // Compute number of a 2-level directories structure
    public static function getAllDaysFilesCount($path) {
        $n_files = 0;
        if ($handle = opendir($path)) {
            while (false !== ($day = readdir($handle))) {
                $n_files += self::getDirectoryFilesCount($path . $day . "/schedules/*.fit");
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
