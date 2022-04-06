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

    public static function updateConfigurationFile($ob) {

        $vpnConf = _OVPN_;
        $result = false;
        if (empty($ob)) {
            return $result;
        }

        $i = 0;
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                ssh2_scp_send($session, $ob, _OVPN_);

                $stream = ssh2_exec($session, "sudo /bin/systemctl restart openvpn@client.service");
                
                unset($session);
                $result = true;
                
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }


        return $result;
    }

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

        return $text2;
    }

}
