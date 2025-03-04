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

/* * **********************************************************

  FREETUREFINAL

 * ********************************************************** */

/**
 *
 * EDIT CONFIGURATION FILE
 *
 * */
$app->POST('/freeturefinal/editconfiguration', function (Application $app, Request $request) {
    $result = FreetureFinalApiLogic::EditConfiguration($request->files->get('configuration'));
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
 * EDIT MASK FILE
 *
 * */
$app->POST('/freeturefinal/editmask', function (Application $app, Request $request) {
    
    $result = FreetureFinalApiLogic::EditMask($request);
    
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result), 500, []);
        $resp->setStatusCode(500);
    }
    return $resp;
});

/**
 *
 * INSERT
 *
 * */
$app->POST('/freeturefinal', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::Save($request->request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});


$app->PUT('/freeturefinal/auto', function (Application $app, Request $request) {

    $result = true;
    
    foreach($request->get("data") as $t) {
        $result &= FreetureFinalApiLogic::UpdateAuto($t);
    }

    if ($result) {
        $resp = new Response("OK");
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
$app->PUT('/freeturefinal', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::Update($request->request);
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
$app->DELETE('/freeturefinal', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::Delete($request->request);
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
$app->PATCH('/freeturefinal', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::Erase($request->request);
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
$app->GET('/freeturefinal/{freeturefinalId}', function (Application $app, Request $request, $freeturefinalId) {

    $result = FreetureFinalApiLogic::Get($freeturefinalId);
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
 * GET ID BY KEY
 *
 * */
$app->GET('/freeturefinal/id/{freeturefinalKey}', function (Application $app, Request $request, $freeturefinalKey) {

    $result = FreetureFinalApiLogic::GetIdByKey($freeturefinalKey);
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
$app->GET('/freeturefinal', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetList();
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
$app->GET('/freeturefinal/datatable/list', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetListDatatable();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/freeturefinal/autocomplete/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = FreetureFinalApiLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/freeturefinal/foreignkey/{companyColumn}', function (Application $app, Request $request, $companyColumn) {

    $result = FreetureFinalApiLogic::GetListFKAjax($companyColumn);
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
 * GET MASK PREVIEW
 *
 * */
$app->GET('/freeturefinal/preview/mask', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetMaskFile();
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
 * GET STORAGE INFO
 *
 * */
$app->GET('/freeturefinal/storage/info', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetStorageInfo();
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
 * GET MEDIA STORAGE INFO
 *
 * */
$app->GET('/freeturefinal/media/info', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetMediaStorageInfo();
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
 * UPDATE MEDIA PREVIEW
 *
 * */
$app->POST('/freeturefinal/media/preview', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::UpdateMediaPreview($request);
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
 * GET MEDIA PREVIEW
 *
 * */
$app->GET('/freeturefinal/media/preview', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetMediaPreview();
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
 * UPDATE MEDIA PREVIEW
 *
 * */
$app->POST('/freeturefinal/media/processing', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::UpdateMediaProcessing($request);
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
 * GET MEDIA PREVIEW
 *
 * */
$app->GET('/freeturefinal/media/processing', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetMediaProcessing();
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
 * CLEAN STORAGE INFO
 *
 * */
$app->POST('/freeturefinal/storage/clean', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::CleanMediaStorage();
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
 * GET NUMBER OF CORES
 *
 * */
$app->GET('/freeturefinal/storage/cores', function (Application $app, Request $request) {

    $result = FreetureFinalApiLogic::GetNumberOfCores();
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



