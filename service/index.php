<?php

include '../initialize.php';

@$uri = $_SERVER['REDIRECT_URL'];
@list($null, $null4, $class, $operazione, $params ) = explode('/', $uri, 5);
if (!isset($class))
    $class = "Home";
if (!isset($operazione))
    $operazione = "home";
if (!isset($params))
    $params = '';
$params = explode('/', $params);
$id = $params[0];

//$class = ucfirst($class);
//$operazione = lcfirst($operazione);

$class = ucfirst(($class));

//carico i dati della vista tramite controller
$controllerName = $class . "Controller";

$Controller = new $controllerName($params);

$result = new Response();

try {
    $result = $Controller->{$operazione . "Operation"}($params);
    
} catch (FieldException $e) {    
    $result->success = FALSE;
    $result->content = implode("\n", $e->errors);
} catch (SecurityException $e) {    
    $result->success = FALSE;
    $result->content = "Autorizzazione negata";
} catch (Exception $e) {    
    $result->success = FALSE;
    $result->content = "Si è verificato un errore imprevisto.";
}

echo json_encode($result);

?>


