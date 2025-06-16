<?php

define("BASE_ENV", "LNX");

if(BASE_ENV == "WIN") {
    define("BASE_PATH", "C:\\xampp\\htdocs\\");
    define("PASSWD_PATH", BASE_PATH."keys/");
    define("FREETURE_CFG_PATH", BASE_PATH."config/");
} else {
    define("BASE_PATH", "/var/www/html/");
    define("PASSWD_PATH","/keys/");
    define("FREETURE_CFG_PATH", "/usr/local/share/freeture/");
}

/* PROD */

define("_WEBMIN_VERSION_NUMBER_", "v1.0.1");
define("_WEBMIN_VERSION_", "WEBMIN ". _WEBMIN_VERSION_NUMBER_);

$db_rdbms = "inaf";
$db_name = 'inaf';
$db_user = 'root';
$db_pass = 'root';
$db_host = 'localhost';
$db_port = '8889';

date_default_timezone_set('UTC');

define('_DB_NAME_', $db_name);
define('_EXTLIB_', BASE_PATH.'ext_lib/');
define('_WEBROOTDIR_', BASE_PATH);
define('_IMGFILEURL_', 'http://34.78.124.10/img/');
define('_FILEUPLADPATH_', BASE_PATH.'export');
define('_ENABLEWAREHOUSE_', false);

define('_SMSMITTENTE_', '+39000000000');
define('_SEVERNAMEC_', '34.78.124.10');

//Storage files
define('_FREETURE_', FREETURE_CFG_PATH.'configuration.cfg');
define('_FREETURE_DATA_', '/freeture/');
//define('_OVPN_', '/usr/local/share/openvpn/client.conf');
define('_OVPN_', '/etc/openvpn/client.conf');
//define('_PROMETHEUS_', '/usr/local/share/prometheus/prometheus.yml');
define('_PROMETHEUS_', '/etc/prometheus/prometheus.yml');
define('_PASSWD_', PASSWD_PATH.'passwd.txt');

//SSH access for Docker
define('_DOCKER_IP_', '127.0.0.1');
define('_DOCKER_PORT_', '22');
define('_DOCKER_SSH_PRI_', '/keys/chiave');
define('_DOCKER_SSH_PUB_', '/keys/chiave.pub');

// Utils
define('_CALIBRATION_PATH_',BASE_PATH.'calibration/');

define('_DEFAULT_STATION_CODE_','DEFAULT');
define('_DEFAULT_STATION_NAME_','DEFAULT');
