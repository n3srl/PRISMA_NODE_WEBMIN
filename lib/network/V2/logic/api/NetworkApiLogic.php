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
            $features = array(
                'GevCurrentIPAddress',
                'GevCurrentSubnetMask',
                'GevCurrentDefaultGateway',
                'GevPersistentIPAddress',
                'GevPersistentSubnetMask',
                'GevPersistentDefaultGateway',
                'GevCurrentIPConfigurationDHCP',
                'GevCurrentIPConfigurationPersistentIP',
                'GevCurrentIPConfigurationLLA',
            );
            $cmd = self::ARV_TOOL . " control --name=" . escapeshellarg($name);
            foreach ($features as $f) {
                $cmd .= " " . escapeshellarg($f);
            }
            $cmd .= " 2>&1";
            $out = (string) self::sshExec($cmd);
            $values = self::parseArvFeatures($out);

            $mode = 'unknown';
            if (!empty($values['GevCurrentIPConfigurationDHCP'])) {
                $mode = 'dhcp';
            } else if (!empty($values['GevCurrentIPConfigurationPersistentIP'])) {
                $mode = 'static';
            }
            $result = array(
                'name'              => $name,
                'mode'              => $mode,
                'currentIp'         => $values['GevCurrentIPAddress'] ?? null,
                'currentMask'       => $values['GevCurrentSubnetMask'] ?? null,
                'currentGateway'    => $values['GevCurrentDefaultGateway'] ?? null,
                'persistentIp'      => $values['GevPersistentIPAddress'] ?? null,
                'persistentMask'    => $values['GevPersistentSubnetMask'] ?? null,
                'persistentGateway' => $values['GevPersistentDefaultGateway'] ?? null,
                'raw'               => $out,
            );
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse(true, $result);
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
    // DHCP:   set DHCP=true, PersistentIP=false
    // Static: write Persistent* values, set PersistentIP=true, DHCP=false
    private static function buildCameraCommands($data) {
        $name = $data['name'];
        $base = self::ARV_TOOL . " control --name=" . escapeshellarg($name);
        $cmds = array();
        if ($data['mode'] === 'static') {
            $cmds[] = $base . " " . escapeshellarg("GevPersistentIPAddress=" . $data['ip']);
            $cmds[] = $base . " " . escapeshellarg("GevPersistentSubnetMask=" . $data['mask']);
            if (!empty($data['gateway'])) {
                $cmds[] = $base . " " . escapeshellarg("GevPersistentDefaultGateway=" . $data['gateway']);
            }
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfigurationPersistentIP=true");
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfigurationDHCP=false");
        } else {
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfigurationDHCP=true");
            $cmds[] = $base . " " . escapeshellarg("GevCurrentIPConfigurationPersistentIP=false");
        }
        return $cmds;
    }
}
