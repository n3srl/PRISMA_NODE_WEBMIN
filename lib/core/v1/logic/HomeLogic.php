<?php
    
    class HomeLogic {
    //controllo connessione ad internet 
    public static function checkInternet() {
        $Home = CoreLogic::VerifyPerson();
        if (@fsockopen('www.google.com', 80)) {
        fclose(@fsockopen('www.google.com', 80)); 
        return true; 
    }
    return false; 
    }
    
    //controllo presenza file mask
    public static function checkMask() {
        $Home = CoreLogic::VerifyPerson();
        if (!file_exists(CoreLogic::GetMaskPath())) { 
            return false;
        }else{
            return true;
        } 
    }

    //controllo presenza file di configurazione
    public static function checkConfig() {
        $freetureConf = _FREETURE_;
        $Home = CoreLogic::VerifyPerson();
        if (!file_exists(_FREETURE_)) {
            return false;
        }else{
            return true;
        }
    }

    // Inspect the freeture container on the docker host and return its
    // image string (e.g. "freeture:v15"), or null if SSH/docker fails.
    public static function getFreetureContainerImage() {
        $session = @ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        if (!$session) {
            return null;
        }
        if (!@ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
            unset($session);
            return null;
        }
        $stream = @ssh2_exec($session, "docker inspect freeture --format='{{.Config.Image}}' 2>/dev/null");
        if (!$stream) {
            unset($session);
            return null;
        }
        stream_set_blocking($stream, true);
        $out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $image = trim((string) stream_get_contents($out));
        unset($session);
        return $image !== '' ? $image : null;
    }

    // Heuristic on the image tag to identify the major freeture version.
    // Returns 'v14', 'v15' or null when undeterminable. Accepts tags like
    // "v15", "15", "15.0.1", "1.5", "1.5.0".
    public static function detectFreetureMajor($image) {
        if (!is_string($image) || $image === '') {
            return null;
        }
        $colon = strrpos($image, ':');
        $tag = $colon !== false ? substr($image, $colon + 1) : $image;
        if (preg_match('/^v?15(?:\.|$)/', $tag)) { return 'v15'; }
        if (preg_match('/^v?14(?:\.|$)/', $tag)) { return 'v14'; }
        if (preg_match('/^1\.5(?:\.|$)/', $tag)) { return 'v15'; }
        if (preg_match('/^1\.4(?:\.|$)/', $tag)) { return 'v14'; }
        return null;
    }

    // Parse configuration.cfg and return the list of TEMPERATURE_* keys that
    // are missing (i.e. not present at all). On v15 containers all 9 keys
    // should be defined.
    public static function getMissingTemperatureParams() {
        $required = array(
            'TEMPERATURE_OVERHEAT_CONTROL_ENABLED',
            'TEMPERATURE_THRESHOLD',
            'TEMPERATURE_HYSTERESIS',
            'TEMPERATURE_WAIT',
            'TEMPERATURE_SAMPLE_RATE',
            'TEMPERATURE_POLICY_ENABLED',
            'TEMPERATURE_POLICY',
            'TEMPERATURE_POLICY_PARAMETER_1',
            'TEMPERATURE_POLICY_PARAMETER_2',
        );
        $conf = _FREETURE_;
        if (!file_exists($conf) || !is_file($conf)) {
            return $required;
        }
        $present = array();
        foreach (file($conf) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (strpos($line, '=') === false) {
                continue;
            }
            $parts = explode('=', $line, 2);
            $key = trim($parts[0]);
            if (in_array($key, $required, true)) {
                $present[] = $key;
            }
        }
        return array_values(array_diff($required, $present));
    }




//Funzioni per mask:

public static function getMaskPath() {
    $freetureConf = _FREETURE_;
    $path = "";
    if (file_exists($freetureConf) && is_file($freetureConf)) {
        $contents = file($freetureConf);

        //Parse config file line by line
        foreach ($contents as $line) {

            if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                    (strlen($line) - 1) !== substr_count($line, " ")) {
                if (self::getKey($line) === "ACQ_MASK_PATH") {
                    $path = self::getValue($line);
                }
            }
        }
    }
    return $path;
}

 // Get the value from the line
 public static function getValue(String $raw) {
    $value1 = explode("=", $raw)[1];
    if (!is_null($value1))
    {
        return trim(self::cleanComments($value1));
    }
    
    return "";
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

}

    ?>