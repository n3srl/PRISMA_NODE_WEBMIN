<?php

/**
 *
 * @author: N3 S.r.l.
 */
require_once __DIR__ . '/autoload.php';
require_once _EXTLIB_ . 'sylex/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

$app = new Silex\Application();

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->POST('/camera/list', function (Application $app, Request $request) {

    $result = CameraApiLogic::List($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/reset', function (Application $app, Request $request) {

    $result = CameraApiLogic::Reset($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/freset', function (Application $app, Request $request) {

    $result = CameraApiLogic::FactoryReset($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/features', function (Application $app, Request $request) {

    $result = CameraApiLogic::Features($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/values', function (Application $app, Request $request) {

    $result = CameraApiLogic::Values($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->GET('/camera/hwinfo', function (Application $app, Request $request) {

    $result = CameraApiLogic::HwInfo($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

// Diagnostica rete nodo<->camera: ethtool/sys, ping, ARP. Non tocca la camera.
$app->GET('/camera/diag', function (Application $app, Request $request) {

    $result = CameraApiLogic::NetDiag($request->query);
    $resp = new Response(json_encode($result));
    $resp->setStatusCode($result->result ? 200 : 403);
    return $resp;
});

// Lettura "deep" via arv-tool values: ferma freeture per 3-6 secondi,
// dumpa tutti i parametri GenICam principali, riavvia freeture.
$app->POST('/camera/hwinfo/deep', function (Application $app, Request $request) {

    $result = CameraApiLogic::HwInfoDeep($request->request);
    $resp = new Response(json_encode($result));
    $resp->setStatusCode($result->result ? 200 : 403);
    return $resp;
});

$app->POST('/camera/bounds', function (Application $app, Request $request) {

    $result = CameraApiLogic::Bounds($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/calibration', function (Application $app, Request $request) {

    $result = CameraApiLogic::Calibration($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->GET('/camera/calibration', function (Application $app, Request $request) {

    $result = CameraApiLogic::GetCalibration();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->GET('/camera/cancalibrate', function (Application $app, Request $request) {

    $result = CameraApiLogic::CanCalibrate();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->DELETE('/camera/calibration', function (Application $app, Request $request) {

    $result = CameraApiLogic::DeleteCalibration($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/cmd/{command}', function (Application $app, Request $request, $command) {

    $result = CameraApiLogic::Cmd($command);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

$app->POST('/camera/rebootServer', function(Application $app, Request $request) {

    $result = ManutenzioneLogic::rebootServer();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});


$app->run();


