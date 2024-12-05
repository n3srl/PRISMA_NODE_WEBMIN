<?php
//include $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/lib/core/v1/autoloadBase.php';
define("_DEFAULT_LANG_C", "en_US");
define("_DEFAULT_LANG", "en");
define("_DEFAULT_COUNTRY", "US");

PrismaMultilanguage::GetCurrentLocaleFromHeader();
$language = $_SESSION["lang"] ?? PrismaMultilanguage::GetCurrentLocaleFromHeader();

$multilanguageInstance = PrismaMultilanguage::getInstance($language);
define('_VERSION_', '?ver=5.3.4');
session_start();

if ($db_rdbms == 'PostgreSQL'){
    $connect_string = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass";
    $db_conn = @pg_connect($connect_string);
    if ($db_conn === false ){
            echo '<h1 style="background:red">Connection to DBMS failed: '.$connect_string.'</h1>';
    }
} else {
	
}

function __autoload_libraries($name) {
//     set_time_limit(0);
    if (is_file($_SERVER['DOCUMENT_ROOT']."/model/" . $name . ".php")){
//var_dump($_SERVER['DOCUMENT_ROOT']."/model/" . $name . ".php");
        include $_SERVER['DOCUMENT_ROOT']."/model/" . $name . ".php";
	}
    if (is_file($_SERVER['DOCUMENT_ROOT']."/service/" . $name . ".php")){
//var_dump($_SERVER['DOCUMENT_ROOT']."/service/" . $name . ".php");
        include $_SERVER['DOCUMENT_ROOT']."/service/" . $name . ".php";
	}
}

spl_autoload_register('__autoload_libraries');

function parseNumber($n) {
    return $n;
}

function parseText($n) {
    
    $n = str_replace("\"", "&quot;", $n);
    $n = nl2br(stripslashes(str_replace("\\r\\n", "&#13;&#10;",$n)));
       return $n;
}

function parseTextTextbox($n){
    return $n;
}

function parseTextTable($n) {
    $n = str_replace("\"", "&quot;", $n);
    $n = nl2br(stripslashes(str_replace("\\r\\n", "<br>",$n)));
    
    return $n;
}

function parseDate($n) {
   
   
    if($n != ""){
        $res = date("d/m/Y", strtotime(str_replace('-','/', $n)));
    }else{
        $res = date("d/m/Y");
    }
        
    return $res;
}

function parseDateFromUnix($n){
    if($n != ""){
        $res = date("d/m/Y", $n);
    }else{
        $res = date("d/m/Y");
    }
        
    return $res;
}
function parseDateToMysql($n){
   if ($n=='') 
        return null;
    $n=explode("/",$n);
    return strtotime($n[2]."-".$n[1]."-".$n[0]." 00:00:00");

}

function parseDateToDateCreate($n){
    
    if ($n=='') 
        return null;
    $n=explode("/",$n);
    
    return $n[2]."-".$n[1]."-".$n[0];
    
}

function parseDateReverse($n){
    
    if (empty($n)) 
        return null;
    $n=explode("-",$n);
    $dh = explode(" ",$n[2]);
    return $dh[0]."/".$n[1]."/".$n[0];
}

function includeJsFooterFromDir($dir){
    $files_tmp = scandir($_SERVER["DOCUMENT_ROOT"].$dir);
    $files = array();
    foreach ($files_tmp as $key => $value) 
    {
            if (!in_array($value, array(".", "..")))
            {
                    $files[] = $value;
            }
    }
    //Includo CRUD
    foreach ($files as $file){
            if (strpos($file, '.js') !== false)
                    echo '<script src="' . $dir."/".$file . _VERSION_.'"></script>';
    }
}

