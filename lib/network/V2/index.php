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

$respond = function ($result) {
    $resp = new Response(json_encode($result));
    $resp->setStatusCode($result->result ? 200 : 403);
    return $resp;
};

/* ----- WIRED ----- */

$app->GET('/network/wired_status', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::GetWiredNetworkInfo());
});

/* ----- NODE ----- */

$app->GET('/network/node', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::GetNodeConfig());
});

$app->POST('/network/node/preview', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::PreviewNodeConfig($request->request));
});

$app->POST('/network/node/apply', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::ApplyNodeConfig($request->request));
});

/* ----- CAMERA ----- */

$app->GET('/network/camera/list', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::ListCameras());
});

$app->GET('/network/camera/info', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::GetCameraConfig($request->query));
});

$app->POST('/network/camera/preview', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::PreviewCameraConfig($request->request));
});

$app->POST('/network/camera/apply', function (Application $app, Request $request) use ($respond) {
    return $respond(NetworkApiLogic::ApplyCameraConfig($request->request));
});

$app->run();
