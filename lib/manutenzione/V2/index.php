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

MANUTENZIONE - MIGRAZIONE DEFAULT -> CONFIGURAZIONE ATTUALE

************************************************************/

/**
*
* SCAN: lista folder/file con valori DEFAULT + anteprima rinomino
*
**/
$app->GET('/manutenzione/migration/default/scan', function(Application $app, Request $request) {

	$result = ManutenzioneApiLogic::ScanDefaults();
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
* RUN: applica il piano di rinomino
*
**/
$app->POST('/manutenzione/migration/default/run', function(Application $app, Request $request) {

	$result = ManutenzioneApiLogic::RunMigration($request->request);
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
