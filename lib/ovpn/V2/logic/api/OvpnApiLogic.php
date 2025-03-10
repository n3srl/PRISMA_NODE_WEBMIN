<?php

class OvpnApiLogic {

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
    
    public static function GetNetStatus() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = self::getNetworkStatus();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }
    
    // Update ovpn configuration file with given file
    public static function updateConfigurationFile($ob) {

        $vpnConf = _OVPN_;
        $result = false;
        if (empty($ob)) {
            return false;
        }
        
        /*
        if(!move_uploaded_file($ob, $vpnConf)){
            return false;
        }
        */
       
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                ssh2_scp_send($session, $ob, $vpnConf);

                $stream = ssh2_exec($session, "sudo /bin/systemctl restart openvpn@client.service");
                
                unset($session);
                $result = true;
                
            }
            unset($session);
        }
        return $result;
    }
    
    // Get vpn status 
    public static function getVpnStatus() {
        $i = 0;
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $text2 = "";

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                //Execute command to get containers
                //https://www.baeldung.com/ops/docker-list-containers

                $stream1 = ssh2_exec($session, "ip tuntap show");
                stream_set_blocking($stream1, true);
                $stream_out1 = ssh2_fetch_stream($stream1, SSH2_STREAM_STDIO);
                $text1 = stream_get_contents($stream_out1);

                if (!(empty($text1))) {
                    $stream2 = ssh2_exec($session, "ip addr show dev tun0");
                    stream_set_blocking($stream2, true);
                    $stream_out2 = ssh2_fetch_stream($stream2, SSH2_STREAM_STDIO);
                    $text2 = stream_get_contents($stream_out2);
                }
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }

        $text2 = str_replace("\n", "</br>",$text2);
        return $text2;
    }

        
    // Get network status 
    public static function getNetworkStatus() {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $text2 = "";

        $supported_interfaces = ["enp1s0", "eno2"];

        if ($session) {

            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                foreach($supported_interfaces as $interface) {
                    $stream2 = ssh2_exec($session, "ip address show ".$interface);
                    stream_set_blocking($stream2, true);
                    $stream_out2 = ssh2_fetch_stream($stream2, SSH2_STREAM_STDIO);
                    $res = stream_get_contents($stream_out2);
                    $text2 = $text2.str_replace("\n", "</br>",$res);
                }
            }
            

            unset($session);
        }
        
        $text2 = str_replace("\n", "</br>",$text2);
        
        return $text2;
    }
}
