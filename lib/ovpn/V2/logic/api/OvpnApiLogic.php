<?php

class OvpnApiLogic
{        
    
    public static function EditConfiguration($request) {
        try {

                $Person = CoreLogic::VerifyPerson();

                $res = self::updateConfigurationFile($request);
        } catch (ApiException $a) {
                CoreLogic::rollbackTransaction();
                return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res);
    }
    
    public static function GetStatus() {
		try {
			$Person = CoreLogic::VerifyPerson();
			$ob = self::getVpnStatus();
			$res = true;
		} catch (ApiException $a) {
			return CoreLogic::GenerateErrorResponse($a->message);
		}
		return CoreLogic::GenerateResponse($res, $ob);
    }
    
    public static function updateConfigurationFile($ob){
        $vpnConf = _OVPN_;
        if(!empty($ob)){
            //return move_uploaded_file($ob, $vpnConf);
            shell_exec('systemctl restart openvpn@client.service');
            return true;
        }
        return false;
    }
    
     public static function getVpnStatus() {
	return shell_exec('ip addr show dev tun0');
    }

}

