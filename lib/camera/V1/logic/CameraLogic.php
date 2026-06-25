<?php
/**
*
* @author: N3 S.r.l.
*/

class CameraLogic
{
	public static function List() {

        $out = CameraLogic::ExecuteArvCommand();
        return $out;
	}

    public static function Reset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceReset");
        return $out;
	}

    public static function FactoryReset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceFactoryReset");
        return $out;
	}

    public static function Features() {

        $out = CameraLogic::ExecuteArvCommand("features");
        return $out;
	}

    public static function Values() {

        $out = CameraLogic::ExecuteArvCommand("values");
        return $out;
	}
    
    public static function Cmd($cmd) {

        $out = CameraLogic::ExecuteArvCommand($cmd);
        return $out;
	}

    public static function Bounds($cmd) {

        $out = CameraLogic::GetBounds($cmd);
        return $out;
	}

    /**
     * Ritorna informazioni hardware della camera SENZA interrogarla:
     *   - "configured": chiavi camera-rilevanti lette da configuration.cfg
     *     (statiche, sempre disponibili).
     *   - "hardware":   vendor/model/firmware/serial/ip/aravis-version estratti
     *     dai log freeture (sono righe scritte all'avvio di freeture quando
     *     enumera la camera). Robusto: non disturba l'acquisizione.
     */
    public static function HwInfo() {
        $hardware   = self::detectHardwareFromLogs();
        $configured = self::readConfiguredCameraKeys();
        return array(
            "res"  => true,
            "data" => array(
                "hardware"   => $hardware,
                "configured" => $configured,
            ),
        );
    }

    /**
     * Diagnostica rete nodo<->camera SENZA toccare la camera:
     *   - Trova NIC che routa verso la camera
     *   - Legge link speed/duplex/carrier/mtu da /sys (no sudo)
     *   - Legge counters da /sys/class/net/<nic>/statistics/ (no sudo)
     *   - Ping flood breve (50 pacchetti) per loss/RTT
     *   - ARP lookup per MAC camera
     *   - Calcola verdetti ok/warn/err per ciascuna metrica
     *
     * Tutto via SSH sull'host docker (i comandi sono Linux standard).
     */
    public static function NetDiag($cameraIp = null) {
        $result = array(
            'cameraIp' => $cameraIp,
            'nic'      => null,
            'link'     => array(),
            'counters' => array(),
            'ping'     => array(),
            'verdict'  => array(),
            'warnings' => array(),
        );

        // 1) Risolvi IP camera se non passato
        if (!$cameraIp) {
            $hw = self::detectHardwareFromLogs();
            if (!empty($hw['ip'])) {
                $cameraIp = $hw['ip'];
            }
            // Fallback: arv-tool list ritorna righe tipo "VENDOR-SERIAL (IP)"
            if (!$cameraIp) {
                $listOut = self::shellViaSsh("arv-tool-0.8 2>/dev/null");
                if (preg_match('/\((\d{1,3}(?:\.\d{1,3}){3})\)/', $listOut, $m)) {
                    $cameraIp = $m[1];
                }
            }
            $result['cameraIp'] = $cameraIp;
        }

        // 2) NIC verso la camera (ip route get) o prima NIC con carrier come fallback
        if ($cameraIp) {
            $route = self::shellViaSsh("ip route get " . escapeshellarg($cameraIp) . " 2>/dev/null");
            if (preg_match('/dev\s+(\S+)/', $route, $m)) {
                $result['nic'] = $m[1];
            }
        }
        if (!$result['nic']) {
            $candidates = self::shellViaSsh(
                "ls -1 /sys/class/net/ 2>/dev/null | grep -vE '^(lo|docker|tun|br-|veth|virbr)'"
            );
            foreach (preg_split('/\r?\n/', trim($candidates)) as $cand) {
                if ($cand === '') continue;
                $carrier = trim(self::shellViaSsh(
                    "cat /sys/class/net/" . escapeshellarg($cand) . "/carrier 2>/dev/null"
                ));
                if ($carrier === '1') {
                    $result['nic'] = $cand;
                    break;
                }
            }
        }
        if (!$result['nic']) {
            $result['warnings'][] = "Impossibile identificare la NIC verso la camera.";
            $result['verdict'] = self::computeNetDiagVerdict($result);
            return array("res" => true, "data" => $result);
        }

        $nic = $result['nic'];
        $nicEsc = escapeshellarg($nic);

        // 3) Link info via /sys (no sudo)
        $linkKeys = array('speed', 'duplex', 'carrier', 'operstate', 'mtu', 'address');
        $cmdLink = 'for f in ' . implode(' ', $linkKeys) . '; do '
                 . 'echo "$f=$(cat /sys/class/net/' . $nic . '/$f 2>/dev/null)"; done';
        $linkOut = self::shellViaSsh($cmdLink);
        foreach (preg_split('/\r?\n/', trim($linkOut)) as $line) {
            if (preg_match('/^(\w+)=(.*)$/', $line, $m)) {
                $result['link'][$m[1]] = $m[2];
            }
        }

        // 4) Counters via /sys/.../statistics/
        $statsKeys = array(
            'rx_packets','tx_packets','rx_bytes','tx_bytes',
            'rx_errors','tx_errors','rx_dropped','tx_dropped',
            'rx_crc_errors','rx_frame_errors','rx_length_errors',
            'rx_over_errors','rx_missed_errors','rx_fifo_errors',
            'collisions',
        );
        $cmdStats = 'for k in ' . implode(' ', $statsKeys) . '; do '
                  . 'v=$(cat /sys/class/net/' . $nic . '/statistics/$k 2>/dev/null); '
                  . '[ -n "$v" ] && echo "$k=$v"; done';
        $statsOut = self::shellViaSsh($cmdStats);
        foreach (preg_split('/\r?\n/', trim($statsOut)) as $line) {
            if (preg_match('/^(\w+)=(\d+)$/', $line, $m)) {
                $result['counters'][$m[1]] = (int) $m[2];
            }
        }

        // 5) Ping camera (50 pacchetti, ~2.5s)
        if ($cameraIp) {
            $pingOut = self::shellViaSsh(
                "ping -c 50 -i 0.05 -W 1 " . escapeshellarg($cameraIp) . " 2>&1"
            );
            $result['ping'] = self::parsePingOutput($pingOut);
        }

        // 6) MAC camera via ARP
        if ($cameraIp) {
            $arpOut = self::shellViaSsh("ip neigh show dev " . $nicEsc . " 2>/dev/null");
            $needle = preg_quote($cameraIp, '/');
            if (preg_match('/' . $needle . '\s+lladdr\s+(\S+)/', $arpOut, $m)) {
                $result['link']['cameraMac'] = $m[1];
            }
        }

        // 7) Switch via SNMP (fase 2): si attiva solo se _SWITCH_IP_ e' configurato.
        $cameraMac = isset($result['link']['cameraMac']) ? $result['link']['cameraMac'] : null;
        $nodeMac   = isset($result['link']['address'])   ? $result['link']['address']   : null;
        $result['switch'] = self::probeSwitchViaSnmp($cameraMac, $nodeMac);

        // 8) Coerenza Jumbo Frame end-to-end (NIC + path + switch). Calcolata su
        //    base ping -M do con size crescenti: identifica il max payload che
        //    sopravvive intero, quindi il PMTU effettivo del path camera<->nodo.
        //    Se _SWITCH_IP_ + HTTP password configurate, prova anche a leggere
        //    lo status jumbo dalla GUI dello switch (scraping).
        if ($cameraIp) {
            $result['jumbo'] = self::jumboFrameCheck($cameraIp, $result['link']);
        }

        $result['verdict'] = self::computeNetDiagVerdict($result);
        return array("res" => true, "data" => $result);
    }

    /**
     * Verifica coerenza Jumbo Frame end-to-end usando ping -M do (Don't Fragment)
     * con 3 size crescenti:
     *   - 1472 byte payload  = MTU 1500 (baseline, deve sempre passare)
     *   - 4472 byte payload  = MTU 4500 (jumbo parziale, rivela mismatch)
     *   - 8972 byte payload  = MTU 9000 (jumbo full)
     * Se ping a size N passa con DF set significa che TUTTI gli step del path
     * (NIC nodo, switch, camera) accettano frame N+28 byte. Il primo size che
     * fallisce identifica il PMTU.
     *
     * Aggiunge anche, se possibile, lo status jumbo dello switch via scraping
     * della web GUI (DGS-1210 non lo espone in SNMP standard).
     */
    private static function jumboFrameCheck($cameraIp, $link) {
        // Payload sizes scelte per coprire i 3 setting tipici delle camere
        // GigE Vision. Manteniamo il numero di ping basso (1 + 2 in caso di
        // primo retry) per non rallentare la diagnostica.
        $tests = array(
            array('size' => 1472, 'mtuLabel' => 1500, 'desc' => 'baseline (no jumbo)'),
            array('size' => 4472, 'mtuLabel' => 4500, 'desc' => 'jumbo parziale'),
            array('size' => 8972, 'mtuLabel' => 9000, 'desc' => 'jumbo full'),
        );
        $results = array();
        $maxOkPayload = 0;
        foreach ($tests as $t) {
            $size = (int) $t['size'];
            // -M do = Don't Fragment, -c 2 = 2 pacchetti (uno potrebbe perdersi),
            // -W 1 = timeout 1s. Catturiamo anche stderr per leggere il messaggio
            // "Frag needed and DF set" oppure "Message too long" lato kernel.
            $out = self::shellViaSsh(
                "ping -M do -s $size -c 2 -W 1 " . escapeshellarg($cameraIp) . " 2>&1"
            );
            $ok       = (preg_match('/\b(\d+) received/', $out, $m) && (int) $m[1] >= 1);
            $fragFlag = (stripos($out, 'frag needed') !== false || stripos($out, 'message too long') !== false);
            if ($ok) {
                $maxOkPayload = max($maxOkPayload, $size);
            }
            $results[] = array(
                'size'        => $size,
                'mtuLabel'    => (int) $t['mtuLabel'],
                'description' => $t['desc'],
                'ok'          => $ok,
                'fragNeeded'  => $fragFlag,
            );
        }

        $nicMtu = isset($link['mtu']) ? (int) $link['mtu'] : 0;

        // Status jumbo lato switch via HTTP scraping (best-effort).
        $switchJumbo = null;
        if (SwitchHttpClientLogic::isConfigured()) {
            $switchJumbo = SwitchHttpClientLogic::getJumboFrameStatus();
        }

        // Lettura "pigra" del packet size camera dal cache di HwInfoDeep:
        // se l'utente ha gia' eseguito "Lettura parametri completi" entro
        // l'ultima ora, riutilizziamo quei valori senza fermare freeture.
        // GevSCPSPacketSize e' il nome legacy GigE Vision, DeviceStreamChannel-
        // PacketSize e' il nome SFNC moderno (entrambi indicano la stessa cosa:
        // dimensione max del payload GVSP che la camera emette).
        $camera = array(
            'packetSize'   => null,   // dimensione payload (bytes)
            'packetSource' => null,   // nome del parametro GenICam letto
            'cacheAgeSec'  => null,   // eta del cache HwInfoDeep
            'cacheStale'   => false,
        );
        $cache = self::readHwInfoDeepCache();
        if ($cache !== null) {
            $camera['cacheAgeSec'] = $cache['ageSec'];
            $camera['cacheStale']  = $cache['ageSec'] > 600;  // >10min = un po' vecchio
            $candidates = array('GevSCPSPacketSize', 'DeviceStreamChannelPacketSize');
            foreach ($candidates as $key) {
                if (!empty($cache['live'][$key])) {
                    // I valori arrivano come stringhe ("8192", "8192 B", ...). Estraggo
                    // solo il primo numero.
                    if (preg_match('/(\d+)/', (string) $cache['live'][$key], $m)) {
                        $camera['packetSize']   = (int) $m[1];
                        $camera['packetSource'] = $key;
                        break;
                    }
                }
            }
        }

        // Verdict end-to-end
        //  - pathMtu = maxOkPayload + 28  (header IP+ICMP)
        //  - se pathMtu >= 9000 e nicMtu >= 9000  -> coherent jumbo
        //  - se pathMtu < nicMtu                  -> path-limited, NIC sopra il path
        //  - se pathMtu == 1500                   -> jumbo NON attivo end-to-end
        $pathMtu = $maxOkPayload > 0 ? ($maxOkPayload + 28) : null;
        $warnings = array();
        $level = 'ok';

        if ($pathMtu === null) {
            $level = 'warning';
            $warnings[] = "Nessun ping con DF e' passato: la camera non risponde o il path e' rotto.";
        } else {
            if ($nicMtu > 0 && $pathMtu < $nicMtu) {
                $level = 'warning';
                $warnings[] = "PMTU effettivo ($pathMtu) inferiore alla MTU della NIC ($nicMtu): c'e' uno step del path che limita il payload.";
            }
            if ($pathMtu <= 1500 && $nicMtu > 1500) {
                $level = 'warning';
                $warnings[] = "Jumbo Frame NON attivo end-to-end (PMTU <= 1500) nonostante la NIC sia MTU $nicMtu.";
            }
            if ($switchJumbo !== null && isset($switchJumbo['enabled']) && $switchJumbo['enabled'] === false && $nicMtu > 1500) {
                $warnings[] = "Switch ha Jumbo Frame DISABILITATO, ma la NIC del nodo e' MTU $nicMtu: configurazione incoerente, abilita Jumbo nel pannello System -> Jumbo Frame dello switch.";
                $level = 'warning';
            }
            // Confronto camera vs PMTU (solo se abbiamo il packet size dal cache).
            if ($camera['packetSize'] !== null && $pathMtu !== null) {
                $cps = $camera['packetSize'];
                // GVSP packet size include header IP+UDP+GVSP, quindi il limite
                // strict e' cameraPacketSize <= pathMtu. Se >, la camera droppera'
                // o frammentera' (a seconda di GevSCPSDoNotFragment).
                if ($cps > $pathMtu) {
                    $level = 'warning';
                    $warnings[] = "Camera packet size ({$cps}B, da " . $camera['packetSource'] . ") MAGGIORE del PMTU misurato ($pathMtu): la camera invia frame piu' grandi di quanto il path possa reggere -> drop o frammentazione.";
                }
                if ($cps <= 1500 && $nicMtu > 1500) {
                    $warnings[] = "Camera packet size ({$cps}B) NON sta sfruttando i jumbo: nonostante NIC=$nicMtu, la camera emette frame piccoli (stessa CPU, meno throughput).";
                    if ($level !== 'warning') $level = 'warning';
                }
            }
        }

        return array(
            'nicMtu'        => $nicMtu,
            'tests'         => $results,
            'maxOkPayload'  => $maxOkPayload,
            'pathMtu'       => $pathMtu,
            'switchJumbo'   => $switchJumbo,
            'camera'        => $camera,
            'level'         => $level,    // 'ok' | 'warning'
            'warnings'      => $warnings,
        );
    }

    // Interroga lo switch via SNMP (v2c) usando snmpget/snmpwalk sull'host docker.
    // Ritorna un payload con sysInfo + porte (ifTable) + match porta camera/nodo via FDB.
    // Se _SWITCH_IP_ e' vuoto, ritorna {configured: false} e basta.
    private static function probeSwitchViaSnmp($cameraMac, $nodeMac) {
        $r = array(
            'configured' => false,
            'ip'         => null,
            'reachable'  => false,
            'sysName'    => null,
            'sysDescr'   => null,
            'sysUpTime'  => null,
            'ports'      => array(),
            'cameraPort' => null,
            'nodePort'   => null,
            'warnings'   => array(),
        );
        if (!defined('_SWITCH_IP_') || trim(_SWITCH_IP_) === '') {
            return $r;
        }
        $ip        = _SWITCH_IP_;
        $community = defined('_SWITCH_SNMP_COMMUNITY_') && _SWITCH_SNMP_COMMUNITY_ !== ''
                   ? _SWITCH_SNMP_COMMUNITY_ : 'public';

        $r['configured'] = true;
        $r['ip']         = $ip;

        // Canary: uso sysDescr (sempre popolato dal firmware) invece di sysName,
        // perche' sysName puo' essere stringa vuota se l'admin non l'ha configurato.
        $sysDescr = self::snmpGetViaSsh($ip, $community, '1.3.6.1.2.1.1.1.0');
        if ($sysDescr === null) {
            $r['warnings'][] = "Switch SNMP ($ip) non risponde: community sbagliata, SNMP non abilitato, o IP irraggiungibile dal docker host.";
            return $r;
        }
        $r['reachable'] = true;
        $r['sysDescr']  = $sysDescr;
        $r['sysName']   = self::snmpGetViaSsh($ip, $community, '1.3.6.1.2.1.1.5.0');
        $r['sysUpTime'] = self::snmpGetViaSsh($ip, $community, '1.3.6.1.2.1.1.3.0');

        // ifTable walks
        $ifDescr      = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.2');
        $ifType       = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.3');
        $ifSpeed      = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.5');
        $ifOperStatus = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.8');
        $ifInOctets   = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.10');
        $ifInErrors   = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.14');
        $ifInDiscards = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.13');
        $ifOutOctets  = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.16');
        $ifOutErrors  = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.2.2.1.20');
        // EtherLike-MIB: CRC, alignment
        $dot3Fcs   = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.10.7.2.1.3');
        $dot3Align = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.10.7.2.1.2');

        // PoE: stato e consumo per porta. Standard POWER-ETHERNET-MIB (RFC 3621)
        // espone admin/detect/class/limit, NON il consumo realtime: per quello
        // serve la private MIB D-Link. probePoEViaSnmp() tenta entrambi.
        $poeByPort = self::probePoEViaSnmp($ip, $community);

        foreach ($ifDescr as $idx => $name) {
            // Solo porte ethernet fisiche (ifType=6 ethernetCsmacd).
            if (isset($ifType[$idx]) && (int) $ifType[$idx] !== 6) continue;
            $speedBps = isset($ifSpeed[$idx]) ? (int) $ifSpeed[$idx] : 0;
            $r['ports'][] = array(
                'ifIndex'     => (int) $idx,
                'name'        => $name,
                'speedMbps'   => $speedBps > 0 ? (int) round($speedBps / 1e6) : 0,
                'up'          => isset($ifOperStatus[$idx]) && (int) $ifOperStatus[$idx] === 1,
                'inOctets'    => isset($ifInOctets[$idx])   ? (int) $ifInOctets[$idx]   : 0,
                'outOctets'   => isset($ifOutOctets[$idx])  ? (int) $ifOutOctets[$idx]  : 0,
                'inErrors'    => isset($ifInErrors[$idx])   ? (int) $ifInErrors[$idx]   : 0,
                'outErrors'   => isset($ifOutErrors[$idx])  ? (int) $ifOutErrors[$idx]  : 0,
                'inDiscards'  => isset($ifInDiscards[$idx]) ? (int) $ifInDiscards[$idx] : 0,
                'fcsErrors'   => isset($dot3Fcs[$idx])   ? (int) $dot3Fcs[$idx]   : null,
                'alignErrors' => isset($dot3Align[$idx]) ? (int) $dot3Align[$idx] : null,
                'poe'         => isset($poeByPort[(int) $idx]) ? $poeByPort[(int) $idx] : null,
            );
        }
        // Indica alla UI se la branch PoE e' disponibile per QUALCHE porta.
        $r['poeAvailable'] = !empty($poeByPort);
        // Ordina per ifIndex
        usort($r['ports'], function ($a, $b) { return $a['ifIndex'] - $b['ifIndex']; });

        // Riassunto rapido
        $r['portsTotal']  = count($r['ports']);
        $r['portsUp']     = 0;
        foreach ($r['ports'] as $p) { if ($p['up']) $r['portsUp']++; }

        // Match porta camera/nodo/uplink via MAC FDB.
        // BRIDGE-MIB::dot1dTpFdbPort viene spesso ritornato VUOTO su switch con VLAN
        // management abilitata (es. DGS-1210 di default). In quel caso usiamo
        // Q-BRIDGE-MIB::dot1qTpFdbPort (suffix = vlanId + MAC).
        $fdb = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.17.4.3.1.2');
        $fdbKind = 'bridge';
        if (empty($fdb)) {
            $fdb = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.17.7.1.2.2.1.2');
            $fdbKind = 'q-bridge';
        }
        // dot1dBasePortIfIndex: bridgePort -> ifIndex
        $bridgePortToIf = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.17.1.4.1.2');
        $r['fdbSize']   = count($fdb);
        $r['fdbSource'] = $fdbKind;

        // Estrae il MAC (6 byte finali) dal suffix dell'OID FDB.
        // - BRIDGE-MIB: suffix = 6 byte MAC
        // - Q-BRIDGE-MIB: suffix = vlanId + 6 byte MAC (= 7 numeri)
        $extractMac = function ($suffix) {
            $parts = explode('.', $suffix);
            $n = count($parts);
            if ($n < 6) return null;
            $macParts = array_slice($parts, $n - 6); // ultimi 6
            return implode(':', array_map(function ($x) {
                return sprintf('%02x', (int) $x);
            }, $macParts));
        };

        // Mappa inversa: bridgePort -> array di MAC visti
        $portToMacs = array();
        foreach ($fdb as $suffix => $bridgePort) {
            $mac = $extractMac($suffix);
            if ($mac === null) continue;
            if (!isset($portToMacs[$bridgePort])) $portToMacs[$bridgePort] = array();
            $portToMacs[$bridgePort][] = $mac;
        }

        $lookupPort = function ($mac) use ($fdb, $bridgePortToIf, $extractMac) {
            if (!$mac) return null;
            $target = strtolower($mac);
            foreach ($fdb as $suffix => $bridgePort) {
                $found = $extractMac($suffix);
                if ($found === null) continue;
                if (strtolower($found) === $target) {
                    return isset($bridgePortToIf[$bridgePort])
                        ? (int) $bridgePortToIf[$bridgePort]
                        : (int) $bridgePort;
                }
            }
            return null;
        };

        $r['cameraPort'] = $lookupPort($cameraMac);
        $r['nodePort']   = $lookupPort($nodeMac);

        // Uplink: MAC del default gateway visto dal nodo.
        $gatewayIp = trim(self::shellViaSsh(
            "ip route show default 2>/dev/null | awk '{for (i=1; i<=NF; i++) if (\$i==\"via\") print \$(i+1)}' | head -1"
        ));
        $gatewayMac = null;
        if ($gatewayIp !== '') {
            // Provo a scaldare la cache ARP
            self::shellViaSsh("ping -c 1 -W 1 " . escapeshellarg($gatewayIp) . " >/dev/null 2>&1");
            $arpOut = self::shellViaSsh("ip neigh show " . escapeshellarg($gatewayIp) . " 2>/dev/null");
            if (preg_match('/lladdr\s+([0-9a-f:]+)/i', $arpOut, $m)) {
                $gatewayMac = strtolower($m[1]);
            }
        }
        $r['gatewayIp']  = $gatewayIp ?: null;
        $r['gatewayMac'] = $gatewayMac;
        $r['uplinkPort'] = $gatewayMac ? $lookupPort($gatewayMac) : null;

        // Mappa ifIndex -> bridgePort (inverso di $bridgePortToIf), serve per gli intrusi
        $ifIndexToBridge = array();
        foreach ($bridgePortToIf as $bp => $idx) {
            $ifIndexToBridge[(int) $idx] = $bp;
        }

        // Tre ruoli attesi sempre: camera, nodo, uplink.
        // Possono cadere sulla stessa porta (es. nodo "dietro" l'uplink): in tal
        // caso una porta porta piu' label. Una porta UP che non e' nessuno dei
        // tre = INTRUSO.
        $expectedSet = array();
        if ($r['cameraPort']) $expectedSet[(int) $r['cameraPort']] = true;
        if ($r['nodePort'])   $expectedSet[(int) $r['nodePort']]   = true;
        if ($r['uplinkPort']) $expectedSet[(int) $r['uplinkPort']] = true;
        $r['expectedPortSet']  = array_keys($expectedSet); // porte ifIndex attese (deduplicate)
        $r['expectedUpPorts']  = count($expectedSet);      // 1..3 a seconda di sovrapposizioni

        // Ruoli che NON siamo riusciti ad identificare via FDB
        $r['missingRoles'] = array();
        if (!$r['cameraPort']) $r['missingRoles'][] = 'camera';
        if (!$r['nodePort'])   $r['missingRoles'][] = 'nodo';
        if (!$r['uplinkPort']) $r['missingRoles'][] = 'uplink';

        // Topologia attesa: 3 ruoli su 3 porte fisiche DISTINTE. Qualunque
        // sovrapposizione e' violazione di topologia (es. nodo + uplink stessa
        // porta = nodo collegato fuori, non direttamente al DGS-1210).
        $r['topologyViolations'] = array();
        if ($r['cameraPort'] && $r['nodePort'] && (int) $r['cameraPort'] === (int) $r['nodePort']) {
            $r['topologyViolations'][] = "Camera e nodo sono visti sulla stessa porta (Port {$r['cameraPort']}): la camera non e' su una porta dedicata o c'e' uno switch intermedio.";
        }
        if ($r['cameraPort'] && $r['uplinkPort'] && (int) $r['cameraPort'] === (int) $r['uplinkPort']) {
            $r['topologyViolations'][] = "Camera e uplink sono visti sulla stessa porta (Port {$r['cameraPort']}): topologia sospetta (camera dietro l'uplink).";
        }
        if ($r['nodePort'] && $r['uplinkPort'] && (int) $r['nodePort'] === (int) $r['uplinkPort']) {
            $r['topologyViolations'][] = "Nodo e uplink sono visti sulla stessa porta (Port {$r['nodePort']}): il nodo NON e' direttamente collegato a questo switch ma e' fuori (collegato a un altro switch/router). La topologia attesa e' camera + nodo + uplink ognuno su una porta dedicata del DGS-1210.";
        }

        $r['intruders'] = array();
        foreach ($r['ports'] as $port) {
            if (!$port['up']) continue;
            if (isset($expectedSet[$port['ifIndex']])) continue;
            $bp = isset($ifIndexToBridge[$port['ifIndex']]) ? $ifIndexToBridge[$port['ifIndex']] : null;
            $macs = ($bp !== null && isset($portToMacs[$bp])) ? array_values(array_unique($portToMacs[$bp])) : array();
            $r['intruders'][] = array(
                'ifIndex'   => $port['ifIndex'],
                'name'      => $port['name'],
                'speedMbps' => $port['speedMbps'],
                'macs'      => $macs,
            );
        }
        $r['intruderCount'] = count($r['intruders']);

        // Velocita' sub-gigabit sulle porte di ruolo: campanello d'allarme.
        $roleByIfIndex = array();
        if ($r['cameraPort']) $roleByIfIndex[(int) $r['cameraPort']][] = 'camera';
        if ($r['nodePort'])   $roleByIfIndex[(int) $r['nodePort']][]   = 'nodo';
        if ($r['uplinkPort']) $roleByIfIndex[(int) $r['uplinkPort']][] = 'uplink';

        $r['speedWarnings'] = array();
        foreach ($r['ports'] as $port) {
            if (!$port['up']) continue;
            if (!isset($roleByIfIndex[$port['ifIndex']])) continue;
            $speed = (int) $port['speedMbps'];
            if ($speed > 0 && $speed < 1000) {
                $r['speedWarnings'][] = array(
                    'port'      => $port['ifIndex'],
                    'roles'     => $roleByIfIndex[$port['ifIndex']],
                    'speedMbps' => $speed,
                );
            }
        }

        // PoE: warning prominente se la camera e' UP via cavo ma NON sta tirando
        // alimentazione (0 W). Significa che la camera ha una sorgente esterna
        // di alimentazione: se l'alimentatore esterno salta, la camera si spegne
        // senza che lo switch possa avvertire.
        $r['poeWarnings'] = array();
        if (!empty($poeByPort) && $r['cameraPort']) {
            $cIdx = (int) $r['cameraPort'];
            $cameraPortRec = null;
            foreach ($r['ports'] as $p) {
                if ($p['ifIndex'] === $cIdx) { $cameraPortRec = $p; break; }
            }
            $cameraPoe = isset($poeByPort[$cIdx]) ? $poeByPort[$cIdx] : null;
            if ($cameraPortRec && $cameraPortRec['up'] && $cameraPoe !== null) {
                $powerW     = isset($cameraPoe['powerW'])      ? (float) $cameraPoe['powerW']      : null;
                $adminEna   = isset($cameraPoe['adminEnable']) ? (bool)  $cameraPoe['adminEnable'] : true;
                $delivering = isset($cameraPoe['delivering'])  ? (bool)  $cameraPoe['delivering']  : false;

                if (!$adminEna) {
                    $r['poeWarnings'][] = array(
                        'severity' => 'danger',
                        'port'     => $cIdx,
                        'reason'   => 'admin-disabled',
                        'message'  => "La camera e' collegata alla Port $cIdx ma il PoE su quella porta e' DISABILITATO nello switch. La camera sta usando un alimentatore esterno; se viene staccato la camera si spegne. Abilita PoE su questa porta dalla GUI dello switch.",
                    );
                } elseif ($powerW !== null && $powerW < 0.5) {
                    // Wattaggio noto e ~0: zero-watt confermato.
                    $r['poeWarnings'][] = array(
                        'severity' => 'danger',
                        'port'     => $cIdx,
                        'reason'   => 'zero-watt',
                        'message'  => "La camera e' UP via dati ma il PoE eroga " . number_format($powerW, 1) . " W sulla Port $cIdx: la camera e' alimentata da una sorgente esterna (alimentatore), NON dal cavo. Se l'alimentatore esterno salta, la camera si spegne.",
                    );
                } elseif ($powerW === null && !$delivering) {
                    // Watt sconosciuti (scrape fallito) MA tutti i segnali indiretti
                    // dicono "nessun PD" (status != delivering, class == default, no
                    // POWER ON): la camera non e' alimentata via PoE.
                    $statusLbl = isset($cameraPoe['statusLabel']) ? $cameraPoe['statusLabel'] : '?';
                    $r['poeWarnings'][] = array(
                        'severity' => 'danger',
                        'port'     => $cIdx,
                        'reason'   => 'not-delivering',
                        'message'  => "La camera e' UP via dati ma il PoE sulla Port $cIdx NON sta alimentando (stato: $statusLbl, class " . ($cameraPoe['class'] === null ? '?' : $cameraPoe['class']) . "). La camera e' alimentata da una sorgente esterna (alimentatore), NON dal cavo. Se l'alimentatore esterno salta, la camera si spegne.",
                    );
                }
            }
        }

        return $r;
    }

    /**
     * Sonda lo stato PoE delle porte dello switch. Combina:
     *  - POWER-ETHERNET-MIB (RFC 3621, standard): admin enable, class, status, limit
     *  - private MIB D-Link (DGS-1210 family): consumo realtime in Watt (non
     *    presente nello standard MIB)
     *
     * Ritorna map: ifIndex => array(
     *   'adminEnable' => bool|null
     *   'status'      => int|null
     *   'statusLabel' => 'disabled'|'searching'|'delivering'|'fault'|'test'|'other'|null
     *   'delivering'  => bool
     *   'class'       => 0..4|null
     *   'powerLimitW' => float|null
     *   'powerW'      => float|null
     *   'powerSource' => string|null
     * )
     */
    private static function probePoEViaSnmp($ip, $community) {
        $statusLabels = array(
            1 => 'disabled', 2 => 'searching', 3 => 'delivering',
            4 => 'fault', 5 => 'test', 6 => 'other',
        );

        // POWER-ETHERNET-MIB (RFC 3621): admin, detection-status, class.
        // NB: l'OID .105.1.1.1.11 NON e' powerLimit ma invalidSignatureCounter,
        // quindi lo ignoro. Il consumo realtime non e' nel MIB standard.
        $stdAdmin  = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.105.1.1.1.3');
        $stdStatus = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.105.1.1.1.6');
        $stdClass  = self::snmpWalkViaSsh($ip, $community, '1.3.6.1.2.1.105.1.1.1.10');

        // Watt realtime: scrape della GUI HTTP (il MIB privato del DGS-1210
        // firmware 6.30 non li espone). getPoEPortPower ritorna map
        // portNumber -> array('powerW', 'voltage', 'current', 'class', 'status').
        $httpPoe = array();
        if (SwitchHttpClientLogic::isConfigured()) {
            $httpPoe = SwitchHttpClientLogic::getPoEPortPower();
        }

        // suffix groupIndex.portIndex -> ifIndex. Su DGS-1210 portIndex == ifIndex
        // (porte 1..8 PoE, 9..10 uplink non-PoE).
        $suffixToIf = function ($suffix) {
            $parts = explode('.', $suffix);
            if (count($parts) >= 2) return (int) end($parts);
            return (int) $parts[0];
        };

        $result = array();
        $allSuffix = array();
        foreach (array($stdAdmin, $stdStatus, $stdClass) as $arr) {
            foreach ($arr as $s => $_) $allSuffix[$s] = true;
        }
        // Aggiungo anche gli ifIndex visti dalla GUI HTTP, nel caso SNMP non
        // sia disponibile per qualche motivo.
        foreach ($httpPoe as $ifIdx => $_) $allSuffix["1.$ifIdx"] = true;

        foreach (array_keys($allSuffix) as $suffix) {
            $ifIdx = $suffixToIf($suffix);
            if ($ifIdx <= 0) continue;
            $admin  = isset($stdAdmin[$suffix])  ? ((int) $stdAdmin[$suffix] === 1) : null;
            $status = isset($stdStatus[$suffix]) ? (int) $stdStatus[$suffix]        : null;
            $class  = isset($stdClass[$suffix])  ? (int) $stdClass[$suffix]         : null;

            // Watt realtime dal scrape GUI (l'unica fonte affidabile su DGS-1210).
            $powerW      = null;
            $powerSource = null;
            $httpStatus  = null;
            if (isset($httpPoe[$ifIdx])) {
                $h = $httpPoe[$ifIdx];
                if (isset($h['powerW']))    $powerW      = (float) $h['powerW'];
                if (isset($h['status']))    $httpStatus  = $h['status']; // 'POWER ON' / 'POWER OFF' / ...
                $powerSource = 'http-scrape';
            }

            // "delivering" = la porta sta erogando potenza a un PD.
            // Lo standard dice status==3, ma il firmware DGS-1210 6.30 usa
            // valori non-standard (visto in produzione: status=2 con
            // GUI=POWER ON e 2.8W). Combiniamo piu' segnali per essere
            // robusti su firmware diversi:
            //   1) Watt > 0.5 dal scrape HTTP -> SI, sta erogando
            //   2) GUI HTTP dice "POWER ON" -> SI
            //   3) status==3 (standard) -> SI
            //   4) class != null && class > 1 (=class0/default) -> PD detectato
            $delivering = false;
            if ($powerW !== null && $powerW >= 0.5) {
                $delivering = true;
            } elseif ($httpStatus !== null && stripos($httpStatus, 'POWER ON') !== false) {
                $delivering = true;
            } elseif ($status === 3) {
                $delivering = true;
            } elseif ($class !== null && $class > 1 && $admin === true) {
                $delivering = true;
            }

            $result[$ifIdx] = array(
                'adminEnable' => $admin,
                'status'      => $status,
                'statusLabel' => ($status !== null && isset($statusLabels[$status])) ? $statusLabels[$status] : null,
                'delivering'  => $delivering,
                'class'       => $class,
                'powerW'      => $powerW,
                'powerSource' => $powerSource,
                'httpStatus'  => $httpStatus,
            );
        }
        return $result;
    }

    // Wrapper sul client SNMP pure-PHP. Ritorna null se l'OID non e' presente o
    // se la richiesta va in timeout.
    private static function snmpGetViaSsh($ip, $community, $oid, $timeoutSec = 3) {
        return SnmpClientLogic::get($ip, $community, $oid, $timeoutSec);
    }

    // Wrapper sul walk SNMP pure-PHP. Ritorna map: suffix-oid (rispetto a $oid) -> value.
    private static function snmpWalkViaSsh($ip, $community, $oid, $timeoutSec = 5) {
        return SnmpClientLogic::walk($ip, $community, $oid, $timeoutSec);
    }

    // Converte "1c:0f:af:47:2f:ca" in suffisso OID "28.15.175.71.47.202"
    private static function macToOidSuffix($mac) {
        $mac = strtolower(trim($mac));
        $parts = preg_split('/[:\-]/', $mac);
        if (count($parts) !== 6) return null;
        $out = array();
        foreach ($parts as $p) {
            if (!preg_match('/^[0-9a-f]{1,2}$/', $p)) return null;
            $out[] = hexdec($p);
        }
        return implode('.', $out);
    }

    private static function parsePingOutput($out) {
        $p = array('rawTail' => '');
        if (preg_match('/(\d+)\s+packets transmitted,\s+(\d+)\s+received,\s+([\d.]+)%\s+packet loss/i', $out, $m)) {
            $p['transmitted'] = (int) $m[1];
            $p['received']    = (int) $m[2];
            $p['lossPct']     = (float) $m[3];
        }
        if (preg_match('/rtt min\/avg\/max\/mdev\s*=\s*([\d.]+)\/([\d.]+)\/([\d.]+)\/([\d.]+)\s*ms/', $out, $m)) {
            $p['rttMin']  = (float) $m[1];
            $p['rttAvg']  = (float) $m[2];
            $p['rttMax']  = (float) $m[3];
            $p['rttMdev'] = (float) $m[4];
        }
        // Ultime 8 righe per debug visibile
        $lines = preg_split('/\r?\n/', trim($out));
        $p['rawTail'] = implode("\n", array_slice($lines, -8));
        return $p;
    }

    // Classifica ogni metrica in {ok, warn, err} con motivazione.
    private static function computeNetDiagVerdict($r) {
        $v = array();
        $link = $r['link'];
        $c    = $r['counters'];

        // Link speed
        $speed = isset($link['speed']) ? (int) $link['speed'] : 0;
        $duplex = isset($link['duplex']) ? $link['duplex'] : '';
        $carrier = isset($link['carrier']) ? $link['carrier'] : '';
        if ($carrier === '0' || isset($link['operstate']) && strtolower($link['operstate']) === 'down') {
            $v[] = self::verd('Link NIC', 'DOWN', 'err', 'NIC senza carrier');
        } elseif ($speed >= 1000) {
            $v[] = self::verd('Velocita link NIC', $speed . ' Mb/s ' . $duplex, 'ok', null);
        } elseif ($speed >= 100) {
            $v[] = self::verd('Velocita link NIC', $speed . ' Mb/s ' . $duplex, 'warn',
                'Atteso 1 Gbps. Cavo Cat5/connettore o porta switch a 100 Mb/s.');
        } elseif ($speed > 0) {
            $v[] = self::verd('Velocita link NIC', $speed . ' Mb/s ' . $duplex, 'err',
                'Link degradato sotto i 100 Mb/s.');
        } else {
            $v[] = self::verd('Velocita link NIC', 'sconosciuta', 'warn',
                'NIC non e\' riportata da /sys (forse virtual o down)');
        }

        if ($duplex === 'half') {
            $v[] = self::verd('Duplex', 'half', 'err',
                'Half duplex su gigabit non e\' normale, indica auto-negotiation mismatch.');
        }

        // Rapporto errori RX
        $rxPackets = isset($c['rx_packets']) ? $c['rx_packets'] : 0;
        $rxErrors  = isset($c['rx_errors'])  ? $c['rx_errors']  : 0;
        if ($rxPackets > 0) {
            $pct = ($rxErrors / $rxPackets) * 100;
            $stat = $pct < 0.001 ? 'ok' : ($pct < 0.1 ? 'warn' : 'err');
            $hint = $stat === 'ok' ? null
                : ($pct >= 0.1 ? 'Rumore elevato sul link: cavo/EMI sospetti.'
                              : 'Errori presenti ma marginali, monitorare nel tempo.');
            $v[] = self::verd('Tasso errori RX',
                sprintf('%.4f%% (%s/%s)', $pct, number_format($rxErrors,0,'.',"'"), number_format($rxPackets,0,'.',"'")),
                $stat, $hint);
        }

        // CRC errors (cavo difettoso classico)
        if (!empty($c['rx_crc_errors'])) {
            $v[] = self::verd('rx_crc_errors', $c['rx_crc_errors'], 'err',
                'Frame con CRC sbagliato: cavo, connettori o EMI.');
        }
        if (!empty($c['rx_length_errors'])) {
            $v[] = self::verd('rx_length_errors', $c['rx_length_errors'], 'warn',
                'Frame oversize: MTU mismatch (la camera invia jumbo?) o NIC/driver.');
        }
        if (!empty($c['rx_missed_errors'])) {
            $v[] = self::verd('rx_missed_errors', $c['rx_missed_errors'], 'warn',
                'NIC ha perso frame (CPU/bus saturi o RX ring troppo piccolo).');
        }
        if (!empty($c['rx_over_errors'])) {
            $v[] = self::verd('rx_over_errors', $c['rx_over_errors'], 'warn',
                'Overruns sul buffer NIC.');
        }
        if (!empty($c['collisions'])) {
            $v[] = self::verd('collisions', $c['collisions'], 'err',
                'Collisioni su gigabit full-duplex non dovrebbero esistere.');
        }

        // Ping
        if (!empty($r['ping'])) {
            $loss = isset($r['ping']['lossPct']) ? $r['ping']['lossPct'] : null;
            if ($loss !== null) {
                $stat = $loss == 0 ? 'ok' : ($loss < 1 ? 'warn' : 'err');
                $v[] = self::verd('Ping packet loss', sprintf('%.1f%%', $loss), $stat,
                    $loss > 0 ? 'Pacchetti persi tra nodo e camera.' : null);
            }
            if (isset($r['ping']['rttAvg'])) {
                $rtt = $r['ping']['rttAvg'];
                $stat = $rtt < 1 ? 'ok' : ($rtt < 5 ? 'warn' : 'err');
                $v[] = self::verd('RTT medio', sprintf('%.2f ms', $rtt), $stat, null);
            }
            if (isset($r['ping']['rttMdev'])) {
                $j = $r['ping']['rttMdev'];
                $stat = $j < 0.5 ? 'ok' : ($j < 2 ? 'warn' : 'err');
                $v[] = self::verd('Jitter', sprintf('%.2f ms', $j), $stat,
                    $j >= 2 ? 'Jitter alto: possibile saturazione link o congestione switch.' : null);
            }
        }

        return $v;
    }

    private static function verd($label, $value, $status, $hint) {
        return array('label' => $label, 'value' => (string) $value, 'status' => $status, 'hint' => $hint);
    }

    private static function shellViaSsh($cmd) {
        $session = @ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        if (!$session) return '';
        if (!@ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
            unset($session);
            return '';
        }
        $stream = @ssh2_exec($session, $cmd);
        if (!$stream) { unset($session); return ''; }
        stream_set_blocking($stream, true);
        $out = stream_get_contents(ssh2_fetch_stream($stream, SSH2_STREAM_STDIO));
        unset($session);
        return (string) $out;
    }

    /**
     * Probe esplorativo della MIB cable-diagnostics dello switch. Gli OID precisi
     * cambiano col firmware DGS-1210 (es. .10.76.X.7 vs .10.153.X.X). Facciamo walk
     * su una lista di candidate D-Link e ritorniamo cio' che risponde, lasciando
     * al chiamante (o a noi nella prossima iterazione) il compito di interpretare
     * la struttura. Utile per non dover indovinare a freddo gli OID.
     */
    /**
     * Triggera Cable Diagnostic (TDR) sulla porta indicata via scraping della
     * web GUI dello switch. Il firmware DGS-1210 6.30.x non espone il test cavo
     * via SNMP, quindi questo e' l'unico modo di ottenere stato + lunghezza per
     * ciascuna delle 4 coppie senza far loggare l'utente nella GUI dello switch.
     */
    public static function SwitchCableDiag($port) {
        $port = (int) $port;
        if ($port <= 0) {
            return array('res' => false, 'data' => array('error' => 'Porta non valida.'));
        }
        if (!SwitchHttpClientLogic::isConfigured()) {
            return array('res' => false, 'data' => array('error' => 'Switch HTTP non configurato (_SWITCH_IP_ / _SWITCH_HTTP_PASSWORD_).'));
        }
        $diag = SwitchHttpClientLogic::cableDiag($port);
        if (empty($diag['ok'])) {
            // Propago error + trace + raw per debug lato UI.
            return array('res' => false, 'data' => array(
                'error' => isset($diag['error']) ? $diag['error'] : 'Errore sconosciuto.',
                'trace' => isset($diag['trace']) ? $diag['trace'] : null,
                'raw'   => isset($diag['raw'])   ? $diag['raw']   : null,
            ));
        }
        return array('res' => true, 'data' => $diag);
    }

    /**
     * Abilita o disabilita una porta dello switch via scraping HTTP.
     * $action: 'enable' / 'disable'. Spegnere-poi-riaccendere e' un modo
     * rapido per forzare la rinegoziazione del link (utile quando una
     * camera resta su 100 Mb/s).
     */
    public static function SwitchPortAdmin($port, $action) {
        $port = (int) $port;
        if ($port <= 0) {
            return array('res' => false, 'data' => array('error' => 'Porta non valida.'));
        }
        $action = strtolower(trim((string) $action));
        if ($action !== 'enable' && $action !== 'disable') {
            return array('res' => false, 'data' => array('error' => "Action non valida: '$action'. Usa 'enable' o 'disable'."));
        }
        if (!SwitchHttpClientLogic::isConfigured()) {
            return array('res' => false, 'data' => array('error' => 'Switch HTTP non configurato.'));
        }
        $result = SwitchHttpClientLogic::setPortAdmin($port, $action === 'enable');
        if (empty($result['ok'])) {
            return array('res' => false, 'data' => array(
                'error' => isset($result['error']) ? $result['error'] : 'Errore sconosciuto.',
                'trace' => isset($result['trace']) ? $result['trace'] : null,
                'raw'   => isset($result['raw'])   ? $result['raw']   : null,
            ));
        }
        return array('res' => true, 'data' => $result);
    }

    public static function ExploreSwitchCableDiag() {
        $r = array(
            'configured' => false,
            'ip'         => null,
            'branches'   => array(),
        );
        if (!defined('_SWITCH_IP_') || trim(_SWITCH_IP_) === '') {
            return array('res' => true, 'data' => $r);
        }
        $ip = _SWITCH_IP_;
        $c  = defined('_SWITCH_SNMP_COMMUNITY_') && _SWITCH_SNMP_COMMUNITY_ !== ''
            ? _SWITCH_SNMP_COMMUNITY_ : 'public';
        $r['configured'] = true;
        $r['ip']         = $ip;

        // Branch da esplorare. Filtriamo per nome o pattern in modo da non ritornare
        // tutta la MIB enterprise (sarebbero migliaia di entry).
        $branches = array(
            // sysObjectID DGS-1210-10P/F1 = 1.3.6.1.4.1.171.10.153.1.1
            array('oid' => '1.3.6.1.4.1.171.10.153.1.1', 'desc' => 'DGS-1210-10P/F1 specific subtree'),
            // Branch DGS series "10.76" (DES-1210 / DGS-1210 vecchio MIB)
            array('oid' => '1.3.6.1.4.1.171.10.76',     'desc' => 'D-Link DGS series legacy MIB'),
            // Branch DGS-1210 common (12.58)
            array('oid' => '1.3.6.1.4.1.171.12.58',     'desc' => 'D-Link DGS common MIB'),
            // Cable Diagnostics generico (10.76 series)
            array('oid' => '1.3.6.1.4.1.171.11.62',     'desc' => 'D-Link smart switch MIB candidate'),
        );

        foreach ($branches as $b) {
            $walked = SnmpClientLogic::walk($ip, $c, $b['oid'], 4, 1000);
            // Trasformiamo in array enumerabile (k = suffix dell'OID, v = valore)
            $entries = array();
            $i = 0;
            foreach ($walked as $suffix => $val) {
                $entries[] = array(
                    'oid'   => '.' . trim($b['oid'], '.') . '.' . $suffix,
                    'value' => is_string($val) ? $val : (string) $val,
                );
                if (++$i >= 200) break; // safety
            }
            $r['branches'][] = array(
                'base'    => '.' . trim($b['oid'], '.'),
                'desc'    => $b['desc'],
                'count'   => count($walked),
                'sample'  => $entries,
            );
        }
        return array('res' => true, 'data' => $r);
    }

    /**
     * Probe esplorativo della MIB PoE. Walka:
     *  - POWER-ETHERNET-MIB standard (RFC 3621) per confronto
     *  - branch private D-Link DGS-1210 candidates per il consumo realtime
     *
     * Utile per identificare l'OID del consumo PoE realtime su un firmware
     * specifico quando probePoEViaSnmp() non riesce a leggerlo. Mostra valori
     * grezzi: l'utente puo' confrontare con la GUI dello switch (PoE -> Port
     * Settings, colonna "Power consumption") per matchare l'OID giusto.
     */
    public static function ExploreSwitchPoE() {
        $r = array(
            'configured' => false,
            'ip'         => null,
            'branches'   => array(),
        );
        if (!defined('_SWITCH_IP_') || trim(_SWITCH_IP_) === '') {
            return array('res' => true, 'data' => $r);
        }
        $ip = _SWITCH_IP_;
        $c  = defined('_SWITCH_SNMP_COMMUNITY_') && _SWITCH_SNMP_COMMUNITY_ !== ''
            ? _SWITCH_SNMP_COMMUNITY_ : 'public';
        $r['configured'] = true;
        $r['ip']         = $ip;

        // Mix di OID standard (per confronto) e candidate private MIB.
        $branches = array(
            // STANDARD POWER-ETHERNET-MIB (RFC 3621): admin, status, class, limit
            array('oid' => '1.3.6.1.2.1.105.1.1.1.3',  'desc' => 'POWER-ETHERNET-MIB pethPsePortAdminEnable (1=enable, 2=disable)'),
            array('oid' => '1.3.6.1.2.1.105.1.1.1.6',  'desc' => 'POWER-ETHERNET-MIB pethPsePortDetectionStatus (1=disabled, 2=searching, 3=deliveringPower, 4=fault, 5=test, 6=otherFault)'),
            array('oid' => '1.3.6.1.2.1.105.1.1.1.10', 'desc' => 'POWER-ETHERNET-MIB pethPsePortPowerClassifications (0..4)'),
            array('oid' => '1.3.6.1.2.1.105.1.1.1.11', 'desc' => 'POWER-ETHERNET-MIB pethPsePortPowerLimit (mW)'),
            // PRIVATE D-Link: candidate per consumo realtime
            array('oid' => '1.3.6.1.4.1.171.10.76.10.10.1.1.4', 'desc' => 'D-Link DGS-1210 candidate A (.10.76.10.10.1.1.4)'),
            array('oid' => '1.3.6.1.4.1.171.10.76.11.10.1.1.4', 'desc' => 'D-Link DGS-1210 candidate B (.10.76.11.10.1.1.4)'),
            array('oid' => '1.3.6.1.4.1.171.10.76.12.10.1.1.4', 'desc' => 'D-Link DGS-1210 candidate C (.10.76.12.10.1.1.4)'),
            array('oid' => '1.3.6.1.4.1.171.11.153.1000.10.1.1.4', 'desc' => 'D-Link DGS-1210 candidate D (.11.153.1000.10.1.1.4)'),
            // Branch piu' larghe per scoperta esplorativa (limite 200 entry per evitare flood)
            array('oid' => '1.3.6.1.4.1.171.10.76.10.10', 'desc' => 'D-Link DGS-1210 PoE branch .10.76.10.10 (esplorativo)'),
            array('oid' => '1.3.6.1.4.1.171.10.153.1.1.10', 'desc' => 'DGS-1210-10P/F1 specific PoE subtree (esplorativo)'),
        );

        foreach ($branches as $b) {
            $walked = SnmpClientLogic::walk($ip, $c, $b['oid'], 4, 1000);
            $entries = array();
            $i = 0;
            foreach ($walked as $suffix => $val) {
                $entries[] = array(
                    'oid'   => '.' . trim($b['oid'], '.') . '.' . $suffix,
                    'value' => is_string($val) ? $val : (string) $val,
                );
                if (++$i >= 200) break; // safety
            }
            $r['branches'][] = array(
                'base'    => '.' . trim($b['oid'], '.'),
                'desc'    => $b['desc'],
                'count'   => count($walked),
                'sample'  => $entries,
            );
        }

        // PROBE HTTP delle pagine PoE Port Settings della GUI: il MIB privato
        // del DGS-1210 6.30 non espone i Watt realtime, quindi servono dal
        // scrape HTTP. Provo diversi URL candidati e ritorno status/size/head
        // di ciascuno cosi' l'utente identifica subito quale e' quello giusto.
        $r['httpProbes'] = array();
        if (SwitchHttpClientLogic::isConfigured()) {
            $r['httpProbes'] = SwitchHttpClientLogic::exploreHttpPoEPaths();
        }

        return array('res' => true, 'data' => $r);
    }

    /**
     * Lettura "deep" dei parametri camera via arv-tool values.
     * GenICam permette un solo controller alla volta, quindi ferma freeture per
     * la durata della lettura e lo riavvia subito dopo (finally garantisce il restart
     * anche se il parsing fallisce). Tempo tipico: 3-6 secondi.
     */
    public static function HwInfoDeep($ip = null) {
        @set_time_limit(60);
        $result = array(
            'live'      => array(),
            'raw'       => '',
            'cameraIp'  => null,
            'pausedSec' => null,
            'warnings'  => array(),
        );

        // Se non ho un IP esplicito, provo a usare quello che il parser dei log ha
        // gia' trovato (campo GevCurrentIPAddress non viene da li' ma e' meglio di niente).
        if (!$ip) {
            $detected = self::detectHardwareFromLogs();
            if (!empty($detected['ip'])) {
                $ip = $detected['ip'];
            }
        }
        $result['cameraIp'] = $ip;

        $startTs = microtime(true);
        $stopOk  = false;
        try {
            // 1) Stop freeture per liberare la sessione GenICam.
            DockerApiLogic::sshContainerStop("freeture");
            $stopOk = true;
            // 2) Piccola attesa per assicurarsi che il control channel sia rilasciato.
            usleep(800 * 1000);

            // 3) Dump arv-tool values.
            $raw = self::runArvValuesRaw($ip);
            $result['raw']  = $raw;
            if ($raw === '' || stripos($raw, 'error') === 0 || stripos($raw, 'no device') !== false) {
                $result['warnings'][] = "arv-tool non ha prodotto output utile (camera non raggiungibile?)";
            }
            $parsed = self::parseArvValues($raw);
            $result['live']        = $parsed['values'];
            $result['parserUsed']  = $parsed['parser'];

            // Cache su filesystem dei valori live: usato da NetDiag/jumbo per
            // chiudere il verdict sul terzo segmento (camera) senza dover rifare
            // un readout deep (che fermerebbe freeture di nuovo).
            if (!empty($result['live'])) {
                self::writeHwInfoDeepCache($result['live'], $ip);
            }
        } catch (\Throwable $t) {
            error_log("[HwInfoDeep] EXCEPTION " . get_class($t) . ": " . $t->getMessage());
            $result['warnings'][] = "Eccezione: " . $t->getMessage();
        } finally {
            // 4) Riavvio freeture comunque (cintura di sicurezza).
            if ($stopOk) {
                DockerApiLogic::sshContainerStart("freeture");
            }
        }
        $result['pausedSec'] = round(microtime(true) - $startTs, 2);

        return array("res" => true, "data" => $result);
    }

    /**
     * Path del file di cache del HwInfoDeep. tmp dir del container PHP.
     */
    private static function hwInfoDeepCachePath() {
        return rtrim(sys_get_temp_dir(), '/\\') . '/camera_hwinfo_deep.json';
    }

    private static function writeHwInfoDeepCache(array $live, $cameraIp) {
        @file_put_contents(self::hwInfoDeepCachePath(), json_encode(array(
            'ts'       => time(),
            'cameraIp' => $cameraIp,
            'live'     => $live,
        )), LOCK_EX);
    }

    /**
     * Legge l'ultimo cache di HwInfoDeep se presente. Ritorna null se assente
     * o piu' vecchio di $maxAgeSec (default 1h: dopo quel tempo i valori GenICam
     * possono essere cambiati senza che l'utente se ne accorga).
     */
    private static function readHwInfoDeepCache($maxAgeSec = 3600) {
        $f = self::hwInfoDeepCachePath();
        if (!file_exists($f)) return null;
        $raw  = @file_get_contents($f);
        $data = @json_decode($raw, true);
        if (!is_array($data) || empty($data['live'])) return null;
        $age = time() - (int) (isset($data['ts']) ? $data['ts'] : 0);
        if ($age > $maxAgeSec) return null;
        $data['ageSec'] = $age;
        return $data;
    }

    // Esegue `arv-tool-0.8 [-a IP] values` via SSH e ritorna l'output grezzo.
    private static function runArvValuesRaw($ip = null) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        if (!$session) return '';
        $out = '';
        if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
            $host = $ip ? ('-a ' . escapeshellarg($ip)) : '';
            $cmd  = "arv-tool-0.8 $host values 2>&1";
            $stream = ssh2_exec($session, $cmd);
            if ($stream) {
                stream_set_blocking($stream, true);
                $out = stream_get_contents(ssh2_fetch_stream($stream, SSH2_STREAM_STDIO));
            }
        }
        unset($session);
        return (string) $out;
    }

    // Estrae da $raw solo le feature GenICam in whitelist e applica post-processing
    // (decode IP/MAC packed, pretty-print Mbps/Gbps).
    //
    // Ritorna ['values' => array<string,string>, 'parser' => 'name-strategy-usata'].
    //
    // Strategie multiple in cascata (la prima che matcha almeno una chiave vince).
    // arv-tool-0.8 (Aravis 0.8, usato con Lucid) produce:
    //   "  StringReg : 'DeviceVendorName' = 'Lucid Vision Labs'"
    //   "  Integer   : 'DeviceLinkSpeed' = 125000000 Bps"
    // Altri tool (pylon-tools per Basler, eventuale arv-tool vecchio) possono usare
    // formati piu' semplici "Feature = value" o "Feature: value": li proviamo come
    // fallback. La whitelist filtra in ogni caso, quindi falsi positivi sono difficili.
    private static function parseArvValues($raw) {
        $whitelist = array(
            // Identita'
            'DeviceVendorName', 'DeviceModelName', 'DeviceVersion',
            'DeviceManufacturerInfo', 'DeviceSerialNumber',
            'DeviceSFNCVersionMajor', 'DeviceSFNCVersionMinor', 'DeviceSFNCVersionSubMinor',
            'DeviceTLType',
            // Link & throughput
            'DeviceMaxThroughput', 'DeviceLinkSpeed',
            'DeviceLinkThroughputLimitMode', 'DeviceLinkThroughputLimit', 'DeviceLinkThroughputReserve',
            'DeviceStreamChannelPacketSize',
            'GevSCPSPacketSize', 'GevSCPD',
            // Acquisizione
            'AcquisitionMode', 'AcquisitionFrameRate', 'AcquisitionFrameRateEnable',
            'AcquisitionFrameRateLinkLimitEnable',
            'ExposureTime', 'ExposureAuto', 'Gain', 'GainAuto', 'BlackLevel',
            'PixelFormat', 'Width', 'Height', 'OffsetX', 'OffsetY',
            'TriggerMode', 'ADCBitDepth', 'SensorShutterMode',
            // Sensore
            'SensorWidth', 'SensorHeight', 'PhysicalPixelSize',
            // Runtime
            'DeviceTemperature', 'DevicePower', 'DeviceUpTime', 'LinkUpTime',
            // GigE
            'GevCurrentIPAddress', 'GevCurrentSubnetMask', 'GevCurrentDefaultGateway',
            'GevMACAddress',
        );
        $wantSet = array_flip($whitelist);

        $strategies = array(
            // 1) arv-tool-0.8 / Aravis (Lucid e qualunque vendor GigE Vision via Aravis):
            //    "    StringReg   : 'DeviceVendorName' = 'Lucid Vision Labs'"
            //    Ordine: <Tipo> : '<Feature>' = <valore>
            array(
                'name'  => 'aravis-typed',
                'regex' => '/^\s*(?:StringReg|Integer|Float|Boolean|Enumeration|Register|String|Command)\s*:\s*\'([^\']+)\'\s*=\s*(.+?)\s*$/',
            ),
            // 2) Formato semplice "Feature = value" (pylon-viewer Basler e arv-tool legacy):
            //    "DeviceVendorName = Basler"
            array(
                'name'  => 'plain-equals',
                'regex' => '/^\s*([A-Z][A-Za-z0-9_]*)\s*=\s*(.+?)\s*$/',
            ),
            // 3) Formato "Feature: value" (varianti pylon-tool):
            //    "DeviceVendorName: Basler"
            array(
                'name'  => 'plain-colon',
                'regex' => '/^\s*([A-Z][A-Za-z0-9_]*)\s*:\s*(.+?)\s*$/',
            ),
        );

        $lines = preg_split('/\r?\n/', $raw);
        $parserUsed = null;
        $out = array();

        foreach ($strategies as $strat) {
            $tmp = array();
            foreach ($lines as $line) {
                if ($line === '') continue;
                if (!preg_match($strat['regex'], $line, $m)) continue;
                $key = $m[1];
                if (!isset($wantSet[$key])) continue;
                $val = trim($m[2]);
                // Strip apici singoli intorno a stringhe: "'Lucid Vision Labs'" -> "Lucid Vision Labs"
                if (strlen($val) >= 2 && $val[0] === "'" && substr($val, -1) === "'") {
                    $val = substr($val, 1, -1);
                }
                $tmp[$key] = $val;
            }
            if (!empty($tmp)) {
                $parserUsed = $strat['name'];
                $out = $tmp;
                break;
            }
        }

        if (empty($out)) {
            error_log("[parseArvValues] no strategy matched any whitelisted key");
            return array('values' => array(), 'parser' => null);
        }
        error_log("[parseArvValues] strategy='$parserUsed' matched " . count($out) . " keys");

        // Post-processing: decodifica packed int -> IP/MAC, pretty-print bit-rate.
        $out = self::postprocessGenicamValues($out);

        return array('values' => $out, 'parser' => $parserUsed);
    }

    private static function postprocessGenicamValues(array $out) {
        foreach ($out as $key => $val) {
            if ($key === 'GevCurrentIPAddress' || $key === 'GevCurrentSubnetMask' || $key === 'GevCurrentDefaultGateway') {
                $out[$key] = self::decodePackedIp($val);
            } elseif ($key === 'GevMACAddress') {
                $out[$key] = self::decodePackedMac($val);
            } elseif ($key === 'DeviceLinkSpeed' || $key === 'DeviceLinkThroughputLimit' || $key === 'DeviceMaxThroughput') {
                // SFNC: DeviceLinkSpeed e simili sono in Byte/secondo (Bps), NON bit/s.
                // Mostro in Gbps (per il link) + MB/s (per il throughput) per chiarezza.
                $out[$key] = self::formatBytesPerSecond($val);
            }
        }
        return $out;
    }

    // Converte un valore Bps (byte/secondo, come da SFNC GenICam) in "X Gbps (Y MB/s)".
    private static function formatBytesPerSecond($val) {
        $bytesPerSec = self::parseNumber($val);
        if ($bytesPerSec === null || $bytesPerSec <= 0) {
            return $val;
        }
        $mbps = $bytesPerSec / 1e6;        // MB/s
        $gbps = ($bytesPerSec * 8) / 1e9;  // Gbit/s

        if ($gbps >= 1) {
            return sprintf('%.2f Gbps (%.1f MB/s, %s Bps)',
                $gbps, $mbps, self::formatInt($bytesPerSec));
        }
        $mbits = ($bytesPerSec * 8) / 1e6;
        return sprintf('%.0f Mbps (%.1f MB/s, %s Bps)',
            $mbits, $mbps, self::formatInt($bytesPerSec));
    }

    private static function formatInt($n) {
        return number_format((float) $n, 0, '.', "'"); // 1'000'000'000 stile europeo
    }

    private static function parseNumber($val) {
        $val = trim((string) $val);
        if (strpos($val, '0x') === 0) return hexdec(substr($val, 2));
        if (is_numeric($val)) return $val + 0; // int o float
        // estrazione del primo numero presente
        if (preg_match('/-?\d+(?:\.\d+)?/', $val, $m)) return $m[0] + 0;
        return null;
    }

    private static function decodePackedIp($val) {
        $n = self::parseNumber($val);
        if ($n === null) return $val;
        return long2ip((int) $n);
    }

    private static function decodePackedMac($val) {
        $n = self::parseNumber($val);
        if ($n === null) return $val;
        $n = (int) $n;
        return sprintf('%02x:%02x:%02x:%02x:%02x:%02x',
            ($n >> 40) & 0xFF, ($n >> 32) & 0xFF,
            ($n >> 24) & 0xFF, ($n >> 16) & 0xFF,
            ($n >> 8)  & 0xFF, $n & 0xFF
        );
    }

    // Legge da configuration.cfg le chiavi di interesse per identificare la camera.
    private static function readConfiguredCameraKeys() {
        $keysMap = array(
            'CAMERA'        => 'camera',
            'INSTRUME'      => 'instrument',
            'TELESCOP'      => 'telescope',
            'CAMERA_ID'     => 'cameraId',
            'ACQ_FORMAT'    => 'format',
            'ACQ_RES_SIZE'  => 'resolution',
            'ACQ_FPS'       => 'fps',
        );
        $out = array();
        foreach ($keysMap as $alias) { $out[$alias] = null; }

        $freetureConf = _FREETURE_;
        if (!file_exists($freetureConf) || !is_file($freetureConf)) {
            return $out;
        }
        foreach (file($freetureConf) as $line) {
            if (!isset($line) || $line === '' || $line[0] === '#' || $line[0] === "\n" || $line[0] === "\t") {
                continue;
            }
            if (strpos($line, '=') === false) continue;
            $parts = explode('=', $line, 2);
            $key   = trim($parts[0]);
            if (!isset($keysMap[$key])) continue;
            $val = $parts[1];
            $hashPos = strpos($val, '#'); // strip inline comments
            if ($hashPos !== false) $val = substr($val, 0, $hashPos);
            $out[$keysMap[$key]] = trim($val);
        }
        return $out;
    }

    // Estrae vendor/model/firmware/serial/ip/aravis dai log freeture, prendendo
    // sempre l'ultima occorrenza (= dopo l'ultimo restart). Non disturba la camera.
    private static function detectHardwareFromLogs() {
        $out = array(
            'vendor'     => null,
            'model'      => null,
            'firmware'   => null,
            'serial'     => null,
            'ip'         => null,
            'aravis'     => null,
            'lastSeenAt' => null,
        );

        $stationCode = CoreLogic::GetStationCode();
        $logsDir     = _FREETURE_DATA_ . $stationCode . "/logs/";
        if (!is_dir($logsDir)) {
            return $out;
        }

        $patterns = array(
            'vendor'   => '/(?:DeviceVendorName|vendor(?:\s+name)?)\s*[:=]\s*([A-Za-z0-9_.\-\s]+?)\s*(?:[\[\]\|;,]|$)/i',
            'model'    => '/(?:DeviceModelName|model(?:\s+name)?)\s*[:=]\s*([A-Za-z0-9_.\-\s]+?)\s*(?:[\[\]\|;,]|$)/i',
            'firmware' => '/(?:DeviceFirmwareVersion|firmware(?:\s+version)?)\s*[:=]\s*([A-Za-z0-9_.\-]+)/i',
            'serial'   => '/(?:DeviceSerialNumber|serial(?:\s+number)?|s\/n)\s*[:=]\s*([A-Za-z0-9_.\-]+)/i',
            'aravis'   => '/aravis(?:\s+library)?\s+version\s*[:=]?\s*([0-9][A-Za-z0-9_.\-]*)/i',
            // IP riconosciuto SOLO con contesto esplicito: senza, un firmware tipo
            // "1.101.0.0" verrebbe scambiato per IP (e' un quad-ottetto valido).
            'ip'       => '/(?:camera\s*ip|device\s*ip|cam\s*ip|gevdeviceaddress|gevcurrentip(?:configuration|address)?|host\s*address|ip\s*address|connecting\s*to)\b[^0-9]{0,30}?(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/i',
        );
        // Riga di log freeture: "YYYY-MM-DD HH:MM:SS [LEVEL] [thread] msg" o
        // "YYYY-MM-DD HH:MM:SS; LEVEL; msg".
        $tsPattern = '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';

        $maxBytes = 8 * 1024 * 1024; // se un log e' enorme, leggo solo gli ultimi 8 MB
        $files = glob($logsDir . "*.log");
        // Preferenze: ACQ_THREAD.log per primo, e' dove freeture logga le info camera.
        usort($files, function ($a, $b) {
            $aIsAcq = (stripos(basename($a), 'ACQ') !== false) ? 1 : 0;
            $bIsAcq = (stripos(basename($b), 'ACQ') !== false) ? 1 : 0;
            if ($aIsAcq !== $bIsAcq) return $bIsAcq - $aIsAcq;
            return strcmp($a, $b);
        });

        foreach ($files as $logFile) {
            $fh = @fopen($logFile, 'rb');
            if ($fh === false) continue;
            $size = filesize($logFile);
            if ($size > $maxBytes) {
                @fseek($fh, $size - $maxBytes);
                @fgets($fh); // scarta linea parziale
            }
            $lastTs = null;
            while (($line = fgets($fh)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line === '') continue;
                if (preg_match($tsPattern, $line, $tm)) {
                    $lastTs = $tm[1];
                }
                foreach ($patterns as $key => $regex) {
                    if (preg_match($regex, $line, $mm)) {
                        $val = trim($mm[1]);
                        if ($val === '') continue;
                        if ($key === 'ip') {
                            // Validazione stretta: deve essere un IP reale, no firmware-like.
                            if (filter_var($val, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) continue;
                            if ($val === '0.0.0.0' || strpos($val, '127.') === 0) continue;
                            // Salta righe firmware (anche se hanno il contesto "ip" che non
                            // dovrebbero, e' un cinturone di sicurezza in piu').
                            if (stripos($line, 'firmware') !== false) continue;
                        }
                        $out[$key] = $val;
                        if ($lastTs !== null) {
                            $out['lastSeenAt'] = $lastTs;
                        }
                    }
                }
            }
            fclose($fh);
            // Se ho riempito tutti i campi principali, posso fermarmi.
            if ($out['vendor'] && $out['model'] && $out['firmware'] && $out['serial']) {
                break;
            }
        }
        return $out;
    }

    public static function CanRunCalibration() {

        $can = self::CanCalibrate();

        return array(
            "res" => true,
            "data" => $can
        );
	}

    private static function ExecuteArvCommand($cmd = "") {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && $cmd != "")
        {
            $host = '-a '.$_POST['ip'];
        }
        

        $command = "arv-tool-0.8 $host $cmd";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
	}

    private static function GetBounds() {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && isset($_POST['camera']))
        {
            $host = $_POST['ip']." ".$_POST['camera'];
        }
        

        $command = "python3 /home/prisma/SETUP/bounds.py $host";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
	}

    public static function CanCalibrate()
    {
        if(file_exists("/var/www/html/calibration/calibrationstarted.txt")) return false;
        return true;
    }

    public static function Calibration() {

        if(!self::CanCalibrate())
        {
            return array(
                "res" => true,
                "data" => _("Una calibrazione e gia in corso")
            );
        }

        DockerApiLogic::sshContainerStop("freeture");
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";
        $step = $_POST['step'];

        $command_count = $_POST['maxGain'] - $_POST['minGain'];
        $cam = $_POST['camera'];

        $now = date("dmY", time());
        $exp = $_POST['exposure'];
        $cam = 1;

        $v1 = "-v /prismadata/freeture-conf/configuration.cfg:/usr/local/share/freeture/configuration.cfg";
        $v2 = "-v /prismadata/freeture-conf/calibration:/usr/local/share/freeture/calibration/";
        $image = "n3srl/freeture13";

        if(isset($_POST['image']))
        {
            $image = $_POST['image'];
        }

        $cmd_seq = array();
        
        for($gain = $_POST['minGain'];$gain <= $_POST['maxGain']; $gain += $step)
        {
            
            $params = "-m 4 -e $exp -g $gain --id $cam --fits --savepath /usr/local/share/freeture/calibration/";
            $name = "calibration-g".$gain."e".$exp."-".$now;
            $cmd = "docker run --rm --network host $v1 $v2 $image $params --filename  $name";

            $cmd_seq[] = $cmd;
        }

        
        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, "touch /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "chmod +x /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "touch /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                foreach($cmd_seq as $command)
                {
                    $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                    stream_set_blocking($stream, true);
                    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                    $cmd_out = stream_get_contents($stream_out);
                }

                // Now create zip
            }
        }

        

        unset($session);

        

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {

                $command = "docker exec prisma-orma chown -R 1000:www-data /usr/local/share/freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "docker exec prisma-orma chmod -R 770 /freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "cd /prismadata/freeture-conf/calibration/ && zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip .";
                //$command = "zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip /prismadata/freeture-conf/calibration/";
                $stream = ssh2_exec($session,"echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
                $command = "rm -rf /prismadata/freeture-conf/calibration";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "docker start freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm -rf /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "nohup /home/prisma/calibration.sh > out.txt";
                $stream = ssh2_exec($session, $command);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
            }
        }

        return array(
            "res" => true,
            "data" => _("Calibrazione iniziata, presto disponibile")
        );

	}

    public static function GetCameraCalibrations()
    {
        $path = _CALIBRATION_PATH_;
        $files = glob($path. "/*");

        $count = count($files);

        if($count == 0)
        {
            $data = _("<tr><td>Nessuna calibrazione recente</td></tr>");
        } else 
        {
            $data = "";
            foreach($files as $f)
            {
                
                $calibration = basename($f);

                if($calibration == "calibrationstarted.txt") continue;  

                $date = explode("-", $calibration)[2];
                $date = substr($date, 0, -4);
                $day = substr($date, 0, 2);
                $month = substr($date, 2, 2);
                $year = substr($date, 4, 4);
                $date = $day . "/" . $month . "/" . $year;  

                $data .= "<tr><td>$calibration</td><td>$date</td><td><a style = 'font-size:22px;color:black; text-decoration:none' href = '/calibration/$calibration'><i class='fa fa-download'></i></a></td><td><span name = '$calibration' class = 'calibration_delete'><i style = 'cursor:pointer;font-size:22px' class='fa fa-trash'></i></span></td></tr>";
                
            }
        }
        

        return array(
            "res" => true,
            "data" => $data
        );
    }

    public static function DeleteCalibration($request)
    {

        $calibration = $request->query->get('calibration');

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";

        $command = "rm /prismadata/orma-src/calibration/$calibration";

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
    }

}

