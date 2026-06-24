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

        $result['verdict'] = self::computeNetDiagVerdict($result);
        return array("res" => true, "data" => $result);
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
            );
        }
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

        return $r;
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

