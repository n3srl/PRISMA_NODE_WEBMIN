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
    // Cache per request (static): vive solo dentro una singola request PHP.
    private static $gambit = null;
    private static $speedStatus = null;

    // Cache persistente cross-request: il DGS-1210 limita a 4 sessioni web
    // concorrenti (firmware 6.30). Se ogni click "Test cavo" facesse un nuovo
    // login, in 4 click ravvicinati otteniamo "Maximum number of sessions
    // reached". Memorizziamo il Gambit su file con TTL piu' basso del timeout
    // dello switch (default switch=180s -> noi cachiamo 120s).
    const GAMBIT_CACHE_TTL = 120;

    public static function isConfigured()
    {
        return defined('_SWITCH_IP_') && _SWITCH_IP_ !== ''
            && defined('_SWITCH_HTTP_PASSWORD_') && _SWITCH_HTTP_PASSWORD_ !== '';
    }

    /**
     * Esegue login allo switch. Ritorna il Gambit (hex) oppure null in errore.
     * Prima cerca un Gambit cached su file (TTL 120s, sotto il timeout di
     * sessione dello switch) per non saturare il pool delle 4 sessioni
     * concorrenti permesse dal firmware DGS-1210 6.30.
     * In caso di fallimento error_log() segnala lo step preciso per debug
     * (allow_url_fopen, RSA, regex Gambit, ecc.).
     */
    public static function login()
    {
        if (self::$gambit !== null) return self::$gambit;
        if (!self::isConfigured()) {
            self::trace("login: switch non configurato (_SWITCH_IP_ o _SWITCH_HTTP_PASSWORD_ mancanti)");
            return null;
        }

        // Tentiamo prima di riusare un Gambit cached da una request precedente
        // (entro GAMBIT_CACHE_TTL secondi).
        $cached = self::readGambitCache();
        if ($cached !== null) {
            self::trace("login: riuso Gambit cached (eta " . (time() - self::readGambitCacheTs()) . "s) = " . substr($cached, 0, 16) . "...");
            self::$gambit = $cached;
            return $cached;
        }

        $host = _SWITCH_IP_;

        // 1) Estrazione chiave RSA pubblica
        $encJs = self::httpGet("http://$host/Encrypt.js");
        if (!is_string($encJs) || $encJs === '') {
            self::trace("login step 1/3: GET Encrypt.js fallita (host $host irraggiungibile o cURL non disponibile)");
            return null;
        }
        if (!preg_match("/var EN_DATA\\s*=\\s*'([^']+)'/", $encJs, $m)) {
            self::trace("login step 1/3: Encrypt.js ricevuto (" . strlen($encJs) . " bytes) ma EN_DATA non estraibile, head=" . substr($encJs, 0, 200));
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
            self::trace("login step 2/3: openssl_public_encrypt fallita (ext-openssl assente o chiave non valida)");
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
            self::trace("login step 3/3: POST /homepage.htm vuoto/false");
            return null;
        }
        if (!preg_match('/Gambit[^A-Za-z0-9]+([0-9A-Fa-f]{32,})/', $body, $mm)) {
            $head = preg_replace('/\\s+/', ' ', substr($body, 0, 300));
            // Detection specifico per "Maximum number of sessions reached":
            if (stripos($body, 'Maximum number of sessions') !== false) {
                self::trace("login step 3/3: switch HA SATURATO le sessioni concorrenti (max 4 sul DGS-1210). Aspetta ~3 min o riduci 'Web Session Timeout'.");
            } else {
                self::trace("login step 3/3: Gambit non trovato nella response (" . strlen($body) . " bytes), head=$head");
            }
            return null;
        }

        self::$gambit = $mm[1];
        self::writeGambitCache($mm[1]);
        self::trace("login OK: nuovo Gambit=" . substr($mm[1], 0, 16) . "...");
        return self::$gambit;
    }

    private static function readGambitCacheTs() {
        $f = self::gambitCachePath();
        if (!file_exists($f)) return 0;
        $data = @json_decode(@file_get_contents($f), true);
        return is_array($data) && isset($data['ts']) ? (int) $data['ts'] : 0;
    }

    /**
     * Logout esplicito dello switch (libera la slot di sessione).
     * Da chiamare quando il client decide di non riusare piu' il Gambit
     * cached (es. cache scaduta). Best-effort: ignora errori HTTP.
     */
    public static function logout($gambit = null)
    {
        if (!self::isConfigured()) return;
        $g = $gambit !== null ? $gambit : self::$gambit;
        if (!$g) return;
        $host = _SWITCH_IP_;
        // Sui DGS-1210 firmware 6.30 il logout della GUI accede tipicamente a
        // /iss/logoff.htm o /iss/logout.htm. Provo entrambi best-effort.
        foreach (array("http://$host/iss/logoff.htm?Gambit=$g", "http://$host/iss/logout.htm?Gambit=$g", "http://$host/logout.cgi?Gambit=$g") as $u) {
            self::httpGet($u, "http://$host/");
        }
        error_log("[SwitchHttpClient] logout: chiamato logout per Gambit=" . substr($g, 0, 16) . "...");
    }

    //--------------------------------------------------------------------
    // Cache Gambit persistente su filesystem
    //--------------------------------------------------------------------

    private static function gambitCachePath()
    {
        return rtrim(sys_get_temp_dir(), '/\\') . '/dlink_switch_gambit.json';
    }

    private static function writeGambitCache($gambit)
    {
        @file_put_contents(self::gambitCachePath(), json_encode(array(
            'ts'     => time(),
            'gambit' => $gambit,
            'host'   => _SWITCH_IP_,
        )), LOCK_EX);
    }

    private static function readGambitCache()
    {
        $f = self::gambitCachePath();
        if (!file_exists($f)) return null;
        $data = @json_decode(@file_get_contents($f), true);
        if (!is_array($data) || empty($data['gambit'])) return null;
        // Invalida se il switch e' cambiato (utente ha aggiornato _SWITCH_IP_)
        if (!empty($data['host']) && $data['host'] !== _SWITCH_IP_) return null;
        $age = time() - (int) (isset($data['ts']) ? $data['ts'] : 0);
        if ($age > self::GAMBIT_CACHE_TTL) {
            // Cache scaduto: logout best-effort per liberare la slot prima di
            // farne una nuova (evita "Maximum sessions reached" su click rapidi).
            self::logout($data['gambit']);
            return null;
        }
        return $data['gambit'];
    }

    /**
     * Invalida il cache (in-memory + file) e tenta logout dello switch.
     * Da chiamare quando una richiesta riceve HTML di "Login timeout":
     * il Gambit cached non e' piu' valido.
     */
    private static function invalidateGambit()
    {
        $g = self::$gambit;
        self::$gambit = null;
        self::$speedStatus = null;
        @unlink(self::gambitCachePath());
        if ($g) self::logout($g);
    }

    // Buffer di debug compilato durante una singola chiamata cableDiag(): tutti
    // gli step (login, pre-touch, PortSetting, TDR) accodano qui un breve summary
    // (status code, prime righe della response) cosi' in caso di errore possiamo
    // ritornarlo nel response API per debug senza dipendere dai log file (in
    // container con log_errors=Off i error_log() si perdono in stderr).
    private static $debugTrace = array();

    private static function trace($msg) {
        self::$debugTrace[] = '[' . date('H:i:s') . '] ' . $msg;
        error_log('[SwitchHttpClient] ' . $msg);
    }

    public static function flushTrace() {
        $t = self::$debugTrace;
        self::$debugTrace = array();
        return $t;
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
        // Reset del trace per questa chiamata: il flush lo fa il caller leggendo
        // self::flushTrace() dopo il ritorno (sia in caso success che error).
        self::$debugTrace = array();

        $port = (int) $port;
        if ($port <= 0) {
            return array('ok' => false, 'error' => "Porta non valida ($port).", 'trace' => self::flushTrace());
        }
        if (!self::isConfigured()) {
            return array('ok' => false, 'error' => 'Switch HTTP non configurato in config.php.', 'trace' => self::flushTrace());
        }

        self::trace("cableDiag start port=$port");

        $g = self::login();
        if (!$g) {
            return array('ok' => false, 'error' => 'Login allo switch fallito.', 'trace' => self::flushTrace());
        }

        $speedStatus = self::getSpeedStatus();
        if ($speedStatus === null || $speedStatus === '') {
            return array('ok' => false, 'error' => 'Impossibile leggere Port Setting dello switch.', 'trace' => self::flushTrace());
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
            self::trace("cableDiag: response vuota/false dallo switch");
            return array('ok' => false, 'error' => 'Nessuna risposta dallo switch.', 'trace' => self::flushTrace());
        }
        self::trace("cableDiag TDR response: " . strlen($resp) . " bytes");

        $json = json_decode($resp, true);
        if (!is_array($json) || empty($json['Content'][0])) {
            if (stripos($resp, '<html') !== false) {
                $errMsg = 'Sessione switch scaduta o login rifiutato (ricevuto HTML).';
                if (stripos($resp, 'Maximum number of sessions') !== false) {
                    $errMsg = 'Switch ha esaurito le sessioni concorrenti (max 4 su DGS-1210). Aspetta 1-3 minuti che le sessioni stale scadano.';
                }
                self::trace("cableDiag: ricevuto HTML, head=" . preg_replace('/\\s+/', ' ', substr($resp, 0, 200)));
                self::invalidateGambit();
                return array('ok' => false, 'error' => $errMsg, 'trace' => self::flushTrace());
            }
            return array(
                'ok'    => false,
                'error' => 'Risposta JSON non valida.',
                'raw'   => substr($resp, 0, 500),
                'trace' => self::flushTrace(),
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

        self::trace("cableDiag OK: " . count($pairs) . " pairs, allOk=" . ($allOk ? 'true' : 'false'));
        return array(
            'ok'            => true,
            'port'          => isset($c['Port']) ? (int) $c['Port'] : $port,
            'pairs'         => $pairs,
            'averageLength' => isset($c['Averge_length']) ? $c['Averge_length'] : '',
            'allOk'         => $allOk,
            'trace'         => self::flushTrace(),
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
                // Sessione scaduta -> invalida (con logout) e abbandono
                self::invalidateGambit();
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
        // Prima di chiedere PortSetting.js, "tocchiamo" la pagina del Cable
        // Diagnostics: alcuni firmware DGS-1210 invalidano il Gambit se l'utente
        // salta direttamente alle .js senza aver caricato la pagina HTML che
        // dovrebbe ospitarle. Best-effort: ignoriamo errori.
        $preTouch = self::httpGet("http://$host/iss/Cable_Diagnostics_ajax.htm?Gambit=$g", "http://$host/");
        self::trace("getSpeedStatus pre-touch Cable_Diagnostics_ajax.htm: " . (is_string($preTouch) ? strlen($preTouch) . " bytes" : "false"));

        $referer = "http://$host/iss/Cable_Diagnostics_ajax.htm?Gambit=$g";
        $url     = "http://$host/iss/specific/PortSetting.js?Gambit=$g&findPort=0";
        $ps      = self::httpGet($url, $referer);
        if (!is_string($ps) || $ps === '') {
            self::trace("getSpeedStatus: GET PortSetting.js vuoto/false");
            return null;
        }
        if (!preg_match('/Port_Setting\\s*=\\s*(\\[[^;]+\\])\\s*;/s', $ps, $m)) {
            $head = preg_replace('/\\s+/', ' ', substr($ps, 0, 300));
            $hint = '';
            if (stripos($ps, '<html') !== false) $hint = ' (HTML: sessione scaduta o login rifiutato)';
            elseif (stripos($ps, 'Login') !== false && stripos($ps, 'timeout') !== false) $hint = ' (pagina di login timeout)';
            self::trace("getSpeedStatus: Port_Setting non trovato (" . strlen($ps) . " bytes)$hint; head=$head");
            self::invalidateGambit();
            return null;
        }
        $bits = array();
        if (preg_match_all('/\\[\\s*\\d+\\s*,\\s*(\\d+)/', $m[1], $bm)) {
            foreach ($bm[1] as $b) $bits[] = $b;
        }
        if (empty($bits)) {
            self::trace("getSpeedStatus: Port_Setting trovato ma vuoto");
            return null;
        }

        self::$speedStatus = implode('', $bits);
        self::trace("getSpeedStatus OK: speed_status=" . self::$speedStatus);
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
