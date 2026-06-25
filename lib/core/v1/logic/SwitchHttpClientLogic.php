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
     * Abilita/disabilita una porta dello switch via scraping del form Port
     * Setting. Riusa la sessione del cable diag (Gambit cached).
     *
     * Il form Port_Setting sul DGS-1210 prende speed=6 per "Disable" e speed=5
     * per "Auto" (ricavato da port_speed_option = ['1000M Full','100M Full',
     * '100M Half','10M Full','10M Half','Auto','Disable']).
     *
     * Best-effort: nessuna garanzia sull'URL esatto del form perche' varia con
     * il firmware. Proviamo i pattern noti, e in caso di errore ritorniamo il
     * trace con cosa ogni endpoint ha risposto.
     *
     * @param int  $port    1..N
     * @param bool $enabled true = abilita (Auto), false = disabilita
     * @return array { ok, error?, trace }
     */
    public static function setPortAdmin($port, $enabled)
    {
        self::$debugTrace = array();
        $port = (int) $port;
        if ($port <= 0) {
            return array('ok' => false, 'error' => "Porta non valida ($port).", 'trace' => self::flushTrace());
        }
        if (!self::isConfigured()) {
            return array('ok' => false, 'error' => 'Switch HTTP non configurato.', 'trace' => self::flushTrace());
        }

        self::trace("setPortAdmin start port=$port enabled=" . ($enabled ? '1' : '0'));

        $g = self::login();
        if (!$g) {
            return array('ok' => false, 'error' => 'Login allo switch fallito.', 'trace' => self::flushTrace());
        }

        $host = _SWITCH_IP_;
        // Pre-touch della pagina Port Settings: alcuni firmware vincolano la
        // POST allo stesso path che ha caricato il form.
        foreach (array('Port_Settings.htm', 'PortSetting.htm', 'specific/Port_Setting.htm') as $page) {
            $u = "http://$host/iss/$page?Gambit=$g";
            $b = self::httpGet($u, "http://$host/");
            if (is_string($b) && stripos($b, 'Port_Setting') !== false) {
                self::trace("setPortAdmin pre-touch OK: $u (" . strlen($b) . " bytes)");
                break;
            }
        }

        // speed: 5 = Auto, 6 = Disable (vedi port_speed_option DGS-1210)
        $speed = $enabled ? 5 : 6;
        // Endpoint candidati: il primo che ritorna non-HTML e non-timeout vince.
        // I parametri standard DGS-1210 (6.30) sono: Gambit, port_from/to, speed,
        // flow, mdix. Mando defaults safe ricavabili da Port_Setting.
        $referer = "http://$host/iss/Port_Settings.htm?Gambit=$g";
        $candidates = array(
            // (URL, fields)
            array("http://$host/form/formPortSetting", array(
                'Gambit'    => $g,
                'PortFrom'  => $port, 'PortTo' => $port,
                'Speed'     => $speed,
                'FlowState' => 0, 'MdixState' => 0, 'CapState' => 0,
            )),
            array("http://$host/iss/specific/Port_Setting.htm", array(
                'Gambit'    => $g,
                'PortFrom'  => $port, 'PortTo' => $port,
                'Speed'     => $speed,
                'FlowState' => 0, 'MdixState' => 0,
            )),
            array("http://$host/iss/Port_Settings.htm", array(
                'Gambit'  => $g,
                'fmportfm' => $port, 'fmportto' => $port,
                'fmspeed'  => $speed,
            )),
        );

        $lastBody = '';
        foreach ($candidates as $cand) {
            list($url, $fields) = $cand;
            $resp = self::httpPost($url, $fields);
            $lastBody = is_string($resp) ? $resp : '';
            $head = preg_replace('/\\s+/', ' ', substr($lastBody, 0, 200));
            self::trace("setPortAdmin POST $url -> " . strlen($lastBody) . " bytes, head=$head");

            if (!is_string($resp) || $resp === '') continue;
            if (stripos($resp, 'Login') !== false && stripos($resp, 'timeout') !== false) {
                self::invalidateGambit();
                return array('ok' => false, 'error' => 'Sessione switch scaduta durante il cambio porta.', 'trace' => self::flushTrace());
            }
            // Heuristica successo: il DGS-1210 di solito ritorna la stessa pagina
            // Port_Settings con i nuovi valori applicati, quindi contiene di
            // nuovo "Port_Setting".
            if (stripos($resp, 'Port_Setting') !== false || stripos($resp, 'Apply') !== false || stripos($resp, 'Success') !== false) {
                self::trace("setPortAdmin probabilmente OK (response coerente)");
                // Invalido la cache Port_Setting in-memory cosi' al prossimo
                // getSpeedStatus rilegge il valore nuovo.
                self::$speedStatus = null;
                return array(
                    'ok'    => true,
                    'port'  => $port,
                    'state' => $enabled ? 'enabled' : 'disabled',
                    'trace' => self::flushTrace(),
                );
            }
        }

        return array(
            'ok'    => false,
            'error' => "Nessuno degli endpoint candidati ha accettato la modifica della porta. Probabilmente il path del form e' diverso su questo firmware: apri la GUI switch, vai in Port Settings, cambia una porta, e nella Network tab del browser leggi URL+parametri della POST.",
            'raw'   => substr($lastBody, 0, 500),
            'trace' => self::flushTrace(),
        );
    }

    /**
     * Probe esplorativo delle pagine PoE della GUI: prova un elenco di URL
     * candidati noti per il DGS-1210, fa GET di ciascuno e ritorna:
     *   array(
     *     'url'         => '...',
     *     'httpCode'    => 200,
     *     'size'        => 1234,
     *     'head'        => 'primi 400 char della response',
     *     'looksLikePoE'=> true,  // contiene 'POWER ON'/'POWER OFF' o pattern Watt
     *   )
     * Cosi' l'utente identifica con un colpo d'occhio quale URL ritorna i
     * dati della tabella PoE Port Settings.
     */
    public static function exploreHttpPoEPaths()
    {
        $out = array();
        if (!self::isConfigured()) return $out;
        $g = self::login();
        if (!$g) return $out;
        $host = _SWITCH_IP_;

        // Lista larga di URL candidati. Pattern noti del DGS-1210 6.30:
        //   /iss/<PageName>.htm           = pagina HTML (top-level: spesso un
        //                                   frameset generico che mostra
        //                                   DeviceInfo come default, NON utile)
        //   /iss/specific/<PageName>.js   = file JS con i dati come array
        //                                   JavaScript (questo e' quello utile)
        //
        // L'utente ITER10 ha confermato che il link del menu apre
        // "PoE_Port_Setting.htm" (SINGOLARE), quindi i file JS companion piu'
        // probabili sono "PoE_Port_Setting.js" / "specific/PoE_Port_Setting.js".
        // Provo entrambe le forme (singolare + plurale) + alcune varianti
        // camelcase note dei vari firmware D-Link.
        $candidates = array(
            // .htm singolare (URL confermato dal menu del DGS-1210 6.30)
            "http://$host/iss/PoE_Port_Setting.htm?Gambit=$g",
            "http://$host/iss/PoE_Port_Settings.htm?Gambit=$g",
            "http://$host/iss/PoE_PortSetting.htm?Gambit=$g",
            "http://$host/iss/PoE_Setting.htm?Gambit=$g",
            "http://$host/iss/PoE_Status.htm?Gambit=$g",
            "http://$host/iss/PoE.htm?Gambit=$g",
            // .js companion del file HTML omonimo - prima la forma singolare
            "http://$host/iss/specific/PoE_Port_Setting.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Port_Settings.js?Gambit=$g",
            "http://$host/iss/specific/PoE_PortSetting.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Setting.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Status.js?Gambit=$g",
            "http://$host/iss/specific/PoEPortInfo.js?Gambit=$g",
            "http://$host/iss/specific/PoEPortSetting.js?Gambit=$g",
            "http://$host/iss/specific/PoESetting.js?Gambit=$g",
            "http://$host/iss/specific/PoE.js?Gambit=$g",
            "http://$host/iss/specific/PoEPortSetting_Ajax_Data.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Port_Setting_Ajax_Data.js?Gambit=$g",
            // Alcuni firmware espongono un main frame separato
            "http://$host/iss/PoE_Port_Setting_main.htm?Gambit=$g",
            "http://$host/iss/PoE_Port_Setting_iframe.htm?Gambit=$g",
        );
        foreach ($candidates as $url) {
            $body = self::httpGet($url, "http://$host/");
            $isStr = is_string($body);
            $size  = $isStr ? strlen($body) : 0;
            $head  = $isStr ? preg_replace('/\\s+/', ' ', substr($body, 0, 400)) : '';
            // Euristica: la pagina e' utile se contiene "POWER ON" / "POWER OFF"
            // o "Class 1..4" o "PoE_Setting"/"PoE_Status" come variabile JS.
            $looks = false;
            if ($isStr) {
                if (preg_match('/POWER\\s*O[NF]/i', $body)) $looks = true;
                elseif (preg_match('/(?:PoE|Poe|POE)[_ ](Setting|Status|Port)/', $body)) $looks = true;
                elseif (preg_match('/Class\\s*[1-4]/', $body) && preg_match('/[0-9]+\\.[0-9]+/', $body)) $looks = true;
            }
            $out[] = array(
                'url'          => $url,
                'httpCode'     => $isStr ? 200 : 0, // cURL ritorna false su errore
                'size'         => $size,
                'looksLikePoE' => $looks,
                'head'         => $head,
            );
        }
        return $out;
    }

    /**
     * Scrape della pagina "PoE Port Settings" della GUI dello switch per
     * ottenere il consumo realtime in Watt + Voltage + Current + Class +
     * Status per ogni porta. Il MIB SNMP standard NON espone i Watt realtime,
     * e il MIB privato D-Link sul DGS-1210 6.30 nemmeno: l'unica via e' la GUI.
     *
     * Ritorna map ifIndex -> array(
     *   'powerW'  => 2.8,
     *   'voltage' => 53.2,
     *   'current' => 53.0,
     *   'class'   => 'Class 1' | 'N/A',
     *   'status'  => 'POWER ON' | 'POWER OFF',
     * )
     * Se nessuna pagina candidata risponde con dati riconoscibili, ritorna
     * array vuoto (il caller fallback su SNMP-only).
     */
    public static function getPoEPortPower()
    {
        if (!self::isConfigured()) return array();
        $g = self::login();
        if (!$g) return array();

        $host = _SWITCH_IP_;
        // URL candidati della pagina PoE Port Settings sul DGS-1210 6.30.
        // L'URL del menu e' "PoE_Port_Setting.htm" (SINGOLARE, confermato sul
        // nodo ITER10), quindi il file JS companion piu' probabile e'
        // "PoE_Port_Setting.js". Mantengo anche le varianti plurali e camelcase
        // per supportare altri firmware D-Link.
        $candidates = array(
            "http://$host/iss/specific/PoE_Port_Setting.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Port_Settings.js?Gambit=$g",
            "http://$host/iss/specific/PoE_PortSetting.js?Gambit=$g",
            "http://$host/iss/specific/PoEPortSetting.js?Gambit=$g",
            "http://$host/iss/specific/PoE_Setting.js?Gambit=$g",
            "http://$host/iss/PoE_Port_Setting.htm?Gambit=$g",
            "http://$host/iss/PoE_Port_Settings.htm?Gambit=$g",
            "http://$host/iss/PoE_PortSetting.htm?Gambit=$g",
        );

        foreach ($candidates as $url) {
            $body = self::httpGet($url, "http://$host/");
            if (!is_string($body) || $body === '') continue;
            if (stripos($body, 'Login') !== false && stripos($body, 'timeout') !== false) {
                self::invalidateGambit();
                return array();
            }

            // Pattern 1: array JavaScript "var PoE_Setting = [['1','Enabled','Normal',...,
            //   power_W, voltage_V, current_mA, classification, status], ...];"
            // Cerchiamo il primo array che assomiglia a una tabella PoE: deve avere
            // almeno una tupla con valori numerici simili a Watt/Voltage/Current.
            // Pattern molto tollerante: estraggo tutte le tuple [...] che contengono
            // 'POWER ON' o 'POWER OFF' e parso da li'.
            $result = array();
            if (preg_match_all('/\\[\\s*[\'"]?(\\d+)[\'"]?\\s*,([^\\[\\]]+?)(?:POWER\\s*ON|POWER\\s*OFF)[^\\[\\]]*\\]/i', $body, $tuples, PREG_SET_ORDER)) {
                foreach ($tuples as $t) {
                    $portN = (int) $t[1];
                    if ($portN <= 0) continue;
                    // Riprendo l'intera tupla matchata per parsarla per intero.
                    $fullTuple = $t[0];
                    // Estraggo tutti i valori (stringhe quoted o numeri).
                    if (!preg_match_all('/[\'"]([^\'"]*)[\'"]|(-?\\d+(?:\\.\\d+)?)/', $fullTuple, $vals)) continue;
                    $items = array();
                    foreach ($vals[0] as $i => $v) {
                        if ($vals[1][$i] !== '') $items[] = $vals[1][$i];
                        else if ($vals[2][$i] !== '') $items[] = $vals[2][$i];
                    }
                    // Posizioni tipiche su DGS-1210 PoE_Setting:
                    //   [0]=port, [1]=state, [2]=time_range, [3]=priority,
                    //   [4]=delay_power_detect, [5]=legacy_pd, [6]=power_limit_text,
                    //   [7]=power_W, [8]=voltage_V, [9]=current_mA,
                    //   [10]=classification, [11]=status
                    // Approccio robusto: cerco l'item che e' "POWER ON"/"POWER OFF"
                    // e prendo gli ultimi 5 prima.
                    $statusIdx = -1;
                    foreach ($items as $i => $v) {
                        if (preg_match('/^POWER\\s*(ON|OFF)$/i', $v)) { $statusIdx = $i; break; }
                    }
                    if ($statusIdx < 4) continue;
                    $classification = $items[$statusIdx - 1];
                    $currentMa      = isset($items[$statusIdx - 2]) ? (float) $items[$statusIdx - 2] : null;
                    $voltageV       = isset($items[$statusIdx - 3]) ? (float) $items[$statusIdx - 3] : null;
                    $powerW         = isset($items[$statusIdx - 4]) ? (float) $items[$statusIdx - 4] : null;
                    $result[$portN] = array(
                        'powerW'  => $powerW,
                        'voltage' => $voltageV,
                        'current' => $currentMa,
                        'class'   => $classification,
                        'status'  => $items[$statusIdx],
                    );
                }
            }

            if (!empty($result)) {
                self::trace("getPoEPortPower OK da $url: " . count($result) . " porte");
                return $result;
            }
            // Se la pagina ha risposto ma non abbiamo trovato il pattern,
            // logghiamo i primi byte per debug.
            $head = preg_replace('/\\s+/', ' ', substr($body, 0, 300));
            self::trace("getPoEPortPower: $url ha risposto ma pattern non matcha; head=$head");
        }
        return array();
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
        // Estrazione del secondo elemento di ogni sub-tupla [portN, speedBits, ...].
        // Tolleriamo tre formati che firmware DGS-1210 diversi usano:
        //   - puri numeri:        [1, 0, 5, ...]
        //   - quote singole:      ['1', '0', '5', ...]   (visto su ITER10/ITUM03)
        //   - quote doppie:       ["1", "0", "5", ...]
        $bits = array();
        if (preg_match_all('/\\[\\s*[\'"]?\\d+[\'"]?\\s*,\\s*[\'"]?(\\d+)[\'"]?/', $m[1], $bm)) {
            foreach ($bm[1] as $b) $bits[] = $b;
        }
        if (empty($bits)) {
            // Dump dei primi 300 char della porzione catturata per capire il
            // formato esatto del firmware quando ci capita un nuovo modello.
            $headInner = preg_replace('/\\s+/', ' ', substr($m[1], 0, 300));
            self::trace("getSpeedStatus: Port_Setting trovato (" . strlen($m[1]) . " bytes) ma sub-tuple non parsabili. head=$headInner");
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
        // Alcuni endpoint del firmware DGS-1210 (es. Jumbo_Frame.htm) rispondono
        // in HTTP/0.9 (senza header). cURL >= 7.66 lo rifiuta di default con
        // "Received HTTP/0.9 when not allowed": abilitiamo esplicitamente.
        if (defined('CURLOPT_HTTP09_ALLOWED')) {
            curl_setopt($ch, CURLOPT_HTTP09_ALLOWED, true);
        }
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
