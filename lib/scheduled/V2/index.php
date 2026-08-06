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

  SCHEDULED

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/scheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::Save($request->request);
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
$app->PUT('/scheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::Update($request->request);
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
$app->DELETE('/scheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::Delete($request->request);
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
$app->PATCH('/scheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::Erase($request->request);
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
 * GET DATATABLE FILES
 *
 * */
$app->GET('/scheduled/datatable/filelist', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::GetFilesListDatatable($request);
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
$app->GET('/scheduled/datatable/daylist', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::GetDaysListDatatable($request);
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
$app->GET('/scheduled/autocomplete/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = ScheduledApiLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/scheduled/foreignkey/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = ScheduledApiLogic::GetListFKAjax($companyColumn);
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
$app->GET('/scheduled/preview/lastscheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::GetLastScheduled();
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
 * GET DOWNLOAD
 *
 * */
$app->GET('/scheduled/download/{fileName}', function (Application $app, Request $request, $fileName) {

    $result = ScheduledApiLogic::GetFitFile($fileName);
    $resp = new BinaryFileResponse($result);
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET SCHEDULE CONFIGURATION (stato + entries ACQ_SCHEDULE)
 *
 * NB: questa route DEVE stare prima di /scheduled/{scheduledId}, altrimenti il
 *     placeholder cattura "schedule" come id.
 *
 * */
$app->GET('/scheduled/schedule', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::GetSchedule();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode($result->result ? 200 : 403);
    return $resp;
});

/**
 *
 * SAVE SCHEDULE CONFIGURATION
 *
 * */
$app->POST('/scheduled/schedule', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::SaveSchedule($request->request);
    $resp = new Response(json_encode($result));
    $resp->setStatusCode($result->result ? 200 : 403);
    return $resp;
});

/**
 *
 * GET
 *
 * */
$app->GET('/scheduled/{scheduledId}', function (Application $app, Request $request, $scheduledId) {

    $result = ScheduledApiLogic::Get($scheduledId);
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
$app->GET('/scheduled', function (Application $app, Request $request) {

    $result = ScheduledApiLogic::GetList();
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
