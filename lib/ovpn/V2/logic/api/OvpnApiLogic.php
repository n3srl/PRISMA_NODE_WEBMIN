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

    public static function GetWiredNetworkInfo() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = self::getWiredNetworkInterfaces();
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

    // Return structured info for each known wired interface on the docker host.
    // Uses `ip -j address show <iface>` (iproute2 >= 5.4) and parses JSON, so
    // there's no fragile text scraping. Each entry: {name, present, operstate,
    // mac, mtu, ipv4[], ipv6[]}. Interfaces that aren't present on the host
    // are still returned with present=false so the UI can render a placeholder.
    public static function getWiredNetworkInterfaces() {
        $supported = array("enp1s0", "eno2");
        $result = array();

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        if (!$session) {
            return $result;
        }
        if (!ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
            unset($session);
            return $result;
        }

        foreach ($supported as $iface) {
            $stream = ssh2_exec($session, "ip -j address show " . escapeshellarg($iface) . " 2>/dev/null");
            stream_set_blocking($stream, true);
            $out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $raw = trim((string) stream_get_contents($out));

            $entry = array(
                'name'      => $iface,
                'present'   => false,
                'operstate' => null,
                'mac'       => null,
                'mtu'       => null,
                'ipv4'      => array(),
                'ipv6'      => array(),
            );

            if ($raw !== '' && $raw[0] === '[') {
                $data = json_decode($raw, true);
                if (is_array($data) && !empty($data[0])) {
                    $d = $data[0];
                    $entry['present']   = true;
                    $entry['operstate'] = isset($d['operstate']) ? $d['operstate'] : null;
                    $entry['mac']       = isset($d['address']) ? $d['address'] : null;
                    $entry['mtu']       = isset($d['mtu']) ? (int) $d['mtu'] : null;
                    if (isset($d['addr_info']) && is_array($d['addr_info'])) {
                        foreach ($d['addr_info'] as $a) {
                            $local  = isset($a['local']) ? $a['local'] : '';
                            $prefix = isset($a['prefixlen']) ? $a['prefixlen'] : '';
                            if ($local === '') {
                                continue;
                            }
                            $cidr = $local . '/' . $prefix;
                            $family = isset($a['family']) ? $a['family'] : '';
                            if ($family === 'inet') {
                                $entry['ipv4'][] = $cidr;
                            } else if ($family === 'inet6') {
                                $entry['ipv6'][] = $cidr;
                            }
                        }
                    }
                }
            }

            $result[] = $entry;
        }

        unset($session);
        return $result;
    }
}
