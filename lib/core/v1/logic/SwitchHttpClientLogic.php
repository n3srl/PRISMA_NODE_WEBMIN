<?php

/**
 * Client HTTP minimale per lo switch D-Link DGS-1210 (firmware 6.30.x). Serve
 * a triggerare il Cable Diagnostic (TDR) e scaricarne il risultato JSON
 * direttamente dalla web GUI, perche' su questo firmware il TDR non e' esposto
 * via SNMP.
 *
 * Flusso login:
 *   1) GET  /Encrypt.js           -> estrae chiave RSA pubblica (var EN_DATA = 'base64')
 *   2) POST /homepage.htm         -> body: pelican_ecryp + pinkpanther_ecryp
 *                                    (user="admin" + password RSA-cifrati con PKCS1)
 *   3) la response contiene un Gambit (hex >=32 chars) usato come token di sessione
 *
 * Endpoint Cable Diag:
 *   GET /iss/specific/CableDiag_Ajax_Data.js
 *     ?Gambit=<token>&dumy=<ms-timestamp>&FromPort=N&ToPort=N&Speed_status=<bits>
 *
 *   Speed_status e' la concatenazione di Port_Setting[i][1] per ogni porta,
 *   letta da /iss/specific/PortSetting.js. Senza questa stringa il TDR risponde
 *   con la pagina di "Login timeout".
 *
 * @author: N3 S.r.l.
 */
class SwitchHttpClientLogic
{
    // Cache per request: il login dello switch dura ~30s, ma una pagina del webmin
    // ne richiede al massimo qualche chiamata consecutiva quindi la teniamo statica
    // e basta. Una nuova request HTTP del browser rifa' tutto da capo (PHP-FPM/Apache
    // ricarica la classe).
    private static $gambit = null;
    private static $speedStatus = null;

    public static function isConfigured()
    {
        return defined('_SWITCH_IP_') && _SWITCH_IP_ !== ''
            && defined('_SWITCH_HTTP_PASSWORD_') && _SWITCH_HTTP_PASSWORD_ !== '';
    }

    /**
     * Esegue login allo switch. Ritorna il Gambit (hex) oppure null in errore.
     * In caso di fallimento error_log() segnala lo step preciso per debug
     * (allow_url_fopen, RSA, regex Gambit, ecc.).
     */
    public static function login()
    {
        if (self::$gambit !== null) return self::$gambit;
        if (!self::isConfigured()) {
            error_log("[SwitchHttpClient] login: switch non configurato (_SWITCH_IP_ o _SWITCH_HTTP_PASSWORD_ mancanti)");
            return null;
        }

        $host = _SWITCH_IP_;

        // 1) Estrazione chiave RSA pubblica
        $encJs = self::httpGet("http://$host/Encrypt.js");
        if (!is_string($encJs) || $encJs === '') {
            error_log("[SwitchHttpClient] login: GET Encrypt.js fallita (host $host irraggiungibile o cURL non disponibile)");
            return null;
        }
        if (!preg_match("/var EN_DATA\\s*=\\s*'([^']+)'/", $encJs, $m)) {
            error_log("[SwitchHttpClient] login: Encrypt.js ricevuto ma EN_DATA non estraibile (" . strlen($encJs) . " bytes)");
            return null;
        }
        $pubKeyPem = "-----BEGIN PUBLIC KEY-----\n"
                   . chunk_split($m[1], 64, "\n")
                   . "-----END PUBLIC KEY-----\n";

        // 2) RSA-cifra credenziali. Il DGS-1210 ignora di fatto lo username
        //    (login solo password) ma il form vuole entrambi i campi.
        $user = (defined('_SWITCH_HTTP_USER_') && _SWITCH_HTTP_USER_ !== '')
            ? _SWITCH_HTTP_USER_ : 'admin';
        $encUser = self::rsaEnc($user, $pubKeyPem);
        $encPwd  = self::rsaEnc(_SWITCH_HTTP_PASSWORD_, $pubKeyPem);
        if ($encUser === null || $encPwd === null) {
            error_log("[SwitchHttpClient] login: openssl_public_encrypt fallita (ext-openssl assente o chiave non valida)");
            return null;
        }

        // 3) POST login form
        $body = self::httpPost("http://$host/homepage.htm", array(
            'pelican_ecryp'     => $encUser,
            'pinkpanther_ecryp' => $encPwd,
            'BrowsingPage'      => 'index_redirect.htm',
            'currlang'          => '0',
            'changlang'         => '0',
        ));
        if (!is_string($body) || $body === '') {
            error_log("[SwitchHttpClient] login: POST /homepage.htm vuoto/false");
            return null;
        }
        if (!preg_match('/Gambit[^A-Za-z0-9]+([0-9A-Fa-f]{32,})/', $body, $mm)) {
            error_log("[SwitchHttpClient] login: Gambit non trovato nella response (body " . strlen($body) . " bytes, head=" . substr($body, 0, 200) . ")");
            return null;
        }

        self::$gambit = $mm[1];
        return self::$gambit;
    }

    /**
     * Lancia il Cable Diagnostic sulla porta indicata e parse il JSON di risposta.
     * Ritorna array strutturato:
     *   array(
     *     'ok'            => true,
     *     'port'          => 2,
     *     'pairs'         => array(
     *         array('index'=>1, 'state'=>'OK', 'length'=>'N/A', 'lengthRaw'=>'N/A'),
     *         ...x4
     *     ),
     *     'averageLength' => '< 50',
     *     'allOk'         => true
     *   )
     * In caso d'errore:
     *   array('ok' => false, 'error' => 'msg', 'raw' => '...')
     */
    public static function cableDiag($port)
    {
        $port = (int) $port;
        if ($port <= 0) {
            return array('ok' => false, 'error' => "Porta non valida ($port).");
        }
        if (!self::isConfigured()) {
            return array('ok' => false, 'error' => 'Switch HTTP non configurato in config.php.');
        }

        $g = self::login();
        if (!$g) {
            return array('ok' => false, 'error' => 'Login allo switch fallito (verifica _SWITCH_HTTP_PASSWORD_).');
        }

        $speedStatus = self::getSpeedStatus();
        if ($speedStatus === null || $speedStatus === '') {
            return array('ok' => false, 'error' => 'Impossibile leggere Port Setting dello switch.');
        }

        $host    = _SWITCH_IP_;
        $ts      = (int) round(microtime(true) * 1000);
        $url     = "http://$host/iss/specific/CableDiag_Ajax_Data.js"
                 . "?Gambit=$g&dumy=$ts&FromPort=$port&ToPort=$port&Speed_status=$speedStatus";
        $referer = "http://$host/iss/Cable_Diagnostics_ajax.htm?Gambit=$g";

        // Il TDR sulla DGS-1210 impiega ~3 secondi a porta (puo' arrivare a 5
        // se il link e' DOWN o c'e' una coppia rotta).
        $resp = self::httpGet($url, $referer, 15);
        if (!is_string($resp) || $resp === '') {
            return array('ok' => false, 'error' => 'Nessuna risposta dallo switch.');
        }

        $json = json_decode($resp, true);
        if (!is_array($json) || empty($json['Content'][0])) {
            // Se la response e' HTML significa che la sessione e' scaduta o il
            // login e' fallito: invalido il Gambit cache per evitare di riusarlo.
            if (stripos($resp, '<html') !== false) {
                self::$gambit = null;
                self::$speedStatus = null;
                return array(
                    'ok'    => false,
                    'error' => 'Sessione switch scaduta o login rifiutato (ricevuto HTML).',
                );
            }
            return array(
                'ok'    => false,
                'error' => 'Risposta JSON non valida.',
                'raw'   => substr($resp, 0, 500),
            );
        }

        $c = $json['Content'][0];
        $pairs = array();
        $allOk = true;
        for ($i = 1; $i <= 4; $i++) {
            $state = isset($c["Pair_State_$i"])  ? trim($c["Pair_State_$i"])  : '';
            $raw   = isset($c["Pair_length_$i"]) ? trim($c["Pair_length_$i"]) : '';

            // Regole di display ricavate dal checkifNoCable() dello switch JS:
            //   state == "OK"                                  -> length = "N/A"
            //   length == 0 e state contiene "open"            -> length = "No Cable"
            //   altrimenti                                     -> length grezza
            $length = $raw;
            if ($state === 'OK') {
                $length = 'N/A';
            } elseif ((int) $raw === 0 && stripos($state, 'open') !== false) {
                $length = 'No Cable';
            }
            if ($state !== 'OK') {
                $allOk = false;
            }
            $pairs[] = array(
                'index'     => $i,
                'state'     => $state,
                'length'    => $length,
                'lengthRaw' => $raw,
            );
        }

        return array(
            'ok'            => true,
            'port'          => isset($c['Port']) ? (int) $c['Port'] : $port,
            'pairs'         => $pairs,
            'averageLength' => isset($c['Averge_length']) ? $c['Averge_length'] : '',
            'allOk'         => $allOk,
        );
    }

    /**
     * Legge lo status Jumbo Frame dalla web GUI dello switch. Sul DGS-1210 e'
     * un singolo flag switch-wide. Best-effort: prova diversi endpoint candidati
     * perche' l'URL varia con la versione di firmware. Ritorna:
     *   array('enabled' => true|false, 'maxFrameSize' => 1536|10000|...)
     * oppure null se non riesce a determinarlo (l'utente vedra' "sconosciuto"
     * lato UI e potra' verificare a mano).
     */
    public static function getJumboFrameStatus()
    {
        if (!self::isConfigured()) return null;
        $g = self::login();
        if (!$g) return null;

        $host = _SWITCH_IP_;
        // Endpoint candidati sul firmware 6.30.x. Nessuna garanzia: se non
        // matcha nulla ritorniamo null e la UI mostra "stato sconosciuto".
        $candidates = array(
            "http://$host/iss/specific/Jumbo_Frame.js?Gambit=$g",
            "http://$host/iss/specific/JumboFrame.js?Gambit=$g",
            "http://$host/iss/specific/Jumbo_Frame.htm?Gambit=$g",
            "http://$host/iss/Jumbo_Frame.htm?Gambit=$g",
        );
        foreach ($candidates as $url) {
            $body = self::httpGet($url, "http://$host/");
            if (!is_string($body) || $body === '') continue;
            if (stripos($body, 'Login') !== false && stripos($body, 'timeout') !== false) {
                // Sessione scaduta -> reset e abbandono
                self::$gambit = null;
                return null;
            }
            // Pattern 1: JS con variabili (firmware nuovo)
            //   var jumbo_state = '1';   oppure  var Jumbo_Enable = '1';
            //   var jumbo_size = '10000';
            $enabled = null; $size = null;
            if (preg_match('/(?:jumbo[_\s]*(?:state|enable|status))[^=]*=\s*[\'"]?(\d+)/i', $body, $m)) {
                $enabled = ((int) $m[1] === 1);
            }
            if (preg_match('/(?:jumbo[_\s]*(?:size|len|frame[_\s]*size|max[_\s]*size))[^=]*=\s*[\'"]?(\d+)/i', $body, $m)) {
                $size = (int) $m[1];
            }
            // Pattern 2: HTML form con radio (firmware vecchio)
            //   <input type=radio name=jumbo_state value=1 checked>Enable
            if ($enabled === null
                && preg_match('/name\s*=\s*[\'"]?jumbo[_\s]*\w*[\'"]?[^>]*value\s*=\s*[\'"]?(\d+)[\'"]?[^>]*checked/i', $body, $m)) {
                $enabled = ((int) $m[1] === 1);
            }
            if ($enabled !== null || $size !== null) {
                return array(
                    'enabled'      => $enabled,
                    'maxFrameSize' => $size,
                    'source'       => 'http-scrape',
                );
            }
        }
        return null;
    }

    /**
     * Recupera Port_Setting.js e ricostruisce la stringa Speed_status che il TDR
     * pretende come parametro: e' la concatenazione di Port_Setting[i][1] per
     * ogni porta dello switch.
     */
    private static function getSpeedStatus()
    {
        if (self::$speedStatus !== null) return self::$speedStatus;

        $g = self::login();
        if (!$g) return null;

        $host    = _SWITCH_IP_;
        $referer = "http://$host/iss/Cable_Diagnostics_ajax.htm?Gambit=$g";
        $ps      = self::httpGet("http://$host/iss/specific/PortSetting.js?Gambit=$g&findPort=0", $referer);
        if (!is_string($ps)) return null;

        if (!preg_match('/Port_Setting\\s*=\\s*(\\[[^;]+\\])\\s*;/s', $ps, $m)) {
            return null;
        }
        $bits = array();
        if (preg_match_all('/\\[\\s*\\d+\\s*,\\s*(\\d+)/', $m[1], $bm)) {
            foreach ($bm[1] as $b) $bits[] = $b;
        }
        if (empty($bits)) return null;

        self::$speedStatus = implode('', $bits);
        return self::$speedStatus;
    }

    private static function rsaEnc($plain, $pubKeyPem)
    {
        $out = '';
        if (!openssl_public_encrypt($plain, $out, $pubKeyPem, OPENSSL_PKCS1_PADDING)) {
            return null;
        }
        return base64_encode($out);
    }

    // Usiamo cURL (sempre disponibile in PHP Apache) invece di file_get_contents
    // su URL HTTP: in molti container Apache "allow_url_fopen" e' disabilitato
    // per sicurezza, e file_get_contents() ritorna silenziosamente false.
    // cURL non dipende da quella ini e da' anche errori espliciti se qualcosa
    // va storto.

    private static function httpGet($url, $referer = null, $timeoutSec = 8)
    {
        $headers = array('User-Agent: Mozilla/5.0');
        if ($referer) $headers[] = "Referer: $referer";
        return self::curlExec($url, false, null, $headers, $timeoutSec);
    }

    private static function httpPost($url, $fields, $timeoutSec = 10)
    {
        // Il DGS-1210 e' suscettibile alla presenza di un Referer "buono":
        // mettiamo la root dell'host come referer.
        $rootRef = preg_replace('#^(https?://[^/]+).*$#', '$1/', $url);
        $headers = array(
            'User-Agent: Mozilla/5.0',
            "Referer: $rootRef",
            'Content-Type: application/x-www-form-urlencoded',
        );
        return self::curlExec($url, true, http_build_query($fields), $headers, $timeoutSec);
    }

    private static function curlExec($url, $post, $postFields, $headers, $timeoutSec)
    {
        if (!function_exists('curl_init')) {
            error_log("[SwitchHttpClient] estensione ext-curl mancante: impossibile contattare lo switch.");
            return false;
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT,        (int) $timeoutSec);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_FAILONERROR,    false);
        // L'http server del DGS-1210 manda risposte con Connection: close;
        // disattiviamo il reuse per evitare race condition sul socket.
        curl_setopt($ch, CURLOPT_FORBID_REUSE,   true);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST,       true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }
        $body = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false) {
            error_log("[SwitchHttpClient] cURL " . ($post ? 'POST' : 'GET') . " $url fallita: $err");
            return false;
        }
        // 401/403 dello switch e' comunque utile da loggare ma non lo trattiamo
        // come errore: il parser piu' a monte decide.
        if ($code >= 400) {
            error_log("[SwitchHttpClient] cURL " . ($post ? 'POST' : 'GET') . " $url HTTP $code");
        }
        return $body;
    }
}
