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

  DETECTION

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/detection', function (Application $app, Request $request) {

    $result = DetectionApiLogic::Save($request->request);
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
$app->PUT('/detection', function (Application $app, Request $request) {

    $result = DetectionApiLogic::Update($request->request);
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
$app->DELETE('/detection', function (Application $app, Request $request) {

    $result = DetectionApiLogic::Delete($request->request);
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
$app->PATCH('/detection', function (Application $app, Request $request) {

    $result = DetectionApiLogic::Erase($request->request);
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
$app->GET('/detection/{detectionId}', function (Application $app, Request $request, $detectionId) {

    $result = DetectionApiLogic::Get($detectionId);
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
$app->GET('/detection', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetList();
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
$app->GET('/detection/datatable/list', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetListDatatable($request);
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
$app->GET('/detection/datatable/filelist', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetFilesListDatatable($request);
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
$app->GET('/detection/datatable/daylist', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetDaysListDatatable($request);
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
$app->GET('/detection/autocomplete/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = DetectionApiLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/detection/foreignkey/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = DetectionApiLogic::GetListFKAjax($companyColumn);
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
 * GET ZIP
 *
 * */
$app->GET('/detection/createzip/{detection}', function (Application $app, Request $request, $detection) {

    $result = DetectionApiLogic::CreateZip($detection);
     if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * CREATE ZIP
 *
 * */
$app->GET('/detection/download/{detection}', function (Application $app, Request $request, $detection) {

    $result = DetectionApiLogic::GetZip($detection);
    $resp = new BinaryFileResponse($result);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * CANCEL ZIP
 *
 * */
$app->POST('/detection/zip/cancel', function (Application $app, Request $request) {

    $result = DetectionApiLogic::ResetZip();
     if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET PREVIEW
 *
 * */
$app->GET('/detection/preview/lastdetection', function (Application $app, Request $request) {
    
    $result = DetectionApiLogic::GetLastDetection();
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
 * GET LAST DAY DETECTION NUMBER
 *
 * */
$app->GET('/detection/counter/lastday', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetLastDayDetectionNumber();
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
 * GET LAST MONTH DETECTION NUMBER
 *
 * */
$app->GET('/detection/counter/lastmonth', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetLastMonthDetectionNumber();
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
 * GET ALL DETECTION NUMBER
 *
 * */
$app->GET('/detection/counter/all', function (Application $app, Request $request) {

    $result = DetectionApiLogic::GetAllDetectionNumber();
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

