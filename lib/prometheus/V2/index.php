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

PROMETHEUS

************************************************************/

/**
*
* INSERT
*
**/

$app->POST('/prometheus', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::Save($request->request);
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

$app->PUT('/prometheus', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::Update($request->request);
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

$app->DELETE('/prometheus', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::Delete($request->request);
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

$app->PATCH('/prometheus', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::Erase($request->request);
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

/*$app->GET('/prometheus/{prometheusId}', function(Application $app, Request $request, $prometheusId) {

	$result = PrometheusApiLogic::Get($prometheusId);
	if ($result->result) {
		$resp = new Response(json_encode($result));
		$resp->setStatusCode(200);
	} else {
		$resp = new Response(json_encode($result));
		$resp->setStatusCode(403);
	}
	return $resp;
});
*/

$app->GET('/prometheus/node_exporter', function(Application $app, Request $request, $prometheusId) {

	$result = PrometheusApiLogic::NodeExporter();
        echo $result;
        die;
});


/**
*
* GET LIST
*
**/

$app->GET('/prometheus', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::GetList();
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

$app->GET('/prometheus/datatable/list', function (Application $app, Request $request) {

    $result = PrometheusApiLogic::GetListDatatable();
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

$app->GET('/prometheus/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

	$result = PrometheusApiLogic::GetListFilterAjax($companyColumn);
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

$app->GET('/prometheus/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

	$result = PrometheusApiLogic::GetListFKAjax($companyColumn);
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

/**
*
* EDIT CONFIGURATION FILE
*
**/

$app->POST('/prometheus/editconfiguration', function(Application $app, Request $request) {

	$result = PrometheusApiLogic::EditConfiguration($request->files->get('configuration'));
	if ($result->result) {
		$resp = new Response(json_encode($result));
		$resp->setStatusCode(200);
	} else {
		$resp = new Response(json_encode($result));
		$resp->setStatusCode(403);
	}
	return $resp;
});

