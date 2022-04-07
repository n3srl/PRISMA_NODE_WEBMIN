<?php

$PROD = true;

if ($PROD) {
    
    /* PROD */

    $db_rdbms = "inaf";
    $db_name = 'inaf';
    $db_user = 'root';
    $db_pass = 'root';
    $db_host = 'localhost';
    $db_port = '8889';

    date_default_timezone_set('UTC');

    define('_DB_NAME_', $db_name);
    define('_EXTLIB_', '/var/www/html/ext_lib/');
    define('_WEBROOTDIR_', '/var/www/html/');
    define('_IMGFILEURL_', 'http://34.78.124.10/img/');
    define('_FILEUPLADPATH_', '/var/www/html/export');
    
    define('_ENABLEWAREHOUSE_', false);

    define('_SMSMITTENTE_', '+39000000000');
    define('_SEVERNAMEC_', '34.78.124.10');
    
    //Storage files
    define('_FREETURE_', '/usr/local/share/freeture/configuration.cfg');
    define('_FREETURE_MASK_', '/usr/local/share/freeture/default.bmp');
    define('_FREETURE_DATA_', '/freeture/');
    //define('_OVPN_', '/etc/openvpn/client.conf');
    //define('_PROMETHEUS_', '/etc/prometheus/prometheus.yml');
    define('_OVPN_', '/usr/local/share/openvpn/client.conf');
    define('_PROMETHEUS_', '/usr/local/share/prometheus/prometheus.yml');
    define('_PASSWD_', '/keys/passwd.txt');
            
    //SSH access for Docker
    define('_DOCKER_IP_', '127.0.0.1');
    define('_DOCKER_PORT_', '22');
    define('_DOCKER_SSH_PRI_', '/keys/chiave');
    define('_DOCKER_SSH_PUB_', '/keys/chiave.pub');
} else {
    
    /* PREPROD */

    $db_rdbms = "inaf";
    $db_name = 'inaf';
    $db_user = 'root';
    $db_pass = 'root';
    $db_host = 'localhost';
    $db_port = '8889';

    date_default_timezone_set('UTC');

    define('_DB_NAME_', $db_name);
    define('_EXTLIB_', '/Users/lorenzobottini/framework-base-php-elle/ext_lib/');
    define('_WEBROOTDIR_', '/Users/lorenzobottini/framework-base-php-elle/');
    define('_IMGFILEURL_', 'http://marmilaperla.it/img/');
    define('_FILEUPLADPATH_', '/Users/lorenzobottini/framework-base-php-elle');
    define('_FILEEXPORTPATH_', '/Users/lorenzobottini/framework-base-php-elle/export');
    
    define('_ENABLEWAREHOUSE_', false);

    define('_SMSMITTENTE_', '+39000000000');
    define('_SEVERNAMEC_', 'marmilaperla.it');
    
    //Storage files
    define('_FREETURE_', '/Users/lorenzobottini/Desktop/fold/configuration.cfg');
    define('_PASSWD_', '/Users/lorenzobottini/Desktop/passwd.txt');
    
    //SSH access for Docker
    define('_DOCKER_IP_', 'host.docker.internal');
    define('_DOCKER_PORT_', '2222');
    define('_DOCKER_SSH_PRI_', '/keys/chiave');
    define('_DOCKER_SSH_PUB_', '/keys/chiave.pub');
}