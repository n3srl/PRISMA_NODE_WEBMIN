<?php

/*Lingue attualmente previste:
    - italiano (default)
    - inglese
    - portoghese
    - tedesco 
    - greco
    - francese
*/

define("en_US", "en_US");
define("it_IT", "it_IT");
define("de_DE", "de_DE");
define("pt_PT", "pt_PT");
define("el_GR", "el_GR");
define("fr_FR", "fr_FR");

define("ISO_2022_JP", "ISO-2022-JP");
define("ISO_8859_1", "ISO-8859-1");
define("ISO_8859_2", "ISO-8859-2");
define("ISO_8859_5", "ISO-8859-5");
define("ISO_3166_2", "ISO-3166-2");
define("ISO_8859_7", "ISO-8859-7");


define("ISO693_3611_en_US", "en-US");
define("ISO693_3611_it_IT", "it-IT");
define("ISO693_3611_de_DE", "de-DE");
define("ISO693_3611_pt_PT", "pt-PT");
define("ISO693_3611_el_GR", "el-GR");
define("ISO693_3611_fr_FR", "fr-FR");


//usati in java see: http://docs.oracle.com/javase/1.4.2/docs/api/java/nio/charset/Charset.html
define("US_ASCII", "US-ASCII");
define("UTF_16BE", "UTF-16BE");
define("UTF_16LE", "UTF-16LE");
define("UTF_8", "UTF-8");
define("UTF8", "utf-8");
define("UTF_16", "UTF-16");



/**
 * This class enable enanched debug file logging.
 * 
 * Set $locale_path before you use it, this contains the absolute path (including locale) structured as follow.
 * 
 * locale\
 *      de_DE\
 *          LC_MESSAGES\
 *      ja_JP\
 *          LC_MESSAGES\
 * 
 * e.g. c:\\apache-2.2\\htdocs\\locale
 * 
 * 
 *  “Pensa da uomo saggio ma comunica nel linguaggio del popolo”
 *   William Butler Yeats
 * 
 * Version 1.0
 * @author Andrea Novati - Software Architect and Developer
 * @copyright N3 S.n.c. - Research & Development Dep.
 */

 
class PrismaMultilanguage {

    private static $_locale = null;
    private static $_lang = '';
    private static $_charset = UTF_8;
    private static $_web_charset = UTF8;
    private static $_CRChar = "<br/>";
    private static $_w3c_lang = ISO693_3611_it_IT;
    private static $_domain = "messages";
    public static $_file_charset = US_ASCII;
    private static $_time_zone;
    public static $_decimal_symbol;
    public static $_grouping_symbol='';
    
    
    public static function getCurrentDateWithTimezone () {
       $datetime = new DateTime();
       $otherTZ  = new DateTimeZone(PrismaMultilanguage::$_time_zone);
       $datetime->setTimezone($otherTZ);
       return $datetime->getTimestamp();
    }
    
    /** This method give to you the singleton PrismaMultilanguage reference
     *
     * @staticvar string $s_debugger Static reference to singleton class
     * @param type $log_file_path the absolute file path in witch the log file will be saved
     * @param type $log_level log level, 0 low (business level) n high (what you need)
     * @param type $debug if $_debug == 1 log is enabled else log is disabled 
     * @return N3Debugger  Static reference to singleton class
     */
    public static function getInstance($loc = null) {
        static $s_multilanguage;
        
        if (isset($_POST['locale'])) {
            $loc = $_POST['locale'];
            setcookie("locale", $loc, strtotime('+1 hour'));
        }
        
        //check locale from browser
        if ($loc === null) {
            if (isset($_COOKIE['locale'])) {
                $loc = $_COOKIE['locale'];
            } else {
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                    if (strlen($_SERVER['HTTP_ACCEPT_LANGUAGE']) >= 5) {
                        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
                    } else {
                        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                    }

                    switch($lang){
                        case 'it': $country = "IT";
                                    break;
                        case 'en': $country = "US";
                                    break;
                        case 'de': $country = "DE";
                                    break;
                        case 'pt': $country = "PT";
                                    break;
                        case 'el': $country = "GR";
                                    break;
                        case 'fr': $country = "FR";
                                    break;
                        default:   $lang = "it";
                                   $country = "IT";
                                   break;
                    }
                    

                    $loc = $lang . "_" . $country;
                    
                    setcookie("locale", $loc, strtotime('+1 hour'));
                } else {
                    $loc = "it_IT";
                }
            }
        } 
        $allowed_lang = ['en_US', 'it_IT', 'de_DE', 'pt_PT', 'el_GR', "fr_FR"];
        //check allowed
        //if (!( $loc == es_ES || $loc == cs_CZ || $loc == de_DE || $loc == en_UK || $loc == fr_FR || $loc == hu_HU || $loc == it_IT || $loc == ja_JP || $loc == pl_PL || $loc == ru_RU ))
        if (!(in_array($loc, $allowed_lang))) {
            /*if ($_SERVER['PHP_SELF']!='/traduzione_non_disponibile.php')
                header("location: traduzione_non_disponibile.php?LC_ALL=" . $loc);
            else return;*/
            $loc = it_IT; //default
        }
        if ($s_multilanguage == null) {
            $s_multilanguage = new PrismaMultilanguage();
        }

        PrismaMultilanguage::changeLocale($loc);

        return $s_multilanguage;
    }

    private function __construct() {
        
    }

    public static function remove_utf8_bom($text) {
        $bom = pack('H*', 'EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    public static function formatTimeShort($ts){
         $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::SHORT,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
    public static function formatFull($ts) {
        $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
    public static function formatDefault($ts) {
        $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }

    public static function formatDateShort($ts) {
        $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::NONE,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
    public static function formatDateFormat($ts,$format_string) {
       $date = DateTime::createFromFormat('U', $ts);
       return  $date->format($format_string);
    }

    public static function formatShort($ts){
        return PrismaMultilanguage::formatDateShort($ts);
    }
    
    public static function format($ts){
        return PrismaMultilanguage::formatDateShort($ts);
    }
    
    public static function formatDateFull($ts){
         $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
    public static function formatDateTimeFull($ts){
         $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::SHORT,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
      
    public static function formatDateMedium($ts){
         $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::NONE,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
    public static function formatDateTime($ts){
         $fmt = new IntlDateFormatter(
            PrismaMultilanguage::$_locale,
            IntlDateFormatter::SHORT,
            IntlDateFormatter::SHORT,
            PrismaMultilanguage::$_time_zone ,
            IntlDateFormatter::GREGORIAN
        );
        
        return $fmt->format($ts);
    }
    
        public static function changeLocale($loc) {
        global $locale_path;
        if (!isset($locale_path) || empty($locale_path)) {
            $locale_path = $_SERVER['DOCUMENT_ROOT'].'/locale/'; 
        }
        switch($loc){
            case 'it_IT':  PrismaMultilanguage::$_locale = it_IT;
                        PrismaMultilanguage::$_lang = "it";
                        date_default_timezone_set('Europe/Rome');
                        PrismaMultilanguage::$_time_zone = 'Europe/Rome';
                        PrismaMultilanguage::$_charset = UTF_8;
                        PrismaMultilanguage::$_web_charset = UTF8;
                        PrismaMultilanguage::$_w3c_lang = ISO693_3611_it_IT;
                        PrismaMultilanguage::$_file_charset = ISO_8859_1;
                        break;
            case 'en_US':  PrismaMultilanguage::$_locale = en_US;
                        PrismaMultilanguage::$_lang = "en";
                        date_default_timezone_set('America/New_York');
                        PrismaMultilanguage::$_time_zone = 'America/New_York';
                        PrismaMultilanguage::$_charset = UTF_8;
                        PrismaMultilanguage::$_web_charset = UTF8;
                        PrismaMultilanguage::$_w3c_lang = ISO693_3611_en_US;
                        PrismaMultilanguage::$_file_charset = US_ASCII;
                        break;
            case 'de_DE':  PrismaMultilanguage::$_locale = de_DE;
                        PrismaMultilanguage::$_lang = "de";
                        date_default_timezone_set('Europe/Berlin');
                        PrismaMultilanguage::$_time_zone = 'Europe/Berlin';
                        PrismaMultilanguage::$_charset = UTF_8;
                        PrismaMultilanguage::$_web_charset = UTF8;
                        PrismaMultilanguage::$_w3c_lang = ISO693_3611_de_DE;
                        PrismaMultilanguage::$_file_charset = ISO_8859_1;
                        break;
            case 'pt_PT': PrismaMultilanguage::$_lang = "pt";
                       PrismaMultilanguage::$_locale = pt_PT;
                       date_default_timezone_set('Europe/Lisbon');
                       PrismaMultilanguage::$_time_zone = 'Europe/Lisbon';
                       PrismaMultilanguage::$_charset = UTF_8;
                       PrismaMultilanguage::$_web_charset = UTF8;
                       PrismaMultilanguage::$_w3c_lang = ISO693_3611_pt_PT;
                       PrismaMultilanguage::$_file_charset = ISO_8859_1;
                       break;
            case 'el_GR': PrismaMultilanguage::$_lang = "el";
                       PrismaMultilanguage::$_locale = el_GR;
                       date_default_timezone_set('Europe/Athens');
                       PrismaMultilanguage::$_time_zone = 'Europe/Athens';
                       PrismaMultilanguage::$_charset = UTF_8;
                       PrismaMultilanguage::$_web_charset = UTF8;
                       PrismaMultilanguage::$_w3c_lang = ISO693_3611_el_GR;
                       PrismaMultilanguage::$_file_charset = ISO_8859_1;
                       break;
            case 'fr_FR': PrismaMultilanguage::$_locale = fr_FR;
                       PrismaMultilanguage::$_lang = "fr";
                       date_default_timezone_set('Europe/Paris');
                       PrismaMultilanguage::$_time_zone = 'Europe/Paris';
                       PrismaMultilanguage::$_charset = UTF_8;
                       PrismaMultilanguage::$_web_charset = UTF8;
                       PrismaMultilanguage::$_w3c_lang = ISO693_3611_fr_FR;
                       PrismaMultilanguage::$_file_charset = ISO_8859_1;
                       break;
                    
            default:PrismaMultilanguage::$_locale = it_IT;
                       PrismaMultilanguage::$_lang = "it";
                       date_default_timezone_set('Europe/Rome');
                       PrismaMultilanguage::$_time_zone = 'Europe/Rome';
                       PrismaMultilanguage::$_charset = UTF_8;
                       PrismaMultilanguage::$_web_charset = UTF8;
                       PrismaMultilanguage::$_w3c_lang = ISO693_3611_it_IT;
                       PrismaMultilanguage::$_file_charset = ISO_8859_1;
                       break;
        }
       
        $locale = PrismaMultilanguage::$_locale;

        if (!isset($locale_path) || empty($locale_path)) {
            $locale_path = $_SERVER['DOCUMENT_ROOT']."/locale/$locale/LC_MESSAGES/messages.mo"; 
        }
        

		$full_path               = bindtextdomain("messages", $locale_path);

        //debug output per binding:
        /*
        if ($full_path === false) {
            error_log("Error: Failed to bind text domain for locale: $locale");
            die("Error: Failed to bind text domain for locale: $locale");
        } else {
            die("Successfully bound text domain to path: $full_path");
        }*/
         

        //$bind_textdomain_codeset = bind_textdomain_codeset(PrismaMultilanguage::$_domain, PrismaMultilanguage::$_charset);
        $bind_textdomain_codeset = bind_textdomain_codeset("messages", PrismaMultilanguage::$_charset);
       
        //debug output per binding:
        /*
        if ($bind_textdomain_codeset === false) {
            error_log("Error: Failed to set text domain codeset for locale: $locale");
            die("Error: Failed to set text domain codeset for locale: $locale");
        } else {
            die("Successfully set text domain codeset: $bind_textdomain_codeset");
        }*/
        //die($locale);
       // $textdomain              = textdomain(PrismaMultilanguage::$_domain);
       
	    $textdomain              = textdomain("messages");

        //debug output per binding:
        /*
        if ($textdomain === false) {
            error_log("Error: Failed to set text domain: $locale");
            die("Error: Failed to set text domain: $locale");
        } else {
            die("Successfully set text domain: $textdomain");
        }*/
        
        setlocale(LC_ALL, $locale);

    

    // Debug output locale
    //echo "<br>Locale: " . PrismaMultilanguage::$_locale;
    //echo "<br>Path: " . $locale_path;
    }
   

    public static function getCharset() {
        return PrismaMultilanguage::$_charset;
    }

    public static function getWebCharset() {
        return PrismaMultilanguage::$_web_charset;
    }

    public static function getFileCharset() {
        return PrismaMultilanguage::$_file_charset;
    }

    public static function getLang() {
        return PrismaMultilanguage::$_lang;
    }

    public static function getW3CLang() {
        return PrismaMultilanguage::$_w3c_lang;
    }

    public static function getCRChar() {
        return PrismaMultilanguage::$_CRChar;
    }

    public static function getLocale() {
        return PrismaMultilanguage::$_locale;
    }

    public static function parseDate($string) {
        $allowed_lang = ['en_US', 'it_IT', 'de_DE', 'pt_PT', 'el_GR', "fr_FR"];
        if ($string == "")
            return $string;

        if (in_array(PrismaMultilanguage::$_locale, $allowed_lang)){
            $time = strtotime($string);
        }

        if (PrismaMultilanguage::$_locale == it_IT ||
            PrismaMultilanguage::$_locale == pt_PT ||
            PrismaMultilanguage::$_locale == el_GR ||
            PrismaMultilanguage::$_locale == fr_FR){
            $string = str_replace("/", "-", $string);
        }

        return $time;
    }

    public static function formatNumber($number,$digits=2) {
        $a = new NumberFormatter(PrismaMultilanguage::$_locale,NumberFormatter::DECIMAL);
        $a->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, $digits); 
        $a->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $digits);
        numfmt_set_symbol($a, NumberFormatter::GROUPING_SEPARATOR_SYMBOL, PrismaMultilanguage::$_grouping_symbol);
        numfmt_set_symbol($a, NumberFormatter::DECIMAL_SEPARATOR_SYMBOL, PrismaMultilanguage::$_decimal_symbol);
        return $a->format($number);
    }

    public static function formatIntNumber($number) {
        $a = new NumberFormatter(PrismaMultilanguage::$_locale,NumberFormatter::DECIMAL);
        numfmt_set_symbol($a, NumberFormatter::GROUPING_SEPARATOR_SYMBOL, PrismaMultilanguage::$_grouping_symbol);
        return $a->format($number);
    }

    public static function getViewOptions() {
        ?>
        <div id="N3LanguageSelector" >
             <form method="POST" action="<?= htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES) ?>">
                    <select name="language" id="language" onchange="this.form.submit()" style="width: 100px; height: 20px; font-size: 12px;">
                    <option id="en" value="<?= en_US ?>"<?php if (PrismaMultilanguage::$_locale == en_US) echo " selected" ?>><?= _('Inglese') ?></option>
                    <option id="it" value="<?= it_IT ?>"<?php if (PrismaMultilanguage::$_locale == it_IT) echo " selected" ?>><?= _('Italiano') ?></option>
                    <option id="de" value="<?= de_DE ?>"<?php if (PrismaMultilanguage::$_locale == de_DE) echo " selected" ?>><?= _('Tedesco') ?></option>
                    <option id="pt" value="<?= pt_PT ?>"<?php if (PrismaMultilanguage::$_locale == pt_PT) echo " selected" ?>><?= _('Portoghese') ?></option>
                    <option id="el" value="<?= el_GR ?>"<?php if (PrismaMultilanguage::$_locale == el_GR) echo " selected" ?>><?= _('Greco') ?></option>
                    <option id="fr" value="<?= fr_FR ?>"<?php if (PrismaMultilanguage::$_locale == fr_FR) echo " selected" ?>><?= _('Francese') ?></option>
                </select>
            </form>
        </div>


        <script type="text/javascript">
            function languageChanged() {
                $("#locale").submit();
            }
        </script>
        <?php
    }

}

function mb_str_replace($search, $replace, $subject) {
    if (is_array($subject)) {
        foreach ($subject as $key => $val) {
            $subject[$key] = mb_str_replace((string) $search, $replace, $subject[$key]);
        }
        return $subject;
    }
    $pattern = '/(?:' . implode('|', array_map(create_function('$match', 'return preg_quote($match[0], "/");'), (array) $search)) . ')/u';
    if (is_array($search)) {
        if (is_array($replace)) {
            $len = min(count($search), count($replace));
            $table = array_combine(array_slice($search, 0, $len), array_slice($replace, 0, $len));
            $f = create_function('$match', '$table = ' . var_export($table, true) . '; return array_key_exists($match[0], $table) ? $table[$match[0]] : $match[0];');
            $subject = preg_replace_callback($pattern, $f, $subject);
            return $subject;
        }
    }
    $subject = preg_replace($pattern, (string) $replace, $subject);
    return $subject;
}


function po_read($locale_path) {
    if (file_exists($locale_path)) {
        $po = file_get_contents($locale_path);
        return $po;
    } else {
        return '';
    }
}


?>