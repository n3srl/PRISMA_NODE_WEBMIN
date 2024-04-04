<?php

/* PROD */

$db_rdbms = "inaf";
$db_name = 'inaf';
$db_user = 'root';
$db_pass = 'root';
$db_host = 'localhost';
$db_port = '8889';

date_default_timezone_set('UTC');

define('_DB_NAME_', $db_name);
define('_EXTLIB_', 'C:/Apache24/htdocs/ext_lib/');
define('_WEBROOTDIR_', 'C:/Apache24/htdocs/');
define('_IMGFILEURL_', 'http://34.78.124.10/img/');
define('_FILEUPLADPATH_', 'C:/Apache24/htdocs/export');

define('_ENABLEWAREHOUSE_', false);

define('_SMSMITTENTE_', '+39000000000');
define('_SEVERNAMEC_', '34.78.124.10');

//Storage files
define('_FREETURE_', 'c:/freeture/configuration.cfg');
define('_FREETURE_DATA_', 'c:/freeture/');
//define('_OVPN_', '/usr/local/share/openvpn/client.conf');
define('_OVPN_', 'c:/etc/openvpn/client.conf');
//define('_PROMETHEUS_', '/usr/local/share/prometheus/prometheus.yml');
define('_PROMETHEUS_', 'c:/etc/prometheus/prometheus.yml');
define('_PASSWD_', '/keys/passwd.txt');

//SSH access for Docker
define('_DOCKER_IP_', '127.0.0.1');
define('_DOCKER_PORT_', '22');
define('_DOCKER_SSH_PRI_', '/keys/chiave');
define('_DOCKER_SSH_PUB_', '/keys/chiave.pub');

// Utils
define('_CALIBRATION_PATH_','C:/Apache24/htdocs/calibration/');

define('_DEFAULT_STATION_CODE_','DEFAULT');
define('_DEFAULT_STATION_NAME_','DEFAULT');
