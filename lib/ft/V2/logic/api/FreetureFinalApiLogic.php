<?php
/**
*
* @author: N3 S.r.l.
*/

class FreetureFinalApiLogic
{
        public static function Save($request) {
		try {

			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));
                            
			$ob = new FreetureFinal();
			$tmp = $request->get("data");
                        
			$ob->id  = $tmp["id"] ;
                        $ob->key = $tmp["key"] ;
                        $ob->value = $tmp["value"] ;

			$res = self::updateValue($ob);
                        self::restartFreeture();
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}
        
        public static function EditConfiguration($request) {
		try {

			$Person = CoreLogic::VerifyPerson();
			//CoreLogic::CheckCSRF($request->get("token"));

			//$tmp = $request->get("data");

			$res = self::updateConfigurationFile($request);
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res);
	}
        
        public static function EditMask($request) {
		try {

			$Person = CoreLogic::VerifyPerson();
			//CoreLogic::CheckCSRF($request->get("token"));

			//$tmp = $request->get("data");

			$res = self::updateMaskFile($request);
		} catch (ApiException $a) {
			CoreLogic::rollbackTransaction();
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res);
	}

	public static function Update($request){

		try {
			$Person = CoreLogic::VerifyPerson();
			CoreLogic::CheckCSRF($request->get("token"));

			$ob = new FreetureFinal();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;
                        $ob->key = $tmp["key"] ;
                        $ob->value = $tmp["value"] ;

			$res = self::updateValue($ob);
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

			$ob = new FreetureFinal();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;

			$ob = FreetureFinalLogic::Get($ob->id);

			CoreLogic::beginTransaction();
			$res = FreetureFinalLogic::Erase($ob);
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

			$ob = new FreetureFinal();
			$tmp = $request->get("data");

			$ob->id = $tmp["id"] ;

			$ob = FreetureFinalLogic::Get($ob->id);

			CoreLogic::beginTransaction();
			$res = FreetureFinalLogic::Delete($ob);
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
			$ob = self::getCfg($id);
			$res = true;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}
        
        public static function GetIdByKey($key) {
		try {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			$ob = self::getId($key);
			$res = true;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
	}

	public static function GetList() {
		try {
			$Person = CoreLogic::VerifyPerson();
			$ob = FreetureFinalLogic::GetList();
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
		$codes = FreetureFinalFactory::GetListFilter($columnName,$_GET['term']);
			foreach ($codes as $code){ 
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
				/* ** ESEMPIO **
				case "created_by":
					$foreignKey = end(FreetureFinalFactory::GetForeignKeyParams($columnName));
					$codes = FreetureFinalFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,array("id","CONCAT(last_name, ' ', first_name) AS full_name"),$_GET['term']);
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
					$foreignKey = end(FreetureFinalFactory::GetForeignKeyParams($columnName));
					$codes = FreetureFinalFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,$foreignKey->REFERENCED_COLUMN_NAME,$_GET['term']);
					$data = new stdClass();
					foreach ($codes as $code){ 
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
        $reply = self::parseCfg();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => 0,
            "iTotalRecords" => count($reply),
            "iTotalDisplayRecords" => count($reply),
            "aaData" => $reply
        );
        return $output;
    }
        
    //Clean string 
    public static function trim(String $raw) {
        return str_replace(array(" ", "\n", "\r"), "", $raw);
    }

    //Get the value from the line
    public static function getValue(String $raw) {
        $value1 = explode("=", $raw)[1];
        return self::trim(self::cleanComments($value1));
    }

    //Get the key from the line
    public static function getKey(String $raw) {
        $key1 = explode("=", $raw)[0];
        return self::trim($key1);
    }

    //If the string has "#nv" in the end mean it is invisible
    public static function isVisible(String $raw) {
        return strpos($raw, "#nv") === false;
    }
    
    //Remove the comment character "#" at the beginning
    public static function removeComment(String $raw) {
        for($i=0; $i<strlen($raw); $i++){
            if($raw[$i]!==" " && $raw[$i]!=="#"){
                return substr($raw,$i);
            }
        }
        return $raw;
    }
    
    //Add the comment character "#" at the beginning
    public static function addComment(String $raw) {
        return "# ".$raw;
    }

    //Parse line by line the config file and get the list of params
    public static function parseCfg() {
        $freetureConf = _FREETURE_;
        $list = array();
        $i = 0;
        $descr = "";

        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);
            
         
            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {

                    //Add parameter to the list
                    if (self::isVisible($line)) {
                        $list[] = array(self::getKey($line), self::getValue($line), self::formatDescription($descr), 1, 0, $i);
                    } else {
                        $list[] = array(self::getKey($line), self::getValue($line), self::formatDescription($descr), 0, 0, $i);
                    }
                    $descr = "";
                    $i++;
                } else {
                    if ($line[0] === "#") { //Comments contains the description
                        if(strlen($line) >= 2 && ($line[1] === "#" || $line[1] === "-")){
                            $descr = "";
                        }else{
                            $descr .= self::removeComment($line);
                        }
                    }
                }
            }
        }
        
        return $list;
    }
    
    public static function formatDescription($raw){
        $raw = str_replace("\n", " ", $raw);
        $raw = str_replace("<", "&lt;", $raw);
        $raw = str_replace(">", "&gt;", $raw);
        return $raw;
    }

    //Parse line by line the config file and get a single id
    public static function getCfg($id) {
        $freetureConf = _FREETURE_;
        $i = 0;
        $descr = "no description";
        
        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {

                    //Return requested data
                    if ("$i" === $id) {
                        $ft = new FreetureFinal();
                        $ft->id = $i;
                        $ft->key = self::getKey($line);
                        $ft->value = self::getValue($line);
                        $ft->description = self::removeComment($descr);
                        $ft->show = 1;
                        return $ft;
                    }
                    $i++;
                } else {
                    if ($line[0] === "#") { //Comments contains the description
                        $descr = $line;
                    }
                }
            }
        }
        return false;
    }
    
    public static function getId($key) {
        $freetureConf = _FREETURE_;
        $i = 0;
        if (file_exists($freetureConf) && is_file($freetureConf)) {
            $contents = file($freetureConf);
            foreach ($contents as $line) {
                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    if (self::getKey($line) === $key) {
                        return $i;
                    }
                    $i++;
                } 
            }
        }
        return false;
    }

    //Clean comments in the end of the string
    public static function cleanComments(String $raw) {
        if (!strpos($raw, "#") === false) {
            return substr($raw, 0, strpos($raw, "#")) . "\n";
        } else {
            return $raw;
        }
    }

    //Set parameter as invisible adding "#nv" (not visible)
    public static function setVisible(String $raw) {
        if (!strpos($raw, "#nv") === false) {
            return substr($raw, 0, strpos($raw, "#")) . "\n";
        }
    }

    //Set parameter as visible removing "#nv"
    public static function setInvisible(String $raw) {
        return str_replace("\n", " #nv\n", $raw);
    }

    //Update the value, from a given object, in the cfg file
    public static function updateValue($ob) {
        $freetureConf = _FREETURE_;
        $reply = "";
        $i = 0;
        $descr = "no description";

        if (file_exists($freetureConf) && is_file($freetureConf)) {

            $contents = file($freetureConf);

            //Parse config file line by line
            foreach ($contents as $line) {

                //If the line has some content and does not start with #,
                //or contains only new line or whitespaces
                if (isset($line) && $line !== "" && $line[0] !== "#" && $line[0] !== "\n" && $line[0] !== "\t" &&
                        (strlen($line) - 1) !== substr_count($line, " ")) {
                    //Update the requested param
                    if ($i === $ob->id) {
                        $reply .= $ob->key." = ".$ob->value."\n";
                        
                    }else{
                        $reply .= $line;
                    }

                    $i++;
                } else {
                    if ($line[0] === "#") { //Comments contains the description
                        $reply .= $line;
                    }
                }
            }
            $myfile = fopen($freetureConf, "w");
            fwrite($myfile, $reply);
            fclose($myfile);
        }
        return true;
    }
    
    public static function updateConfigurationFile($ob){
        $freetureConf = _FREETURE_;
        if(!empty($ob)){
            $result = move_uploaded_file($ob, $freetureConf);
            self::restartFreeture();
            return $result;
        }
        return false;
    }
    
    // Return mask file path
    public static function GetMaskFile(){
        return self::getMaskPath();
    }
   
    public static function updateMaskFile($ob){
        $freetureConf = self::getMaskPath();
        if(!empty($ob)){
            $result = move_uploaded_file($ob, $freetureConf);
            self::restartFreeture();
            return $result;
        }       
        return false;
    }
    
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
    
    // Restart freeture container in ssh
    public static function restartFreeture(){
        $session = ssh2_connect( _DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "sudo docker restart freeture");
                return true;
            }
            unset($session);
        }
        return false;
    }
       
    //Generates passwords
    public static function passwdGen() {
        $file = "";
        $name = "";
        $passwd = "";
        $myfile = fopen($file, "w");
        
        $reply = new Person();
        $reply->id = "0";
        $reply->username = $name;
        $reply->password = password_hash($passwd, PASSWORD_BCRYPT);
        $reply->timezone = "0";
        $reply->erased = "0";
        
        $string = $reply->id." ".$reply->username." ".$reply->password." ".$reply->timezone." ".$reply->erased;
        
        fwrite($myfile, $string);
        fclose($myfile);
    }

}

