<?php

/**
 *
 * @author: N3 S.r.l.
 */
require_once __DIR__ . '/autoload.php';
require_once _EXTLIB_ . 'sylex/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Silex\Application;

$app = new Silex\Application();

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

/* * **********************************************************

  STACK

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/stack', function (Application $app, Request $request) {

    $result = StackApiLogic::Save($request->request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * UPDATE
 *
 * */
$app->PUT('/stack', function (Application $app, Request $request) {

    $result = StackApiLogic::Update($request->request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * DELETE
 *
 * */
$app->DELETE('/stack', function (Application $app, Request $request) {

    $result = StackApiLogic::Delete($request->request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * ERASED
 *
 * */
$app->PATCH('/stack', function (Application $app, Request $request) {

    $result = StackApiLogic::Erase($request->request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * GET
 *
 * */
$app->GET('/stack/{stackId}', function (Application $app, Request $request, $stackId) {

    $result = StackApiLogic::Get($stackId);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * GET LIST
 *
 * */
$app->GET('/stack', function (Application $app, Request $request) {

    $result = StackApiLogic::GetList();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * GET DATATABLE
 *
 * */
$app->GET('/stack/datatable/list', function (Application $app, Request $request) {

    $result = StackApiLogic::GetListDatatable($request);
    $encode = json_encode($result);
    $resp = new Response($encode);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET DATATABLE FILES
 *
 * */
$app->GET('/stack/datatable/filelist', function (Application $app, Request $request) {

    $result = StackApiLogic::GetFilesListDatatable($request);
    $encode = json_encode($result);
    $resp = new Response($encode);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET DATATABLE DAYS
 *
 * */
$app->GET('/stack/datatable/daylist', function (Application $app, Request $request) {

    $result = StackApiLogic::GetDaysListDatatable($request);
    $encode = json_encode($result);
    $resp = new Response($encode);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/stack/autocomplete/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = StackApiLogic::GetListFilterAjax($companyColumn);
    if ($result->results) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * GET FOREIGN KEY
 *
 * */
$app->GET('/stack/foreignkey/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = StackApiLogic::GetListFKAjax($companyColumn);
    if ($result->results) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/**
 *
 * GET PREVIEW
 *
 * */
$app->GET('/stack/preview/{fileName}', function (Application $app, Request $request, $fileName) {

    $result = StackApiLogic::GetPngFile($fileName);
    $resp = new BinaryFileResponse($result);
    $resp->headers->set('Content-Type', 'image/png');
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET DOWNLOAD
 *
 * */
$app->GET('/stack/download/{fileName}', function (Application $app, Request $request, $fileName) {

    $result = StackApiLogic::GetFitFile($fileName);
    $resp = new BinaryFileResponse($result);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET LAST STACK INFO
 *
 * */
$app->GET('/stack/info/laststack', function (Application $app, Request $request) {

    $result = StackApiLogic::GetLastStackInfo();
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

