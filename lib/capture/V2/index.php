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

/************************************************************

CAPTURE

************************************************************/

/**
*
* INSERT
*
**/

$app->POST('/capture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::Save($request->request);
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
**/

$app->PUT('/capture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::Update($request->request);
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
**/

$app->DELETE('/capture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::Delete($request->request);
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
**/

$app->PATCH('/capture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::Erase($request->request);
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
**/

$app->GET('/capture/{captureId}', function(Application $app, Request $request, $captureId) {

	$result = CaptureApiLogic::Get($captureId);
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
**/

$app->GET('/capture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::GetList();
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
**/

$app->GET('/capture/datatable/list', function (Application $app, Request $request) {

    $result = CaptureApiLogic::GetListDatatable($request);
    $encode = json_encode($result);
    $resp = new Response($encode);
    $resp->setStatusCode(200);
    return $resp;
});

/**
*
* GET AUTOCOMPLETE
*
**/

$app->GET('/capture/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

	$result = CaptureApiLogic::GetListFilterAjax($companyColumn);
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
**/

$app->GET('/capture/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

	$result = CaptureApiLogic::GetListFKAjax($companyColumn);
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
**/

$app->GET('/capture/preview/{fileName}', function(Application $app, Request $request, $fileName) {
        
	$result = CaptureApiLogic::GetPngFile($fileName);
	$resp = new BinaryFileResponse($result);
        $resp->headers->set('Content-Type', 'image/png');
        $resp->setStatusCode(200);
	return $resp;
});

/**
*
* GET DOWNLOAD
*
**/

$app->GET('/capture/download/{fileName}', function(Application $app, Request $request, $fileName) {

	$result = CaptureApiLogic::GetFitFile($fileName);
	$resp = new BinaryFileResponse($result);
        $resp->setStatusCode(200);
	return $resp;
});

/**
*
* GET LAST CAPTURE INFO
*
**/

$app->GET('/capture/info/lastcapture', function(Application $app, Request $request) {

	$result = CaptureApiLogic::GetLastCaptureInfo();
	$resp = new Response($result);
        $resp->setStatusCode(200);
	return $resp;
});

$app->run();

