<?php

/**
 * Client SNMP v2c minimale pure-PHP. Implementa GetRequest (0xA0) e
 * GetNextRequest (0xA1) sufficienti per il nostro uso (snmpget + walk).
 * Non richiede l'estensione php-snmp ne' binari snmpget/snmpwalk: tutto via
 * stream_socket_client UDP + encoding/decoding ASN.1 BER manuale.
 *
 * Limiti:
 *  - solo SNMPv2c (no v1 obsoleto, no v3 con auth/priv)
 *  - solo lettura (GET, GETNEXT). No SET, no traps, no bulk.
 *  - decoding semplificato: int / octet-string / oid / null / ipaddress /
 *    counter32 / gauge32 / timeticks / counter64.
 *
 * @author: N3 S.r.l.
 */
class SnmpClientLogic
{
    /**
     * Esegue una singola GetRequest. Ritorna il valore decodificato (string),
     * o null in caso di errore / timeout / noSuchObject.
     */
    public static function get($host, $community, $oid, $timeoutSec = 3, $port = 161)
    {
        $resp = self::sendPdu($host, $port, $community, 0xA0, $oid, $timeoutSec);
        if ($resp === null || empty($resp['varbinds'])) return null;
        $vb = $resp['varbinds'][0];
        // Se OID restituito = "noSuchObject"/"noSuchInstance" -> null
        if ($vb['type'] === 0x80 || $vb['type'] === 0x81 || $vb['type'] === 0x82) return null;
        return $vb['value'];
    }

    /**
     * Walk via GetNextRequest in catena. Ritorna un array map: suffix-oid => value,
     * dove suffix-oid e' la parte di OID dopo $rootOid (escluso il '.' iniziale).
     */
    public static function walk($host, $community, $rootOid, $timeoutSec = 5, $maxIter = 4096, $port = 161)
    {
        $result = array();
        $current = $rootOid;
        $rootPrefix = '.' . trim($rootOid, '.') . '.';
        for ($i = 0; $i < $maxIter; $i++) {
            $resp = self::sendPdu($host, $port, $community, 0xA1, $current, $timeoutSec);
            if ($resp === null || empty($resp['varbinds'])) break;
            $vb = $resp['varbinds'][0];
            // end-of-mib o noSuchObject -> fine walk
            if ($vb['type'] === 0x80 || $vb['type'] === 0x81 || $vb['type'] === 0x82) break;
            $oid = '.' . trim($vb['oid'], '.');
            // Se l'OID risposto non sta piu' sotto il root, abbiamo finito
            if (strpos($oid . '.', $rootPrefix) !== 0) break;
            $suffix = substr($oid, strlen($rootPrefix));
            $result[$suffix] = $vb['value'];
            $current = $vb['oid'];
            // Protezione anti loop
            if ($current === $resp['varbinds'][0]['oid'] && $i > 0 && isset($prevOid) && $prevOid === $current) break;
            $prevOid = $current;
        }
        return $result;
    }

    //----------------------------------------------------------------------
    // Internals: PDU send/recv via UDP socket
    //----------------------------------------------------------------------

    private static function sendPdu($host, $port, $community, $pduType, $oid, $timeoutSec)
    {
        $reqId = mt_rand(1, 0x7FFFFFFF);
        $varbind = self::seq(self::encOid($oid) . self::encNull());
        $varbinds = self::seq($varbind);
        $pduBody = self::encInt($reqId) . self::encInt(0) . self::encInt(0) . $varbinds;
        $pdu = self::encTLV($pduType, $pduBody);
        $msg = self::seq(self::encInt(1) /* v2c */ . self::encStr($community) . $pdu);

        $sock = @stream_socket_client("udp://$host:$port", $errno, $errstr, $timeoutSec);
        if (!$sock) {
            error_log("[SnmpClient] socket create fail for $host:$port: $errstr");
            return null;
        }
        stream_set_timeout($sock, (int) $timeoutSec, (int) (($timeoutSec - floor($timeoutSec)) * 1e6));
        @fwrite($sock, $msg);
        $resp = @fread($sock, 65535);
        $meta = stream_get_meta_data($sock);
        @fclose($sock);
        if (!empty($meta['timed_out'])) {
            return null;
        }
        if ($resp === '' || $resp === false) {
            return null;
        }
        return self::parseMessage($resp);
    }

    //----------------------------------------------------------------------
    // ASN.1 BER encoding
    //----------------------------------------------------------------------

    private static function encLen($len)
    {
        if ($len < 128) return chr($len);
        $hex = dechex($len);
        if (strlen($hex) % 2) $hex = '0' . $hex;
        $b = pack('H*', $hex);
        return chr(0x80 | strlen($b)) . $b;
    }

    private static function encTLV($tag, $body)
    {
        return chr($tag) . self::encLen(strlen($body)) . $body;
    }

    private static function seq($body)        { return self::encTLV(0x30, $body); }
    private static function encStr($s)        { return self::encTLV(0x04, $s); }
    private static function encNull()         { return "\x05\x00"; }

    private static function encInt($n)
    {
        // Codifica big-endian two's complement, dimensione minima
        if ($n === 0) return self::encTLV(0x02, "\x00");
        $negative = $n < 0;
        $val = $negative ? ~(-$n - 1) : $n;
        $bytes = '';
        do {
            $bytes = chr($val & 0xFF) . $bytes;
            $val = $val >> 8;
        } while ($val > 0);
        // Aggiungi 0x00 davanti se il bit alto del primo byte e' 1 (per non confondere col segno)
        if (!$negative && (ord($bytes[0]) & 0x80)) {
            $bytes = "\x00" . $bytes;
        }
        return self::encTLV(0x02, $bytes);
    }

    private static function encOid($oidStr)
    {
        $parts = explode('.', trim($oidStr, '.'));
        $n = count($parts);
        if ($n < 2) return self::encTLV(0x06, '');
        $first = ((int) $parts[0]) * 40 + (int) $parts[1];
        $bytes = self::encOidSubid($first);
        for ($i = 2; $i < $n; $i++) {
            $bytes .= self::encOidSubid((int) $parts[$i]);
        }
        return self::encTLV(0x06, $bytes);
    }

    private static function encOidSubid($v)
    {
        if ($v < 0x80) return chr($v);
        $stack = array($v & 0x7F);
        $v >>= 7;
        while ($v > 0) {
            $stack[] = ($v & 0x7F) | 0x80;
            $v >>= 7;
        }
        return implode('', array_map('chr', array_reverse($stack)));
    }

    //----------------------------------------------------------------------
    // ASN.1 BER decoding
    //----------------------------------------------------------------------

    /**
     * Parsa il messaggio SNMP completo. Ritorna:
     *   array('community' => str, 'reqId' => int, 'errorStatus' => int,
     *         'errorIndex' => int, 'varbinds' => [ ['oid'=>'.1.3...', 'type'=>int, 'value'=>...], ... ])
     */
    private static function parseMessage($bin)
    {
        $pos = 0;
        $seq = self::parseTLV($bin, $pos);
        if (!$seq || $seq['tag'] !== 0x30) return null;
        $body = $seq['value'];
        $p2 = 0;

        $version = self::parseTLV($body, $p2);
        $community = self::parseTLV($body, $p2);
        $pdu = self::parseTLV($body, $p2);
        if (!$pdu) return null;

        $p3 = 0;
        $reqId      = self::parseTLV($pdu['value'], $p3);
        $errorStat  = self::parseTLV($pdu['value'], $p3);
        $errorIdx   = self::parseTLV($pdu['value'], $p3);
        $vbList     = self::parseTLV($pdu['value'], $p3); // SEQUENCE of varbinds
        if (!$vbList) return null;

        $varbinds = array();
        $pv = 0;
        while ($pv < strlen($vbList['value'])) {
            $vb = self::parseTLV($vbList['value'], $pv);
            if (!$vb || $vb['tag'] !== 0x30) break;
            $pi = 0;
            $oidTlv = self::parseTLV($vb['value'], $pi);
            $valTlv = self::parseTLV($vb['value'], $pi);
            if (!$oidTlv || !$valTlv) continue;
            $varbinds[] = array(
                'oid'   => self::decodeOid($oidTlv['value']),
                'type'  => $valTlv['tag'],
                'value' => self::decodeValue($valTlv['tag'], $valTlv['value']),
            );
        }

        return array(
            'community'   => $community ? $community['value'] : null,
            'reqId'       => $reqId ? self::decodeInt($reqId['value']) : null,
            'errorStatus' => $errorStat ? self::decodeInt($errorStat['value']) : 0,
            'errorIndex'  => $errorIdx ? self::decodeInt($errorIdx['value']) : 0,
            'varbinds'    => $varbinds,
        );
    }

    private static function parseTLV($bin, &$pos)
    {
        if ($pos >= strlen($bin)) return null;
        $tag = ord($bin[$pos++]);
        if ($pos >= strlen($bin)) return null;
        $b = ord($bin[$pos++]);
        if ($b < 128) {
            $len = $b;
        } else {
            $n = $b & 0x7F;
            if ($n === 0 || $n > 4) return null; // indefinite/large non gestita
            $len = 0;
            for ($i = 0; $i < $n; $i++) {
                if ($pos >= strlen($bin)) return null;
                $len = ($len << 8) | ord($bin[$pos++]);
            }
        }
        $val = substr($bin, $pos, $len);
        $pos += $len;
        return array('tag' => $tag, 'value' => $val);
    }

    private static function decodeInt($bin)
    {
        $len = strlen($bin);
        if ($len === 0) return 0;
        $n = ord($bin[0]);
        $negative = ($n & 0x80) !== 0;
        for ($i = 1; $i < $len; $i++) {
            $n = ($n << 8) | ord($bin[$i]);
        }
        if ($negative) {
            $n -= (1 << (8 * $len));
        }
        return $n;
    }

    private static function decodeOid($bin)
    {
        $len = strlen($bin);
        if ($len === 0) return '.0.0';
        $first = ord($bin[0]);
        $parts = array(intdiv($first, 40), $first % 40);
        $val = 0;
        for ($i = 1; $i < $len; $i++) {
            $b = ord($bin[$i]);
            $val = ($val << 7) | ($b & 0x7F);
            if (($b & 0x80) === 0) {
                $parts[] = $val;
                $val = 0;
            }
        }
        return '.' . implode('.', $parts);
    }

    private static function decodeValue($tag, $bin)
    {
        switch ($tag) {
            case 0x02: // INTEGER
            case 0x41: // Counter32
            case 0x42: // Gauge32
            case 0x43: // TimeTicks
            case 0x46: // Counter64 (semplificato a int; in PHP int 64bit ok)
                return self::decodeInt($bin);
            case 0x04: // OCTET STRING
                return $bin;
            case 0x05: // NULL
                return null;
            case 0x06: // OBJECT IDENTIFIER
                return self::decodeOid($bin);
            case 0x40: // IpAddress (4 bytes)
                if (strlen($bin) === 4) {
                    return ord($bin[0]) . '.' . ord($bin[1]) . '.' . ord($bin[2]) . '.' . ord($bin[3]);
                }
                return bin2hex($bin);
            case 0x80: // noSuchObject
            case 0x81: // noSuchInstance
            case 0x82: // endOfMibView
                return null;
            default:
                return bin2hex($bin);
        }
    }
}
