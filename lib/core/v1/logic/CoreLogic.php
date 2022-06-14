<?php

class CoreLogic {
    
    public static function CheckSession() {
        if (isset($_SESSION["person_id"])) {
            return true;
        }
        return false;
    }

    public static function SetSession($person_id) {
        $_SESSION["person_id"] = $person_id;
    }

    public static function GetPersonLogged() {
        $person = null;
        if (isset($_SESSION["person_id"])) {
            $person_id = $_SESSION["person_id"];
            $person = self::getUser($person_id);
        }
        return $person;
    }

    public static function VerifyPermission() {
        $person = self::GetPersonLogged();
        return $person->level; // 1 se Admin, 0 se Agent
    }

    public static function VerifyPerson($verifyPermission = false) {
        $Person = self::GetPersonLogged();
        if ($verifyPermission) {
            /* Metodo pensato per introdurre anche le parti di licenza della person */
            self::VerifyPermission();
        }

        if (empty($Person)) {
            throw new ApiException(ApiException::$PersonException);
        }

        return $Person;
    }

    public static function Logout() {

        if (isset($_SESSION["person_id"])) {
            unset($_SESSION["person_id"]);
            session_destroy();
        }

        return self::GenerateResponse(true);
    }

    public static function GenerateCSRF() {
        try {

            $Person = CoreLogic::VerifyPerson();
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $_SESSION["token"][] = $token;
            $dataObj = new stdClass();
            $dataObj->token = $token;
            $data = $dataObj;
            return self::GenerateResponse(true, $data);
        } catch (PersonException $p) {
            return CoreLogic::GenerateErrorResponse($p->message);
        }
    }

    public static function CheckCSRF($csfr) {
        $result = false;
        if (isset($_SESSION["token"])) {
            $pos = array_search($csfr, $_SESSION["token"]);

            if ($pos !== false) {
                $result = true;
            }
        }

        if (!$result) {
            throw new ApiException(ApiException::$CSRFException);
        }

        unset($_SESSION["token"][$pos]);
    }

    public static function CheckSave($obj, $params = null) {

        if ($params != null) {
            if (!empty($obj->{$params})) {
                throw new Exception(ApiLogic::getCrudErrorCode());
            }
        } else if (!empty($obj->id)) {
            throw new Exception(ApiLogic::getCrudErrorCode());
        }
    }

    public static function Login($request) {

        
        /*
         * Oggetto di risposta
          {
          "result": true,
          "data": {
          "person": {
          "oid": "string",
          "title": "string",
          "first_name": "string",
          "middle_name": "string",
          "company": "string"
          }
          }
          }

         */

        //global $db_conn;
        $username = $request->get('username');
        $password = $request->get('password');
        
        $result = false;
        $data = null;
        $Person = self::getUserFromUsername($username);

        if ($Person) {
            if (password_verify($password, $Person->password)) {
                self::setSession($Person->id);
                $result = true;
                $dataObj = new stdClass();

                $dataObj->person = $Person;

                $data = $dataObj;
            }
        }

        return self::GenerateResponse($result, $data);
    }

    public static function Registration() {
        global $db_conn;

//Utilizzare password_hash per cryptare la password
//password_hash("password", PASSWORD_BCRYPT);
    }

    public static function Permission($request) {
        global $db_conn;

        $result = false;
        $data = null;
        if (self::checkSession()) {
            $result = true;

//Estraggo tutte le permission per la gui richiesta
            $gui = $request->get('gui');
            $Permission = PermissionFactory::Get4Gui(self::GetPersonLogged(), $gui);
            if ($Permission) {
                $dataObj = new stdClass();
                $dataObj->permission = PermissionLogic::SmallPermission($Permission);
                $data = $dataObj;
            }
        }

        return self::GenerateResponse($result, $data);
    }

    public static function Menu() {
        global $db_conn;

        $result = false;
        $data = null;
        if (self::checkSession()) {

            $result = true;
//Estraggo il Menu per l'utente
            $data = GuiLogic::getMenu();
        }

        return self::GenerateResponse($result, $data);
    }

    public static function GenerateResponse($result = false, $data = null) {
        $obj = new stdClass();
        $obj->result = $result;
        $obj->data = $data;
        return $obj;
    }

    public static function GenerateErrorResponse($message = "", $code = "00") {
        $obj = new stdClass();
        $obj->result = false;
        $obj->message = $message;
        $obj->code = $code;
        return $obj;
    }

    public static function ReloadObject($ob, $array, $sanitize = true, $exclude_validity_date = true) {
        global $db_conn;
        $temp_array = get_object_vars($ob);
        foreach ($temp_array as $key => $value) {
            if ($exclude_validity_date && ($key == "valid_from" || $key == "valid_to")) {
                continue;
            }
            if (isset($array[$key])) {
                if ($sanitize) {
                    $ob->$key = self::Sanitize($array[$key]);
                } else {
                    $ob->$key = trim($array->$key);
                }
            }
        }
    }

    public static function GetFromArray($ob, $array, $sanitize = true, $exclude_validity_date = true) {
        global $db_conn;
        $temp_array = get_object_vars($ob);
        foreach ($temp_array as $key => $value) {
            if ($exclude_validity_date && ($key == "valid_from" || $key == "valid_to")) {
                continue;
            }


            if (isset($array[$key])) {
                if ($sanitize) {
                    $ob->$key = self::Sanitize($array[$key]);
                } else {
                    $ob->$key = trim($array->$key);
                }
            } else {
                $ob->$key = null;
            }
        }
    }

    public static function Sanitize($value) {
        global $db_conn;
        if (is_array($value)) {
            foreach ($value as $v) {
                $v = trim($db_conn, $v);
            }
            return $value;
        } else {
            return trim($db_conn, $value);
        }
    }

    public static function generateOID() {
        $tmp = self::GUID();
        return $tmp;
    }

    public static function beginTransaction() {
        global $db_conn;
        CoreFactory::beginTransaction();
    }

    public static function commitTransaction() {
        global $db_conn;
        CoreFactory::commitTransaction();
    }

    public static function rollbackTransaction() {
        global $db_conn;
        CoreFactory::rollbackTransaction();
    }

    private static function GUID() {
        if (function_exists('com_create_guid') === true) {
            return trim(trim(com_create_guid(), '{}'), '-');
        }
        return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
    
    //Get Person given an ID
    public static function getUser($id) {
        $file = _PASSWD_;
        $i = 0;

        if (file_exists($file) && is_file($file)) {

            $contents = file($file);

            //Parse config file line by line
            foreach ($contents as $line) {

                //Get user matching ID
                if ("$i" === $id) {
                    $user = new Person();
                    $array = explode(" ",$line);
                    $user->id = $array[0];
                    $user->username = $array[1];
                    $user->password = $array[2];
                    $user->timezone = $array[3];
                    $user->erased = $array[4];
                    $user->level = $array[5];
                } 

                $i++;
            }
        }
        return $user;
    }
    
    //Get Person given a username
    public static function getUserFromUsername($username) {
        $file = _PASSWD_;
        $i = 0;

        if (file_exists($file) && is_file($file)) {

            $contents = file($file);

            //Parse config file line by line
            foreach ($contents as $line) {

                //Get user matching ID
                if ($username === explode(" ",$line)[1]) {
                    $user = new Person();
                    $array = explode(" ",$line);
                    $user->id = $array[0];
                    $user->username = $array[1];
                    $user->password = $array[2];
                    $user->timezone = $array[3];
                     $user->erased = $array[4];
                    $user->level = $array[5];
                } 

                $i++;
            }
        }
        return $user;
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

    // Get the station code parsing freeture configuration file
    public static function GetStationCode() {
        $freetureConf = _FREETURE_;
        $stationCode = "NO_NAME";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === "DATA_PATH") {
                        $tmp = self::getValue($line);
                        $stationCode = explode("/", $tmp)[2];
                    }
                }
            }
        }
        return $stationCode;
    }

}
