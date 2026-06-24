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

        $all = self::getCapturesDays(0, 365); 
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
        $path = $data_dir . $day . "/captures/" . $fit_name;
        return $path;
    }

    // Get last capture in time data.
    // Scans every station/day folder under the data root and picks the file
    // with the most recent timestamp embedded in its name, regardless of which
    // folder holds it.
    public static function GetLastCapture() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $lastcapture = null;

            $base_dir = self::getDataPath();
            if (is_dir($base_dir)) {
                $datePattern = '/(\d{8})T(\d{6})/';
                $bestKey = '';
                $bestFile = null;
                $bestFolder = null;
                $bestCapturesDir = null;

                foreach (scandir($base_dir) as $folder) {
                    if ('.' === $folder || '..' === $folder) {
                        continue;
                    }
                    $captures_dir = $base_dir . $folder . "/captures";
                    if (!is_dir($captures_dir)) {
                        continue;
                    }
                    foreach (scandir($captures_dir) as $entry) {
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
                            $bestCapturesDir = $captures_dir;
                        }
                    }
                }

                if ($bestFile !== null) {
                    $datetime = date_create_from_format('YmdHis', $bestKey);
                    $day = $datetime->format('Y-m-d');
                    $hour = $datetime->format('H:i:s');
                    $base64 = self::processCapture($bestFile, $bestCapturesDir);
                    $lastcapture = array(
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

            $datePattern = '/(\d{8})T(\d{6})/';
            if(preg_match($datePattern, $file, $matches)) {
                $dateStr = $matches[1]; // YYYYMMDD
                $timeStr = $matches[2]; // HHMMSS
                $datetime = date_create_from_format('YmdHis', $dateStr . $timeStr);

                $day = $datetime->format('Y-m-d');
                $hour = $datetime->format('H:i:s');

                $base64 = $enablePreview ? self::processCapture($file, $data_dir) : "";
                $reply[] = array($file, $day . ":" . $n_day_files, $hour, $base64, $day_dir . "_" . $file);
                $i++;
            }
        }
        return $reply;
    }

    /**
     * GET /capture/completeness?dayDir=FOLDER_NAME
     * Verifica che per il giorno selezionato siano presenti tutte le capture attese in
     * base al periodo letto da ACQ_REGULAR_CFG (formato "HHhMMmSSs..."). Ritorna:
     *   { complete, expectedCount, foundCount, periodSeconds, missingCount,
     *     missingRanges: [{start, end, count}], dayDir }
     */
    public static function GetCompleteness($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $dayDir = isset($_GET['dayDir']) ? $_GET['dayDir'] : '';
            error_log("[GetCompleteness/capture] start dayDir='$dayDir'");
            if ($dayDir === '') {
                error_log("[GetCompleteness/capture] ABORT: dayDir empty");
                throw new ApiException("Parametro dayDir mancante.");
            }
            $periodSec    = self::getCapturePeriodSeconds();
            error_log("[GetCompleteness/capture] periodSec=$periodSec");
            $captureDir   = self::getDataPath() . $dayDir . "/captures";
            error_log("[GetCompleteness/capture] captureDir='$captureDir' exists=" . (is_dir($captureDir) ? 'yes' : 'no'));
            $foundSeconds = self::collectCaptureSecondsOfDay($dayDir);
            error_log("[GetCompleteness/capture] foundFiles=" . count($foundSeconds));

            // Giorno corrente UT: gli slot dopo "now" non sono ancora attesi.
            $todayInfo = self::resolveTodayCutoff($dayDir);
            $maxSec    = $todayInfo['maxSec'];

            $report       = self::buildCompletenessReport($foundSeconds, $periodSec, $maxSec);
            $report['dayDir']     = $dayDir;
            $report['inProgress'] = $todayInfo['inProgress'];
            $report['cutoffTime'] = $todayInfo['cutoffTime'];

            // Incrocio con i log freeture: per ogni range mancante annoto la causa
            // piu' verosimile (ERROR/FATAL > WARN) caduta nella finestra estesa
            // attorno al range.
            if (!empty($report['missingRanges']) && preg_match('/(\d{8})/', $dayDir, $dk)) {
                self::lookupErrorCausesForRanges($report['missingRanges'], $dk[1], $periodSec);
            }

            error_log("[GetCompleteness/capture] report expected={$report['expectedCount']} found={$report['foundCount']} missing={$report['missingCount']} ranges=" . count($report['missingRanges']) . " inProgress=" . ($report['inProgress'] ? '1' : '0') . " cutoff=" . $report['cutoffTime']);
        } catch (ApiException $a) {
            error_log("[GetCompleteness/capture] ApiException: " . $a->message);
            return CoreLogic::GenerateErrorResponse($a->message);
        } catch (\Throwable $t) {
            error_log("[GetCompleteness/capture] EXCEPTION " . get_class($t) . ": " . $t->getMessage() . " @ " . $t->getFile() . ":" . $t->getLine());
            return CoreLogic::GenerateErrorResponse("Errore interno: " . $t->getMessage());
        }
        return CoreLogic::GenerateResponse(true, $report);
    }

    // Parsa ACQ_REGULAR_CFG ("HHhMMmSSs[exposure]e..."). Default 600 s (10 min).
    private static function getCapturePeriodSeconds() {
        $freetureConf = _FREETURE_;
        $defaultPeriod = 600;
        if (!file_exists($freetureConf) || !is_file($freetureConf)) {
            return $defaultPeriod;
        }
        foreach (file($freetureConf) as $line) {
            if (!isset($line) || $line === '' || $line[0] === '#' || $line[0] === "\n" || $line[0] === "\t") {
                continue;
            }
            if ((strlen($line) - 1) === substr_count($line, " ")) {
                continue;
            }
            if (self::getKey($line) !== "ACQ_REGULAR_CFG") {
                continue;
            }
            $raw = self::getValue($line);
            if (preg_match('/^\s*(\d+)h(\d+)m(\d+)s/i', $raw, $m)) {
                $sec = ((int) $m[1]) * 3600 + ((int) $m[2]) * 60 + ((int) $m[3]);
                if ($sec > 0) {
                    return $sec;
                }
            }
            return $defaultPeriod;
        }
        return $defaultPeriod;
    }

    // Estrae i secondi-dal-mezzanotte dai filename del giorno selezionato.
    private static function collectCaptureSecondsOfDay($day_dir) {
        $data_dir = self::getDataPath() . $day_dir . "/captures";
        if (!is_dir($data_dir)) {
            return array();
        }
        $datePattern = '/(\d{8})T(\d{6})/';
        $foundSeconds = array();
        foreach (scandir($data_dir) as $file) {
            if ($file === '.' || $file === '..') continue;
            if (substr_compare($file, '.fit', -4, 4, true) !== 0) continue;
            if (!preg_match($datePattern, $file, $m)) continue;
            $h  = (int) substr($m[2], 0, 2);
            $mn = (int) substr($m[2], 2, 2);
            $s  = (int) substr($m[2], 4, 2);
            $foundSeconds[] = $h * 3600 + $mn * 60 + $s;
        }
        return $foundSeconds;
    }

    // Algoritmo di completeness (duplicato da StackApiLogic per disaccoppiare i moduli).
    private static function buildCompletenessReport(array $foundSeconds, $periodSec, $maxSec = 86400) {
        $periodSec = max(1, (int) $periodSec);
        $maxSec    = max(0, min(86400, (int) $maxSec));
        $expected  = (int) floor($maxSec / $periodSec);

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
        $h = (int) floor($sec / 3600);
        $m = (int) floor(($sec % 3600) / 60);
        $s = $sec % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    // Per ogni range in $ranges (modificato in-place) cerca tra i log freeture la
    // causa piu' verosimile del buco. La finestra di ricerca e' [startSec - lookback,
    // endSec] perche' tipicamente il file mancante alle HH:MM non e' stato scritto
    // a causa di un errore avvenuto NEL CICLO PRECEDENTE (quindi prima di HH:MM).
    // Priorita': ERROR/FATAL > WARN.
    private static function lookupErrorCausesForRanges(&$ranges, $dayKey, $periodSec) {
        $stationCode = CoreLogic::GetStationCode();
        $logsDir = _FREETURE_DATA_ . $stationCode . "/logs/";
        if (!is_dir($logsDir)) {
            error_log("[lookupErrorCauses/capture] logs dir absent: $logsDir");
            return;
        }
        $logLines = self::loadDayErrors($logsDir, $dayKey);
        error_log("[lookupErrorCauses/capture] loaded " . count($logLines) . " ERROR/FATAL/WARN lines for dayKey=$dayKey");
        if (empty($logLines)) {
            return;
        }
        $datePrefix  = substr($dayKey, 0, 4) . '-' . substr($dayKey, 4, 2) . '-' . substr($dayKey, 6, 2);
        $lookbackSec = max(120, ((int) $periodSec) * 2);
        foreach ($ranges as &$r) {
            $winStartSec = max(0, $r['startSec'] - $lookbackSec);
            $startTs     = $datePrefix . ' ' . self::fmtHMS($winStartSec);
            $endTs       = $datePrefix . ' ' . self::fmtHMS($r['endSec']);
            $bestErr     = null;
            foreach ($logLines as $line) {
                if (strcmp($line['timestamp'], $startTs) < 0) continue;
                if (strcmp($line['timestamp'], $endTs) > 0)   break; // log ordinati per ts
                $isFatal = ($line['level'] === 'ERROR' || $line['level'] === 'FATAL');
                if ($bestErr === null) {
                    $bestErr = $line;
                } elseif ($isFatal && $bestErr['level'] === 'WARN') {
                    $bestErr = $line;
                }
            }
            if ($bestErr !== null) {
                $r['cause'] = $bestErr;
            }
        }
        unset($r);
    }

    // Legge tutti i .log sotto $logsDir ed estrae le righe ERROR/FATAL/WARN del
    // giorno $dayKey (YYYYMMDD), ordinate per timestamp.
    private static function loadDayErrors($logsDir, $dayKey) {
        $datePrefix = substr($dayKey, 0, 4) . '-' . substr($dayKey, 4, 2) . '-' . substr($dayKey, 6, 2);
        // Accetto sia il formato "YYYY-MM-DD HH:MM:SS; LEVEL; msg" che la variante
        // "YYYY-MM-DD HH:MM:SS [LEVEL] [thread] msg" (log4cpp con altre conversion patterns).
        $patternSemi   = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}); ([A-Z]+); (.*)$/';
        $patternSquare = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) \[([A-Z]+)\] (.*)$/';
        $lines = array();
        foreach (glob($logsDir . "*.log") as $logFile) {
            $thread = pathinfo($logFile, PATHINFO_FILENAME);
            $fh = @fopen($logFile, 'r');
            if (!$fh) continue;
            while (($raw = fgets($fh)) !== false) {
                $raw = rtrim($raw, "\r\n");
                if ($raw === '') continue;
                if (preg_match($patternSemi, $raw, $m)) {
                    // ok
                } elseif (preg_match($patternSquare, $raw, $m)) {
                    // ok
                } else {
                    continue;
                }
                if (strpos($m[1], $datePrefix) !== 0) continue;
                $lvl = $m[2];
                if ($lvl !== 'ERROR' && $lvl !== 'FATAL' && $lvl !== 'WARN') continue;
                $lines[] = array(
                    'timestamp' => $m[1],
                    'level'     => $lvl,
                    'thread'    => $thread,
                    'message'   => $m[3],
                );
            }
            fclose($fh);
        }
        usort($lines, function ($a, $b) { return strcmp($a['timestamp'], $b['timestamp']); });
        return $lines;
    }

    // Se la dateKey YYYYMMDD del dayDir coincide con la data UT corrente, il giorno
    // e' "in corso": gli slot dopo l'ora attuale non sono ancora attesi.
    private static function resolveTodayCutoff($dayDir) {
        $info = array('inProgress' => false, 'maxSec' => 86400, 'cutoffTime' => '24:00:00');
        if (!preg_match('/(\d{8})/', $dayDir, $m)) {
            return $info;
        }
        $dayKey = $m[1];
        $todayKey = gmdate('Ymd');
        if ($dayKey !== $todayKey) {
            return $info;
        }
        $h  = (int) gmdate('H');
        $mn = (int) gmdate('i');
        $s  = (int) gmdate('s');
        $info['inProgress'] = true;
        $info['maxSec']     = $h * 3600 + $mn * 60 + $s;
        $info['cutoffTime'] = sprintf('%02d:%02d:%02d', $h, $mn, $s);
        return $info;
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
           

            $datePattern = '/\d{4}\d{2}\d{2}/';
            if(preg_match($datePattern, $day_dir, $matches)) {
                $datetime = date_create($matches[0]);
                $day = date('Y-m-d', strtotime($matches[0]));
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
