<?php

$path = "";

$_SERVER['PATH_WEBROOT'] = $path;

include './initialize.php';

//leggo i parametri dell'url
@$uri = $_SERVER['REDIRECT_URL'];
@list($null, $class, $operazione, $params ) = explode('/', $uri, 4);

if (!isset($class))
    $class = "Home";
if (!isset($operazione))
    $operazione = "home";

if (!isset($params))
    $params = '';
$params = explode('/', $params);
$id = $params[0];

include './config/exceptionNameClass.php';


$class = ucfirst(($class));
//carico i dati della vista tramite controller
$controllerName = $class . "Controller";

$Controller = new $controllerName($params);


try {

    $Controller->{$operazione . "Operation"}($params);
    
} catch (SecurityException $ex) {
    $class = "home";
    $operazione = "error";
    $error = _("Autorizzazione negata");
}


//includo la vista
if($operazione != "login"){
    include './view/template/index.php';
}else{
    include './view/user/login.php';
}

?>