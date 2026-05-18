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

    // Return structured info for the wired interface(s) carrying internet on
    // the host running webmin. Auto-detected: first the interface(s) of the
    // default IPv4 route, then a scan of /sys/class/net for physical Ethernet
    // ports if no default route exists. Uses local `ip -j` (iproute2 JSON
    // output), no SSH — the host filesystem is already trusted for reading
    // /freeture/* etc. Each entry: {name, present, operstate, mac, mtu,
    // ipv4[], ipv6[], isDefault}.
    public static function getWiredNetworkInterfaces() {
        $defaultIfaces = self::findDefaultRouteIfaces();
        $candidates = !empty($defaultIfaces)
            ? $defaultIfaces
            : self::findPhysicalEthernetIfaces();

        $defaultSet = array_flip($defaultIfaces);
        $result = array();
        foreach ($candidates as $iface) {
            $entry = self::probeNetworkInterface($iface);
            $entry['isDefault'] = isset($defaultSet[$iface]);
            $result[] = $entry;
        }
        return $result;
    }

    private static function findDefaultRouteIfaces() {
        $raw = shell_exec("ip -j route show default 2>/dev/null");
        if (!is_string($raw) || $raw === '') {
            return array();
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return array();
        }
        $seen = array();
        foreach ($data as $r) {
            if (!empty($r['dev']) && !isset($seen[$r['dev']])) {
                $seen[$r['dev']] = true;
            }
        }
        return array_keys($seen);
    }

    // Physical Ethernet ifaces from /sys/class/net (type==1 and a backing
    // device link, excluding obvious virtual/wireless name prefixes).
    private static function findPhysicalEthernetIfaces() {
        $result = array();
        $dirs = @scandir('/sys/class/net');
        if (!is_array($dirs)) {
            return $result;
        }
        $excludeRx = '/^(lo|docker|br-|veth|tun|tap|virbr|lxd|wlan|wlp|wwan|wwp)/i';
        foreach ($dirs as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }
            if (preg_match($excludeRx, $name)) {
                continue;
            }
            $devLink = "/sys/class/net/$name/device";
            if (!file_exists($devLink)) {
                continue; // virtual iface — no backing PCI/USB device
            }
            $type = trim((string) @file_get_contents("/sys/class/net/$name/type"));
            if ($type !== '1') {
                continue; // 1 == ARPHRD_ETHER
            }
            $result[] = $name;
        }
        return $result;
    }

    private static function probeNetworkInterface($iface) {
        $entry = array(
            'name'      => $iface,
            'present'   => false,
            'operstate' => null,
            'mac'       => null,
            'mtu'       => null,
            'ipv4'      => array(),
            'ipv6'      => array(),
        );
        $raw = shell_exec("ip -j address show " . escapeshellarg($iface) . " 2>/dev/null");
        if (!is_string($raw)) {
            return $entry;
        }
        $raw = trim($raw);
        if ($raw === '' || $raw[0] !== '[') {
            return $entry;
        }
        $data = json_decode($raw, true);
        if (!is_array($data) || empty($data[0])) {
            return $entry;
        }
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
        return $entry;
    }
}
