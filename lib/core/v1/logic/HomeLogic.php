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