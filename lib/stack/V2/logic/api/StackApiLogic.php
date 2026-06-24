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
        $iTotal = 0;
        $pageNumber = 0;

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $day_dir = $_GET['dayDir'];
            $enable_preview = $_GET['enablePreview'] === 'true' ? true : false;

            $iTotal = count(self::collectStacksFiles($day_dir));
            $reply = self::getStacksFiles($iDisplayStart, $iDisplayStart + $iDisplayLength - 1, $day_dir, $enable_preview);

            if ($iDisplayLength > 0 && $iDisplayStart >= $iDisplayLength) {
                $pageNumber = intdiv($iDisplayStart, $iDisplayLength);
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

    /**
     * GET /stack/completeness?dayDir=YYYYMMDD|FOLDER_NAME
     * Verifica che per il giorno selezionato siano presenti tutti gli stack attesi in
     * base a STACK_TIME (un file ogni STACK_TIME secondi, 24h). Ritorna:
     *   { complete, expectedCount, foundCount, periodSeconds, missingCount,
     *     missingRanges: [{start, end, count}], dateKey, dayDir }
     * I missingRanges raggruppano slot vuoti contigui (es. "12:00:00 - 13:30:59").
     */
    public static function GetCompleteness($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $dayDir = isset($_GET['dayDir']) ? $_GET['dayDir'] : '';
            if ($dayDir === '') {
                throw new ApiException("Parametro dayDir mancante.");
            }
            $periodSec = self::getStackPeriodSeconds();
            $files     = self::collectStacksFiles($dayDir);
            $report    = self::buildStackCompletenessReport($files, $periodSec);
            $report['dayDir'] = $dayDir;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $report);
    }

    // Legge STACK_TIME (secondi) da configuration.cfg. Default 60 se mancante.
    private static function getStackPeriodSeconds() {
        $freetureConf = _FREETURE_;
        $stackTime = 60;
        if (file_exists($freetureConf) && is_file($freetureConf)) {
            foreach (file($freetureConf) as $line) {
                if (!isset($line) || $line === '' || $line[0] === '#' || $line[0] === "\n" || $line[0] === "\t") {
                    continue;
                }
                if ((strlen($line) - 1) === substr_count($line, " ")) {
                    continue;
                }
                if (self::getKey($line) === "STACK_TIME") {
                    $v = (int) self::getValue($line);
                    if ($v > 0) {
                        $stackTime = $v;
                    }
                }
            }
        }
        return $stackTime;
    }

    /**
     * Estrae HHMMSS dai filename, calcola gli slot mancanti, raggruppa contigui in range.
     */
    private static function buildStackCompletenessReport(array $stackFiles, $periodSec) {
        $datePattern = '/(\d{8})T(\d{6})/';
        $foundSeconds = array();
        foreach ($stackFiles as $row) {
            if (preg_match($datePattern, $row['file'], $m)) {
                $h = (int) substr($m[2], 0, 2);
                $mn = (int) substr($m[2], 2, 2);
                $s = (int) substr($m[2], 4, 2);
                $foundSeconds[] = $h * 3600 + $mn * 60 + $s;
            }
        }
        return self::buildCompletenessReport($foundSeconds, $periodSec);
    }

    // Algoritmo di completeness condiviso tra stack e capture (qui duplicato per non
    // accoppiare i due moduli a un'utility esterna).
    private static function buildCompletenessReport(array $foundSeconds, $periodSec) {
        $periodSec = max(1, (int) $periodSec);
        $expected  = (int) floor(86400 / $periodSec);

        $hasFile = array_fill(0, $expected, false);
        foreach ($foundSeconds as $sec) {
            if ($sec < 0 || $sec >= 86400) continue;
            $slot = (int) floor($sec / $periodSec);
            if ($slot >= $expected) $slot = $expected - 1;
            $hasFile[$slot] = true;
        }

        $missingRanges = array();
        $runStart = null;
        for ($k = 0; $k < $expected; $k++) {
            if (!$hasFile[$k]) {
                if ($runStart === null) $runStart = $k;
            } elseif ($runStart !== null) {
                $missingRanges[] = self::makeMissingRange($runStart, $k - 1, $periodSec);
                $runStart = null;
            }
        }
        if ($runStart !== null) {
            $missingRanges[] = self::makeMissingRange($runStart, $expected - 1, $periodSec);
        }

        $missingCount = 0;
        foreach ($missingRanges as $r) { $missingCount += $r['count']; }

        $foundCount = $expected - $missingCount;
        if ($foundCount < 0) $foundCount = 0;

        return array(
            'complete'      => ($missingCount === 0),
            'expectedCount' => $expected,
            'foundCount'    => $foundCount,
            'periodSeconds' => $periodSec,
            'missingCount'  => $missingCount,
            'missingRanges' => $missingRanges,
        );
    }

    private static function makeMissingRange($kStart, $kEnd, $periodSec) {
        $startSec = $kStart * $periodSec;
        $endSec   = ($kEnd + 1) * $periodSec - 1;
        if ($endSec > 86399) $endSec = 86399;
        return array(
            'startSec' => $startSec,
            'endSec'   => $endSec,
            'start'    => self::fmtHMS($startSec),
            'end'      => self::fmtHMS($endSec),
            'count'    => $kEnd - $kStart + 1,
        );
    }

    private static function fmtHMS($sec) {
        $h  = (int) floor($sec / 3600);
        $m  = (int) floor(($sec % 3600) / 60);
        $s  = $sec % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    public static function jGetDaysListDatatable($request) {
        $reply = null;
        $iDisplayStart = 1;
        $directory = self::getDataPath() . "*";
        $iTotal = count(self::getStacksDays(0, 365));
		
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $iDisplayStart = intval($_GET['iDisplayStart']);
            $iDisplayLength = intval($_GET['iDisplayLength']);
            $reply = self::getStacksDays($iDisplayStart, $iDisplayStart + $iDisplayLength - 1);
			
			if (!empty($array)) {
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
		
        
          //Ordering
          if (isset($_GET['iSortCol_0'])) {
          if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == 'true') {
          $i = $_GET['iSortCol_0'];
          $sort = array_column($reply, $i);
          if ($_GET['sSortDir_' . $i] === 'asc') {
          array_multisort($sort, SORT_ASC, $reply);
          } else {
          array_multisort($sort, SORT_DESC, $reply);}}}
         
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

        $all = self::getStacksDays(0, 365); 
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
    // File info given is 
    public static function GetFitFile($file) {
        $data_dir = self::getDataPath();
        $file_info = explode("_", $file);
        $day = $file_info[0] . "_" . $file_info[1];
        $fit_name = $file_info[2] . "_" . $file_info[3] . "_" . $file_info[4];
        $path = $data_dir . $day . "/stacks/" . $fit_name;
        return $path;
    }

    // Get last stack in time data.
    // Scans every station/day folder under the data root and picks the file
    // with the most recent timestamp embedded in its name, regardless of which
    // folder holds it.
    public static function GetLastStack() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $laststack = null;

            $base_dir = self::getDataPath();
            if (is_dir($base_dir)) {
                $datePattern = '/(\d{8})T(\d{6})/';
                $bestKey = '';
                $bestFile = null;
                $bestFolder = null;
                $bestStacksDir = null;

                foreach (scandir($base_dir) as $folder) {
                    if ('.' === $folder || '..' === $folder) {
                        continue;
                    }
                    $stacks_dir = $base_dir . $folder . "/stacks";
                    if (!is_dir($stacks_dir)) {
                        continue;
                    }
                    foreach (scandir($stacks_dir) as $entry) {
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
                            $bestStacksDir = $stacks_dir;
                        }
                    }
                }

                if ($bestFile !== null) {
                    $datetime = date_create_from_format('YmdHis', $bestKey);
                    $day = $datetime->format('Y-m-d');
                    $hour = $datetime->format('H:i:s');
                    $base64 = self::processStack($bestFile, $bestStacksDir);
                    $laststack = array(
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
        $stationName = "TELESCOPE";

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
        $png_named_path = $png_dir .$named_png_name;

        shell_exec("convert $png_path -gravity NorthWest -pointsize 22 -fill white -annotate 0 \"$stamp\" $png_named_path");
        
        $base64 = self::encodeStack($png_named_path);
        shell_exec("rm " . $png_dir . "*.png"); // Clean temporary png files
        return $base64;
    }

    // Get stacks data given starting and ending index and the day directory
    // If preview enabled, convert images to base64.
    // $day_dir can be either a bare date key (YYYYMMDD) — in which case all
    // station folders for that date are merged — or an explicit folder name.
    public static function getStacksFiles($start, $end, $day_dir, $enablePreview = false) {
        $base_dir = self::getDataPath();
        $stack_files = self::collectStacksFiles($day_dir);
        $n_day_files = count($stack_files);
        $datePattern = '/(\d{8})T(\d{6})/';

        $reply = array();
        $i = 0;
        foreach ($stack_files as $row) {
            if ($i < $start) {
                $i++;
                continue;
            }
            if ($i > $end) {
                return $reply;
            }

            $file = $row['file'];
            $folder = $row['folder'];
            $stacks_dir = $base_dir . $folder . "/stacks";

            preg_match($datePattern, $file, $matches);
            $datetime = date_create_from_format('YmdHis', $matches[1] . $matches[2]);
            $day = $datetime->format('Y-m-d');
            $hour = $datetime->format('H:i:s');

            $base64 = $enablePreview ? self::processStack($file, $stacks_dir) : "";
            $reply[] = array($file, $day . ":" . $n_day_files, $hour, $base64, $folder . "_" . $file);
            $i++;
        }
        return $reply;
    }

    // Resolve $day_dir (either an 8-digit date key or an explicit folder name)
    // to the full, timestamp-sorted list of {folder, file} stack entries.
    // Each entry is a .fit file whose name contains a YYYYMMDDTHHMMSS marker.
    private static function collectStacksFiles($day_dir) {
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
        $stack_files = array();
        foreach ($folders as $folder) {
            $stacks_dir = $base_dir . $folder . "/stacks";
            if (!is_dir($stacks_dir)) {
                continue;
            }
            foreach (scandir($stacks_dir, SCANDIR_SORT_DESCENDING) as $entry) {
                if ('.' === $entry || '..' === $entry) {
                    continue;
                }
                if (substr_compare($entry, '.fit', -4, 4, true) !== 0) {
                    continue;
                }
                if (!preg_match($datePattern, $entry)) {
                    continue;
                }
                $stack_files[] = array('folder' => $folder, 'file' => $entry);
            }
        }

        usort($stack_files, function ($a, $b) use ($datePattern) {
            preg_match($datePattern, $a['file'], $ma);
            preg_match($datePattern, $b['file'], $mb);
            return strcmp($mb[1] . $mb[2], $ma[1] . $ma[2]);
        });

        return $stack_files;
    }

    // Get all days and compute number of stack in that day.
    // Folders with the same date (regardless of station prefix, e.g.
    // ITLI05_20260504 and LASPEZIA_20260504) are merged into a single row;
    // the row's third column is the bare date key (YYYYMMDD).
    public static function getStacksDays($start, $end) {
        $data_dir = self::getDataPath();
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
                continue;
            }
            $n_day_files = self::getDirectoryFilesCount($data_dir . "/" . $day_dir . "/stacks/*.fit");
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
