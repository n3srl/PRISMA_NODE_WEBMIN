<?php

/**
 *
 * @author: N3 S.r.l.
 */
class DockerApiLogic {

    public static function Save($request) {

        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];
            $ob->name = $tmp["name"];

            $res = self::sshContainerRestart($ob);
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

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];
            $ob->name = $tmp["name"];

            $res = self::sshContainerRestart($ob);
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

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = DockerLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DockerLogic::Erase($ob);
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

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = DockerLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DockerLogic::Delete($ob);
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
            $ob = self::sshContainerGet($id);
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = DockerLogic::GetList();
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
            $codes = DockerFactory::GetListFilter($columnName, $_GET['term']);
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
                /*                 * * ESEMPIO **
                  case "created_by":
                  $foreignKey = end(DockerFactory::GetForeignKeyParams($columnName));
                  $codes = DockerFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,array("id","CONCAT(last_name, ' ', first_name) AS full_name"),$_GET['term']);
                  $data = new stdClass();
                  foreach ($codes as $code){
                  $obj = new stdClass();
                  $obj->id = $code->id;
                  $obj->text = $code->full_name;
                  $results[] = $obj;
                  }
                  break;
                 */
                default:
                    $foreignKey = end(DockerFactory::GetForeignKeyParams($columnName));
                    $codes = DockerFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
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

    public static function GetListDatatable() {
        $reply = self::sshContainerList();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => 0,
            "iTotalRecords" => count($reply),
            "iTotalDisplayRecords" => count($reply),
            "aaData" => $reply
        );

        return $output;
    }

    //Access SSH container and get Container list
    public static function sshContainerList() {
        $list = array();
        $i = 0;
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            // Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                $sshCmd = "docker container ls -a --format \"{{.Names}} {{.Image}} {{.Status}}\"";
                // Execute command to get containers
                //https://www.baeldung.com/ops/docker-list-containers
                $mylevel = CoreLogic::VerifyPermission();
                if(intval($mylevel) >= 2) {
                    $sshCmd = $sshCmd . " | awk '$1 ~ /^(freeture|prisma-orma)$/'";
                } else {
                    $sshCmd = $sshCmd . " | awk '$1 ~ /^(freeture)$/'";
                }
                $stream = ssh2_exec($session, $sshCmd);

                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $text = stream_get_contents($stream_out);

                // Parse Containers
                $containers = explode("\n", $text);
                foreach ($containers as $container) {

                    // Parse Container
                    if ($i < count($containers) - 1) {

                        $conta = explode(" ", $container);

                        $list[] = array($conta[0], $conta[1], "empty", $conta[2], "no date", $conta[0], $conta[0], $i);

                        $i++;
                    }
                }
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }

        return $list;
    }

    //Access SSH container and get a single Container
    public static function sshContainerGet($id) {
        $list = array();
        $i = 0;
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                //Execute command to get containers
                //https://www.baeldung.com/ops/docker-list-containers
                $stream = ssh2_exec($session, "sudo docker container ls -a --format \"{{.Names}} {{.Image}} {{.Status}}\"");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $text = stream_get_contents($stream_out);

                //Parse Containers
                $containers = explode("\n", $text);
                foreach ($containers as $container) {

                    //Parse Container
                    if ($i < count($containers) - 1) {

                        if ("$i" === $id) {
                            $conta = explode(" ", $container);
                            $dc = new Docker();
                            $dc->id = $id;
                            $dc->name = $conta[0];
                            $dc->image = $conta[1];
                            $dc->status = $conta[2];
                            return $dc;
                        }


                        //$list[] = array($conta[0], $conta[1], "empty", $conta[2], "no date", 0, $i);

                        $i++;
                    }
                }
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }

        return false;
    }

    //Access SSH container and get a single Container
    public static function sshContainerStart($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker start " . $ob);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    //Access SSH container and get a single Container
    public static function sshContainerRestart($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $cmd = "docker inspect -f '{{.State.Running}}' $ob | grep -q 'true' && docker restart $ob";
                $stream = ssh2_exec($session, $cmd);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    //Access SSH container and get a single Container
    public static function sshContainerStop($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker stop " . $ob);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    public static function sshFreetureLog() {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        $cmd_out = "";
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker logs --tail 100 freeture");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
                $result = true;
            }
            
        }
        unset($session);
        return array(
            "res" => $result,
            "data" => $cmd_out
        );
    }

    public static function FreetureLog() {

        try {

            $Person = CoreLogic::VerifyPerson();

            $res = self::sshFreetureLog();
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res['res'], $res['data']);
    }

    // Read & filter freeture log4cpp output files for the current station.
    // $from / $to are "YYYY-MM-DD HH:MM:SS" strings (or empty for open bound).
    // $levels is an array of uppercase level names (empty = no level filter).
    // Returns at most LOG_FILTER_LIMIT records, sorted by timestamp ascending,
    // each shaped as {timestamp, level, thread, message}.
    const LOG_FILTER_LIMIT = 5000;

    public static function FilteredFreetureLogs($from, $to, $levels) {
        try {
            $Person = CoreLogic::VerifyPerson();

            $stationCode = CoreLogic::GetStationCode();
            $logsDir = _FREETURE_DATA_ . $stationCode . "/logs/";

            $result = self::readFilteredLogs($logsDir, $from, $to, $levels);
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    private static function readFilteredLogs($logsDir, $from, $to, $levels) {
        $out = array('records' => array(), 'truncated' => false, 'total' => 0);
        if (!is_dir($logsDir)) {
            return $out;
        }

        $fromTs = (is_string($from) && $from !== '') ? strtotime($from) : null;
        $toTs   = (is_string($to)   && $to   !== '') ? strtotime($to)   : null;
        if ($fromTs === false) { $fromTs = null; }
        if ($toTs   === false) { $toTs   = null; }

        $levelSet = array();
        if (is_array($levels)) {
            foreach ($levels as $lvl) {
                $lvl = strtoupper(trim($lvl));
                if ($lvl !== '') {
                    $levelSet[$lvl] = true;
                }
            }
        }
        $filterLevels = !empty($levelSet);

        $linePattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}); ([A-Z]+); (.*)$/';
        $records = array();

        foreach (glob($logsDir . "*.log") as $filePath) {
            $thread = pathinfo($filePath, PATHINFO_FILENAME);
            $fh = @fopen($filePath, 'r');
            if (!$fh) {
                continue;
            }
            while (($line = fgets($fh)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line === '' || !preg_match($linePattern, $line, $m)) {
                    continue;
                }
                $ts = strtotime($m[1]);
                if ($fromTs !== null && $ts < $fromTs) {
                    continue;
                }
                if ($toTs !== null && $ts > $toTs) {
                    continue;
                }
                if ($filterLevels && !isset($levelSet[$m[2]])) {
                    continue;
                }
                $records[] = array(
                    'timestamp' => $m[1],
                    'level'     => $m[2],
                    'thread'    => $thread,
                    'message'   => $m[3],
                );
            }
            fclose($fh);
        }

        usort($records, function ($a, $b) {
            $c = strcmp($a['timestamp'], $b['timestamp']);
            return ($c !== 0) ? $c : strcmp($a['thread'], $b['thread']);
        });

        $out['total'] = count($records);
        if ($out['total'] > self::LOG_FILTER_LIMIT) {
            $records = array_slice($records, -self::LOG_FILTER_LIMIT);
            $out['truncated'] = true;
        }
        $out['records'] = $records;
        return $out;
    }

}
