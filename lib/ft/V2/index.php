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

/************************************************************

FREETUREFINAL

************************************************************/

/**
*
* SAVE FILE
*
**/

$app->POST('/freeturefinal/editconfiguration', function(Application $app, Request $request) {

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
* INSERT
*
**/

$app->POST('/freeturefinal', function(Application $app, Request $request) {

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

/**
*
* UPDATE
*
**/

$app->PUT('/freeturefinal', function(Application $app, Request $request) {

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
**/

$app->DELETE('/freeturefinal', function(Application $app, Request $request) {

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
**/

$app->PATCH('/freeturefinal', function(Application $app, Request $request) {

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
**/

$app->GET('/freeturefinal/{freeturefinalId}', function(Application $app, Request $request, $freeturefinalId) {

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
* GET LIST
*
**/

$app->GET('/freeturefinal', function(Application $app, Request $request) {

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
**/

$app->GET('/freeturefinal/datatable/list', function(Application $app, Request $request) {

	$result = FreetureFinalApiLogic::GetListDatatable();
		$resp = new Response(json_encode($result));
		$resp->setStatusCode(200);
	return $resp;
});

/**
*
* GET AUTOCOMPLETE
*
**/

$app->GET('/freeturefinal/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

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
**/

$app->GET('/freeturefinal/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

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

$app->run();

