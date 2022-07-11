<?php

class PrometheusApiLogic {
    
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
    
    // Update prometheus configuration file with given file
    public static function updateConfigurationFile($ob) {

        $prometheusConf = _PROMETHEUS_;
        $result = false;
        if (empty($ob)) {
            return false;
        }
        
        /*
        if(!move_uploaded_file($ob, $prometheusConf)){
            return false;
        }
        */

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
               
                ssh2_scp_send($session, $ob, $prometheusConf);

                $stream = ssh2_exec($session, "sudo /bin/systemctl restart node_exporter");
                
                unset($session);
                $result = true;
                
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }

        return $result;
    }

}
