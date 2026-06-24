<?php
/**
*
* @author: N3 S.r.l.
*/

class CameraLogic
{
	public static function List() {

        $out = CameraLogic::ExecuteArvCommand();
        return $out;
	}

    public static function Reset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceReset");
        return $out;
	}

    public static function FactoryReset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceFactoryReset");
        return $out;
	}

    public static function Features() {

        $out = CameraLogic::ExecuteArvCommand("features");
        return $out;
	}

    public static function Values() {

        $out = CameraLogic::ExecuteArvCommand("values");
        return $out;
	}
    
    public static function Cmd($cmd) {

        $out = CameraLogic::ExecuteArvCommand($cmd);
        return $out;
	}

    public static function Bounds($cmd) {

        $out = CameraLogic::GetBounds($cmd);
        return $out;
	}

    /**
     * Ritorna informazioni hardware della camera SENZA interrogarla:
     *   - "configured": chiavi camera-rilevanti lette da configuration.cfg
     *     (statiche, sempre disponibili).
     *   - "hardware":   vendor/model/firmware/serial/ip/aravis-version estratti
     *     dai log freeture (sono righe scritte all'avvio di freeture quando
     *     enumera la camera). Robusto: non disturba l'acquisizione.
     */
    public static function HwInfo() {
        $hardware   = self::detectHardwareFromLogs();
        $configured = self::readConfiguredCameraKeys();
        return array(
            "res"  => true,
            "data" => array(
                "hardware"   => $hardware,
                "configured" => $configured,
            ),
        );
    }

    // Legge da configuration.cfg le chiavi di interesse per identificare la camera.
    private static function readConfiguredCameraKeys() {
        $keysMap = array(
            'CAMERA'        => 'camera',
            'INSTRUME'      => 'instrument',
            'TELESCOP'      => 'telescope',
            'CAMERA_ID'     => 'cameraId',
            'ACQ_FORMAT'    => 'format',
            'ACQ_RES_SIZE'  => 'resolution',
            'ACQ_FPS'       => 'fps',
        );
        $out = array();
        foreach ($keysMap as $alias) { $out[$alias] = null; }

        $freetureConf = _FREETURE_;
        if (!file_exists($freetureConf) || !is_file($freetureConf)) {
            return $out;
        }
        foreach (file($freetureConf) as $line) {
            if (!isset($line) || $line === '' || $line[0] === '#' || $line[0] === "\n" || $line[0] === "\t") {
                continue;
            }
            if (strpos($line, '=') === false) continue;
            $parts = explode('=', $line, 2);
            $key   = trim($parts[0]);
            if (!isset($keysMap[$key])) continue;
            $val = $parts[1];
            $hashPos = strpos($val, '#'); // strip inline comments
            if ($hashPos !== false) $val = substr($val, 0, $hashPos);
            $out[$keysMap[$key]] = trim($val);
        }
        return $out;
    }

    // Estrae vendor/model/firmware/serial/ip/aravis dai log freeture, prendendo
    // sempre l'ultima occorrenza (= dopo l'ultimo restart). Non disturba la camera.
    private static function detectHardwareFromLogs() {
        $out = array(
            'vendor'     => null,
            'model'      => null,
            'firmware'   => null,
            'serial'     => null,
            'ip'         => null,
            'aravis'     => null,
            'lastSeenAt' => null,
        );

        $stationCode = CoreLogic::GetStationCode();
        $logsDir     = _FREETURE_DATA_ . $stationCode . "/logs/";
        if (!is_dir($logsDir)) {
            return $out;
        }

        $patterns = array(
            'vendor'   => '/(?:DeviceVendorName|vendor(?:\s+name)?)\s*[:=]\s*([A-Za-z0-9_.\-\s]+?)\s*(?:[\[\]\|;,]|$)/i',
            'model'    => '/(?:DeviceModelName|model(?:\s+name)?)\s*[:=]\s*([A-Za-z0-9_.\-\s]+?)\s*(?:[\[\]\|;,]|$)/i',
            'firmware' => '/(?:DeviceFirmwareVersion|firmware(?:\s+version)?)\s*[:=]\s*([A-Za-z0-9_.\-]+)/i',
            'serial'   => '/(?:DeviceSerialNumber|serial(?:\s+number)?|s\/n)\s*[:=]\s*([A-Za-z0-9_.\-]+)/i',
            'aravis'   => '/aravis(?:\s+library)?\s+version\s*[:=]?\s*([0-9][A-Za-z0-9_.\-]*)/i',
            // IP riconosciuto SOLO con contesto esplicito: senza, un firmware tipo
            // "1.101.0.0" verrebbe scambiato per IP (e' un quad-ottetto valido).
            'ip'       => '/(?:camera\s*ip|device\s*ip|cam\s*ip|gevdeviceaddress|gevcurrentip(?:configuration|address)?|host\s*address|ip\s*address|connecting\s*to)\b[^0-9]{0,30}?(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/i',
        );
        // Riga di log freeture: "YYYY-MM-DD HH:MM:SS [LEVEL] [thread] msg" o
        // "YYYY-MM-DD HH:MM:SS; LEVEL; msg".
        $tsPattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';

        $maxBytes = 8 * 1024 * 1024; // se un log e' enorme, leggo solo gli ultimi 8 MB
        $files = glob($logsDir . "*.log");
        // Preferenze: ACQ_THREAD.log per primo, e' dove freeture logga le info camera.
        usort($files, function ($a, $b) {
            $aIsAcq = (stripos(basename($a), 'ACQ') !== false) ? 1 : 0;
            $bIsAcq = (stripos(basename($b), 'ACQ') !== false) ? 1 : 0;
            if ($aIsAcq !== $bIsAcq) return $bIsAcq - $aIsAcq;
            return strcmp($a, $b);
        });

        foreach ($files as $logFile) {
            $fh = @fopen($logFile, 'rb');
            if ($fh === false) continue;
            $size = filesize($logFile);
            if ($size > $maxBytes) {
                @fseek($fh, $size - $maxBytes);
                @fgets($fh); // scarta linea parziale
            }
            $lastTs = null;
            while (($line = fgets($fh)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line === '') continue;
                if (preg_match($tsPattern, $line, $tm)) {
                    $lastTs = $tm[1];
                }
                foreach ($patterns as $key => $regex) {
                    if (preg_match($regex, $line, $mm)) {
                        $val = trim($mm[1]);
                        if ($val === '') continue;
                        if ($key === 'ip') {
                            // Validazione stretta: deve essere un IP reale, no firmware-like.
                            if (filter_var($val, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) continue;
                            if ($val === '0.0.0.0' || strpos($val, '127.') === 0) continue;
                            // Salta righe firmware (anche se hanno il contesto "ip" che non
                            // dovrebbero, e' un cinturone di sicurezza in piu').
                            if (stripos($line, 'firmware') !== false) continue;
                        }
                        $out[$key] = $val;
                        if ($lastTs !== null) {
                            $out['lastSeenAt'] = $lastTs;
                        }
                    }
                }
            }
            fclose($fh);
            // Se ho riempito tutti i campi principali, posso fermarmi.
            if ($out['vendor'] && $out['model'] && $out['firmware'] && $out['serial']) {
                break;
            }
        }
        return $out;
    }

    public static function CanRunCalibration() {

        $can = self::CanCalibrate();

        return array(
            "res" => true,
            "data" => $can
        );
	}

    private static function ExecuteArvCommand($cmd = "") {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && $cmd != "")
        {
            $host = '-a '.$_POST['ip'];
        }
        

        $command = "arv-tool-0.8 $host $cmd";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
	}

    private static function GetBounds() {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && isset($_POST['camera']))
        {
            $host = $_POST['ip']." ".$_POST['camera'];
        }
        

        $command = "python3 /home/prisma/SETUP/bounds.py $host";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
	}

    public static function CanCalibrate()
    {
        if(file_exists("/var/www/html/calibration/calibrationstarted.txt")) return false;
        return true;
    }

    public static function Calibration() {

        if(!self::CanCalibrate())
        {
            return array(
                "res" => true,
                "data" => _("Una calibrazione e gia in corso")
            );
        }

        DockerApiLogic::sshContainerStop("freeture");
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";
        $step = $_POST['step'];

        $command_count = $_POST['maxGain'] - $_POST['minGain'];
        $cam = $_POST['camera'];

        $now = date("dmY", time());
        $exp = $_POST['exposure'];
        $cam = 1;

        $v1 = "-v /prismadata/freeture-conf/configuration.cfg:/usr/local/share/freeture/configuration.cfg";
        $v2 = "-v /prismadata/freeture-conf/calibration:/usr/local/share/freeture/calibration/";
        $image = "n3srl/freeture13";

        if(isset($_POST['image']))
        {
            $image = $_POST['image'];
        }

        $cmd_seq = array();
        
        for($gain = $_POST['minGain'];$gain <= $_POST['maxGain']; $gain += $step)
        {
            
            $params = "-m 4 -e $exp -g $gain --id $cam --fits --savepath /usr/local/share/freeture/calibration/";
            $name = "calibration-g".$gain."e".$exp."-".$now;
            $cmd = "docker run --rm --network host $v1 $v2 $image $params --filename  $name";

            $cmd_seq[] = $cmd;
        }

        
        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, "touch /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "chmod +x /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "touch /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                foreach($cmd_seq as $command)
                {
                    $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                    stream_set_blocking($stream, true);
                    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                    $cmd_out = stream_get_contents($stream_out);
                }

                // Now create zip
            }
        }

        

        unset($session);

        

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {

                $command = "docker exec prisma-orma chown -R 1000:www-data /usr/local/share/freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "docker exec prisma-orma chmod -R 770 /freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "cd /prismadata/freeture-conf/calibration/ && zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip .";
                //$command = "zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip /prismadata/freeture-conf/calibration/";
                $stream = ssh2_exec($session,"echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
                $command = "rm -rf /prismadata/freeture-conf/calibration";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "docker start freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm -rf /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "nohup /home/prisma/calibration.sh > out.txt";
                $stream = ssh2_exec($session, $command);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
            }
        }

        return array(
            "res" => true,
            "data" => _("Calibrazione iniziata, presto disponibile")
        );

	}

    public static function GetCameraCalibrations()
    {
        $path = _CALIBRATION_PATH_;
        $files = glob($path. "/*");

        $count = count($files);

        if($count == 0)
        {
            $data = _("<tr><td>Nessuna calibrazione recente</td></tr>");
        } else 
        {
            $data = "";
            foreach($files as $f)
            {
                
                $calibration = basename($f);

                if($calibration == "calibrationstarted.txt") continue;  

                $date = explode("-", $calibration)[2];
                $date = substr($date, 0, -4);
                $day = substr($date, 0, 2);
                $month = substr($date, 2, 2);
                $year = substr($date, 4, 4);
                $date = $day . "/" . $month . "/" . $year;  

                $data .= "<tr><td>$calibration</td><td>$date</td><td><a style = 'font-size:22px;color:black; text-decoration:none' href = '/calibration/$calibration'><i class='fa fa-download'></i></a></td><td><span name = '$calibration' class = 'calibration_delete'><i style = 'cursor:pointer;font-size:22px' class='fa fa-trash'></i></span></td></tr>";
                
            }
        }
        

        return array(
            "res" => true,
            "data" => $data
        );
    }

    public static function DeleteCalibration($request)
    {

        $calibration = $request->query->get('calibration');

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";

        $command = "rm /prismadata/orma-src/calibration/$calibration";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
    }

}

