<?php

/**
 *
 * @author: N3 S.r.l.
 */
class NetworkApiLogic {

    const INTERFACES_PATH = '/etc/network/interfaces';
    const ARV_TOOL        = 'arv-tool-0.8';

    /* ----------------------------------------------------------------
     * NODE — read / preview / apply /etc/network/interfaces
     * --------------------------------------------------------------*/

    public static function GetNodeConfig() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $raw = self::sshExec("cat " . self::INTERFACES_PATH . " 2>/dev/null");
            $iface = self::detectDefaultIface();
            $parsed = self::parseInterfacesFile((string) $raw, $iface);
            $result = array(
                'iface'    => $iface,
                'raw'      => (string) $raw,
                'mode'     => $parsed['mode'],
                'address'  => $parsed['address'],
                'netmask'  => $parsed['netmask'],
                'gateway'  => $parsed['gateway'],
                'dns'      => $parsed['dns'],
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    public static function PreviewNodeConfig($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));
            $data = $request->get("data");
            $validation = self::validateNodeInput($data);
            if (!$validation['ok']) {
                return CoreLogic::GenerateErrorResponse($validation['error']);
            }
            $raw = (string) self::sshExec("cat " . self::INTERFACES_PATH . " 2>/dev/null");
            $newContent = self::buildInterfacesFile($raw, $data);
            $result = array(
                'iface'      => $data['iface'],
                'oldContent' => $raw,
                'newContent' => $newContent,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    public static function ApplyNodeConfig($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));
            $data = $request->get("data");
            $validation = self::validateNodeInput($data);
            if (!$validation['ok']) {
                return CoreLogic::GenerateErrorResponse($validation['error']);
            }
            $raw = (string) self::sshExec("cat " . self::INTERFACES_PATH . " 2>/dev/null");
            $newContent = self::buildInterfacesFile($raw, $data);
            $stamp = date('YmdHis');
            $backup = self::INTERFACES_PATH . ".bak." . $stamp;

            $payload = base64_encode($newContent);
            $cmd =
                "sudo cp " . escapeshellarg(self::INTERFACES_PATH) . " " . escapeshellarg($backup) . " && " .
                "echo " . escapeshellarg($payload) . " | base64 -d | sudo tee " . escapeshellarg(self::INTERFACES_PATH) . " > /dev/null && " .
                "sudo systemctl restart networking 2>&1";
            $out = (string) self::sshExec($cmd);

            $result = array(
                'iface'      => $data['iface'],
                'backupPath' => $backup,
                'newContent' => $newContent,
                'output'     => $out,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    /* ----------------------------------------------------------------
     * WIRED — read structured status of physical Ethernet interfaces
     * --------------------------------------------------------------*/

    public static function GetWiredNetworkInfo() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = self::collectWiredInterfaces();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    // Return structured info for the wired interface(s) carrying internet on
    // the host running webmin. Auto-detected: first the interface(s) of the
    // default IPv4 route from /proc/net/route, then a scan of /sys/class/net
    // for physical Ethernet ports. Each entry: {name, present, operstate,
    // mac, mtu, ipv4[], ipv6[], isDefault}.
    private static function collectWiredInterfaces() {
        $defaultIfaces = self::findDefaultRouteIfacesLocal();
        $candidates = !empty($defaultIfaces)
            ? $defaultIfaces
            : self::findPhysicalEthernetIfaces();

        $defaultSet = array_flip($defaultIfaces);
        $result = array();
        foreach ($candidates as $iface) {
            $entry = self::probeWiredInterface($iface);
            $entry['isDefault'] = isset($defaultSet[$iface]);
            $result[] = $entry;
        }
        return $result;
    }

    // Read default-route interfaces straight from /proc/net/route. This avoids
    // depending on the `ip` binary being on the web server's PATH.
    private static function findDefaultRouteIfacesLocal() {
        $raw = @file_get_contents('/proc/net/route');
        if (!is_string($raw)) {
            return array();
        }
        $seen = array();
        $lines = preg_split('/\R/', trim($raw));
        foreach ($lines as $idx => $line) {
            if ($idx === 0) { continue; }
            $cols = preg_split('/\s+/', trim($line));
            if (count($cols) < 2 || $cols[1] !== '00000000') { continue; }
            if (!isset($seen[$cols[0]])) {
                $seen[$cols[0]] = true;
            }
        }
        return array_keys($seen);
    }

    private static function findPhysicalEthernetIfaces() {
        $result = array();
        $dirs = @scandir('/sys/class/net');
        if (!is_array($dirs)) {
            return $result;
        }
        $excludeRx = '/^(lo|docker|br-|veth|tun|tap|virbr|lxd|wlan|wlp|wwan|wwp)/i';
        foreach ($dirs as $name) {
            if ($name === '.' || $name === '..') { continue; }
            if (preg_match($excludeRx, $name)) { continue; }
            $devLink = "/sys/class/net/$name/device";
            if (!file_exists($devLink)) { continue; }
            $type = trim((string) @file_get_contents("/sys/class/net/$name/type"));
            if ($type !== '1') { continue; }
            $result[] = $name;
        }
        return $result;
    }

    private static function probeWiredInterface($iface) {
        $entry = array(
            'name'      => $iface,
            'present'   => false,
            'operstate' => null,
            'mac'       => null,
            'mtu'       => null,
            'ipv4'      => array(),
            'ipv6'      => array(),
        );

        // Primary: net_get_interfaces() (PHP >= 7.3). No shell, no SSH.
        if (function_exists('net_get_interfaces')) {
            $all = @net_get_interfaces();
            if (is_array($all) && isset($all[$iface])) {
                $d = $all[$iface];
                $entry['present']   = true;
                $entry['operstate'] = !empty($d['up']) ? 'UP' : 'DOWN';
                $entry['mac']       = (isset($d['mac']) && $d['mac'] !== '') ? $d['mac'] : null;
                $entry['mtu']       = isset($d['mtu']) ? (int) $d['mtu'] : null;
                if (isset($d['unicast']) && is_array($d['unicast'])) {
                    foreach ($d['unicast'] as $u) {
                        $addr = isset($u['address']) ? $u['address'] : '';
                        if ($addr === '') { continue; }
                        $family = isset($u['family']) ? (int) $u['family'] : 0;
                        $prefix = self::wiredNetmaskToPrefix(isset($u['netmask']) ? $u['netmask'] : '');
                        $cidr   = $addr . ($prefix !== null ? '/' . $prefix : '');
                        if ($family === 2) {
                            $entry['ipv4'][] = $cidr;
                        } else if ($family === 10 || $family === 30) {
                            $entry['ipv6'][] = $cidr;
                        }
                    }
                }
                return $entry;
            }
        }

        // Fallback: read /sys/class/net for basic state (no IPs).
        $base = "/sys/class/net/" . $iface;
        if (is_dir($base)) {
            $entry['present']   = true;
            $state = trim((string) @file_get_contents("$base/operstate"));
            $entry['operstate'] = $state === '' ? null : strtoupper($state);
            $entry['mac']       = trim((string) @file_get_contents("$base/address")) ?: null;
            $mtu = trim((string) @file_get_contents("$base/mtu"));
            $entry['mtu']       = $mtu === '' ? null : (int) $mtu;
        }
        return $entry;
    }

    private static function wiredNetmaskToPrefix($mask) {
        if (!is_string($mask) || $mask === '') { return null; }
        $packed = @inet_pton($mask);
        if ($packed === false) { return null; }
        $bits = 0;
        $len = strlen($packed);
        for ($i = 0; $i < $len; $i++) {
            $byte = ord($packed[$i]);
            if ($byte === 0xFF) { $bits += 8; continue; }
            for ($b = 7; $b >= 0; $b--) {
                if ($byte & (1 << $b)) { $bits++; }
                else { return $bits; }
            }
        }
        return $bits;
    }

    /* ----------------------------------------------------------------
     * CAMERA — discover / read / preview / apply via arv-tool-0.8
     * --------------------------------------------------------------*/

    public static function ListCameras() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $out = (string) self::sshExec(self::ARV_TOOL . " 2>&1");
            $cameras = self::parseArvDeviceList($out);
            $result = array(
                'cameras' => $cameras,
                'raw'     => $out,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    public static function GetCameraConfig($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $name = (string) $request->get('name', '');
            if ($name === '') {
                return CoreLogic::GenerateErrorResponse('camera name missing');
            }
            // GenICam / GigE Vision standard features (per Basler/Aravis):
            // GevCurrentIPConfiguration is a uint32 bitmask:
            //   bit 0 (0x01) = PersistentIP, bit 1 (0x02) = DHCP, bit 2 (0x04) = LLA
            // GevCurrent/Persistent*Address are uint32 IPs in network byte order.
            $features = array(
                'GevCurrentIPConfiguration',
                'GevCurrentIPAddress',
                'GevCurrentSubnetMask',
                'GevCurrentDefaultGateway',
                'GevPersistentIPAddress',
                'GevPersistentSubnetMask',
                'GevPersistentDefaultGateway',
            );
            $cmd = self::ARV_TOOL . " control --name=" . escapeshellarg($name);
            foreach ($features as $f) {
                $cmd .= " " . escapeshellarg($f);
            }
            $cmd .= " 2>&1";
            $out = (string) self::sshExec($cmd);
            $values = self::parseArvFeatures($out);

            $configBits = isset($values['GevCurrentIPConfiguration']) ? (int) $values['GevCurrentIPConfiguration'] : 0;
            $pick = function ($k) use ($values) {
                return isset($values[$k]) ? $values[$k] : null;
            };
            $result = array(
                'name'              => $name,
                'mode'              => self::decodeIpConfig($configBits),
                'configBits'        => $configBits,
                'currentIp'         => self::longToIp($pick('GevCurrentIPAddress')),
                'currentMask'       => self::longToIp($pick('GevCurrentSubnetMask')),
                'currentGateway'    => self::longToIp($pick('GevCurrentDefaultGateway')),
                'persistentIp'      => self::longToIp($pick('GevPersistentIPAddress')),
                'persistentMask'    => self::longToIp($pick('GevPersistentSubnetMask')),
                'persistentGateway' => self::longToIp($pick('GevPersistentDefaultGateway')),
                'raw'               => $out,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    // GigE Vision IP configuration bitmask:
    //   bit 0 (0x01) PersistentIP, bit 1 (0x02) DHCP, bit 2 (0x04) LLA.
    // DHCP wins over PersistentIP for display purposes; LLA alone is a
    // fallback mode the camera uses when neither DHCP nor PersistentIP yields
    // an address.
    private static function decodeIpConfig($bits) {
        if ($bits & 0x02) { return 'dhcp'; }
        if ($bits & 0x01) { return 'static'; }
        if ($bits & 0x04) { return 'lla'; }
        return 'unknown';
    }

    private static function longToIp($val) {
        if ($val === null || $val === '' || !is_numeric($val)) {
            return null;
        }
        // long2ip accepts both signed and unsigned int32 representations.
        return long2ip((int) $val);
    }

    private static function ipToUnsignedLong($ip) {
        $v = ip2long($ip);
        if ($v === false) {
            return null;
        }
        return sprintf('%u', $v);
    }

    public static function PreviewCameraConfig($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));
            $data = $request->get("data");
            $validation = self::validateCameraInput($data);
            if (!$validation['ok']) {
                return CoreLogic::GenerateErrorResponse($validation['error']);
            }
            $cmds = self::buildCameraCommands($data);
            $result = array(
                'name'     => $data['name'],
                'commands' => $cmds,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    public static function ApplyCameraConfig($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));
            $data = $request->get("data");
            $validation = self::validateCameraInput($data);
            if (!$validation['ok']) {
                return CoreLogic::GenerateErrorResponse($validation['error']);
            }
            $cmds = self::buildCameraCommands($data);
            $shell = implode(" && ", $cmds) . " 2>&1";
            $out = (string) self::sshExec($shell);
            $result = array(
                'name'     => $data['name'],
                'commands' => $cmds,
                'output'   => $out,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
    }

    /* ----------------------------------------------------------------
     * Helpers — SSH
     * --------------------------------------------------------------*/

    private static function sshExec($cmd) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        if (!$session) {
            return '';
        }
        if (!ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
            unset($session);
            return '';
        }
        $stream = ssh2_exec($session, $cmd);
        if (!$stream) {
            unset($session);
            return '';
        }
        stream_set_blocking($stream, true);
        $out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
        $err = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        $text = stream_get_contents($out);
        $errText = stream_get_contents($err);
        unset($session);
        if ($text === false) { $text = ''; }
        if ($errText !== false && $errText !== '') {
            $text .= $errText;
        }
        return $text;
    }

    /* ----------------------------------------------------------------
     * Helpers — /etc/network/interfaces parsing & generation
     * --------------------------------------------------------------*/

    // Detect the iface carrying the default IPv4 route — same approach as
    // OvpnApiLogic::findDefaultRouteIfaces, but executed on the host via SSH
    // because /proc/net/route on the webserver may be the same host (it is in
    // current deployments) but we want consistency with the rest of network
    // operations.
    private static function detectDefaultIface() {
        $raw = self::sshExec("awk 'NR>1 && $2==\"00000000\" {print $1; exit}' /proc/net/route 2>/dev/null");
        $iface = trim((string) $raw);
        if ($iface !== '') {
            return $iface;
        }
        // Fallback: any iface with state UP that isn't lo/docker/tun.
        $raw = self::sshExec("ls -1 /sys/class/net 2>/dev/null");
        foreach (preg_split('/\R/', (string) $raw) as $n) {
            $n = trim($n);
            if ($n === '' || $n === 'lo') continue;
            if (preg_match('/^(docker|br-|veth|tun|tap|virbr|wlan|wlp|wwan|wwp)/', $n)) continue;
            return $n;
        }
        return '';
    }

    private static function parseInterfacesFile($content, $iface) {
        $out = array('mode' => 'dhcp', 'address' => '', 'netmask' => '', 'gateway' => '', 'dns' => '');
        if ($iface === '' || $content === '') {
            return $out;
        }
        $lines = preg_split('/\R/', $content);
        $inStanza = false;
        foreach ($lines as $line) {
            $trim = trim($line);
            if (preg_match('/^iface\s+(\S+)\s+inet\s+(\w+)/', $trim, $m)) {
                if ($m[1] === $iface) {
                    $inStanza = true;
                    $out['mode'] = strtolower($m[2]);
                    continue;
                }
                if ($inStanza) {
                    break; // entered another iface stanza
                }
            }
            if (!$inStanza) {
                continue;
            }
            // Stop at next top-level directive (auto/allow-*/iface/mapping/source)
            if (preg_match('/^(auto|allow-|iface|mapping|source)\b/', $trim)) {
                break;
            }
            if (preg_match('/^address\s+(.+)$/', $trim, $m)) {
                $out['address'] = trim($m[1]);
            } else if (preg_match('/^netmask\s+(.+)$/', $trim, $m)) {
                $out['netmask'] = trim($m[1]);
            } else if (preg_match('/^gateway\s+(.+)$/', $trim, $m)) {
                $out['gateway'] = trim($m[1]);
            } else if (preg_match('/^dns-nameservers\s+(.+)$/', $trim, $m)) {
                $out['dns'] = trim($m[1]);
            }
        }
        return $out;
    }

    // Replace the target iface stanza in $content with a new one built from
    // $data. If no stanza exists for the iface, append a new one.
    private static function buildInterfacesFile($content, $data) {
        $iface = $data['iface'];
        $mode  = $data['mode'];
        $stanza = self::generateStanza($data);

        if ($content === '') {
            return "auto $iface\n" . $stanza;
        }

        $lines = preg_split('/\R/', $content);
        $startIdx = -1;
        $endIdx = -1;
        $autoIdx = -1;

        // Find auto-line for this iface (we keep it if present).
        for ($i = 0; $i < count($lines); $i++) {
            if (preg_match('/^\s*auto\s+(.*)$/', $lines[$i], $m)) {
                $ifaces = preg_split('/\s+/', trim($m[1]));
                if (in_array($iface, $ifaces, true)) {
                    $autoIdx = $i;
                }
            }
        }

        // Find iface stanza
        for ($i = 0; $i < count($lines); $i++) {
            if (preg_match('/^\s*iface\s+(\S+)\s+inet/', $lines[$i], $m)) {
                if ($m[1] === $iface) {
                    $startIdx = $i;
                    // Find end: next top-level directive or EOF
                    for ($j = $i + 1; $j < count($lines); $j++) {
                        $tt = trim($lines[$j]);
                        if (preg_match('/^(auto|allow-|iface|mapping|source)\b/', $tt)) {
                            break;
                        }
                    }
                    $endIdx = $j - 1;
                    break;
                }
            }
        }

        if ($startIdx === -1) {
            // No existing stanza; append.
            $rebuilt = rtrim($content, "\n") . "\n\nauto $iface\n" . $stanza;
            return $rebuilt;
        }

        $before = array_slice($lines, 0, $startIdx);
        $after = array_slice($lines, $endIdx + 1);
        $stanzaLines = preg_split('/\R/', rtrim($stanza, "\n"));
        $merged = array_merge($before, $stanzaLines, $after);
        return implode("\n", $merged) . "\n";
    }

    private static function generateStanza($data) {
        $iface = $data['iface'];
        $mode  = $data['mode'];
        if ($mode === 'dhcp') {
            return "iface $iface inet dhcp\n";
        }
        // static
        $cidr = $data['address'];
        $netmask = isset($data['netmask']) ? $data['netmask'] : '';
        $gateway = isset($data['gateway']) ? $data['gateway'] : '';
        $dns = isset($data['dns']) ? trim($data['dns']) : '';

        $stanza = "iface $iface inet static\n";
        $stanza .= "    address $cidr\n";
        if ($netmask !== '' && strpos($cidr, '/') === false) {
            $stanza .= "    netmask $netmask\n";
        }
        if ($gateway !== '') {
            $stanza .= "    gateway $gateway\n";
        }
        if ($dns !== '') {
            $stanza .= "    dns-nameservers $dns\n";
        }
        return $stanza;
    }

    private static function validateNodeInput($data) {
        if (!is_array($data)) {
            return array('ok' => false, 'error' => 'missing data');
        }
        $iface = isset($data['iface']) ? trim($data['iface']) : '';
        if ($iface === '' || !preg_match('/^[a-zA-Z0-9_.:-]+$/', $iface)) {
            return array('ok' => false, 'error' => 'invalid iface name');
        }
        $mode = isset($data['mode']) ? strtolower($data['mode']) : '';
        if ($mode !== 'dhcp' && $mode !== 'static') {
            return array('ok' => false, 'error' => 'mode must be dhcp or static');
        }
        if ($mode === 'static') {
            $addr = isset($data['address']) ? trim($data['address']) : '';
            if ($addr === '') {
                return array('ok' => false, 'error' => 'address required for static');
            }
            // Accept either "1.2.3.4/24" CIDR or plain "1.2.3.4" (then need separate netmask)
            $cidrParts = explode('/', $addr, 2);
            if (filter_var($cidrParts[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                return array('ok' => false, 'error' => 'address is not a valid IPv4');
            }
            if (count($cidrParts) === 2) {
                $p = (int) $cidrParts[1];
                if ($p < 0 || $p > 32) {
                    return array('ok' => false, 'error' => 'invalid CIDR prefix');
                }
            } else {
                $nm = isset($data['netmask']) ? trim($data['netmask']) : '';
                if ($nm === '' || filter_var($nm, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    return array('ok' => false, 'error' => 'netmask required when address has no /prefix');
                }
            }
            if (!empty($data['gateway']) && filter_var($data['gateway'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                return array('ok' => false, 'error' => 'gateway is not a valid IPv4');
            }
            if (!empty($data['dns'])) {
                foreach (preg_split('/[\s,]+/', $data['dns']) as $d) {
                    if ($d === '') continue;
                    if (filter_var($d, FILTER_VALIDATE_IP) === false) {
                        return array('ok' => false, 'error' => "invalid DNS: $d");
                    }
                }
            }
        }
        return array('ok' => true);
    }

    /* ----------------------------------------------------------------
     * Helpers — arv-tool-0.8 output parsing
     * --------------------------------------------------------------*/

    // Sample arv-tool-0.8 listing line:
    //   Lucid Vision Labs-TRI054S-C-...-... (192.168.0.42)
    //   Aravis-Fake-1 (192.168.0.50)
    // Parse "name (ip)" lines, skipping headers/errors.
    private static function parseArvDeviceList($out) {
        $list = array();
        if (!is_string($out)) return $list;
        foreach (preg_split('/\R/', $out) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') continue;
            if (preg_match('/^(.+?)\s+\(([0-9a-fA-F.:]+)\)\s*$/', $line, $m)) {
                $list[] = array(
                    'name' => trim($m[1]),
                    'ip'   => trim($m[2]),
                );
            }
        }
        return $list;
    }

    // arv-tool-0.8 control output for a feature is typically:
    //   GevPersistentIPAddress = '192.168.0.10' (...)
    // We capture the feature name and the first quoted token (or bare number).
    private static function parseArvFeatures($out) {
        $values = array();
        if (!is_string($out)) return $values;
        foreach (preg_split('/\R/', $out) as $line) {
            if (preg_match('/^\s*([A-Za-z][A-Za-z0-9_]*)\s*=\s*\'([^\']*)\'/', $line, $m)
                || preg_match('/^\s*([A-Za-z][A-Za-z0-9_]*)\s*=\s*"([^"]*)"/', $line, $m)
                || preg_match('/^\s*([A-Za-z][A-Za-z0-9_]*)\s*=\s*([0-9.A-Za-z]+)/', $line, $m)) {
                $values[$m[1]] = $m[2];
            }
        }
        return $values;
    }

    private static function validateCameraInput($data) {
        if (!is_array($data) || empty($data['name'])) {
            return array('ok' => false, 'error' => 'camera name required');
        }
        $mode = isset($data['mode']) ? strtolower($data['mode']) : '';
        if ($mode !== 'dhcp' && $mode !== 'static') {
            return array('ok' => false, 'error' => 'mode must be dhcp or static');
        }
        if ($mode === 'static') {
            foreach (array('ip' => 'ip', 'mask' => 'subnet mask') as $k => $label) {
                $v = isset($data[$k]) ? trim($data[$k]) : '';
                if ($v === '' || filter_var($v, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                    return array('ok' => false, 'error' => "$label is not a valid IPv4");
                }
            }
            if (!empty($data['gateway']) && filter_var($data['gateway'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
                return array('ok' => false, 'error' => 'gateway is not a valid IPv4');
            }
        }
        return array('ok' => true);
    }

    // Build the list of arv-tool-0.8 commands to apply the requested config.
    // GigE Vision encodes the IP-config mode as a uint32 bitmask in
    // GevCurrentIPConfiguration (bit0=PersistentIP, bit1=DHCP, bit2=LLA).
    // We always keep LLA enabled as a fallback in case the chosen method
    // fails to yield an address. IP/mask/gateway are uint32 in network byte
    // order — passed to arv-tool as plain decimal integers.
    private static function buildCameraCommands($data) {
        $name = $data['name'];
        $base = self::ARV_TOOL . " control --name=" . escapeshellarg($name);
        $cmds = array();
        if ($data['mode'] === 'static') {
            $ipInt   = self::ipToUnsignedLong($data['ip']);
            $maskInt = self::ipToUnsignedLong($data['mask']);
            $cmds[] = $base . " " . escapeshellarg("GevPersistentIPAddress=" . $ipInt);
            $cmds[] = $base . " " . escapeshellarg("GevPersistentSubnetMask=" . $maskInt);
            if (!empty($data['gateway'])) {
                $gwInt = self::ipToUnsignedLong($data['gateway']);
                $cmds[] = $base . " " . escapeshellarg("GevPersistentDefaultGateway=" . $gwInt);
            }
            // PersistentIP (0x01) + LLA fallback (0x04) = 5
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfiguration=5");
        } else {
            // DHCP (0x02) + LLA fallback (0x04) = 6
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfiguration=6");
        }
        return $cmds;
    }
}
