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

MANUTENZIONE - MIGRAZIONE STAZIONE SORGENTE -> CONFIGURAZIONE ATTUALE

************************************************************/

/**
*
* SOURCES: elenco cartelle stazione candidate sorgenti + config destinazione
*
**/
$app->GET('/manutenzione/migration/sources', function(Application $app, Request $request) {

	$result = ManutenzioneApiLogic::ListSources();
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
* SCAN: lista folder/file della sorgente + anteprima rinomino
* Parametri (query): srcCode, srcName (default DEFAULT/DEFAULT).
*
**/
$app->GET('/manutenzione/migration/scan', function(Application $app, Request $request) {

	$result = ManutenzioneApiLogic::ScanDefaults($request->query);
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
* Parametri (body): token, srcCode, srcName.
*
**/
$app->POST('/manutenzione/migration/run', function(Application $app, Request $request) {

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

/**
*
* PROGRESS: stato di avanzamento della RUN (polling client durante l'esecuzione)
*
**/
$app->GET('/manutenzione/migration/progress', function(Application $app, Request $request) {

	$result = ManutenzioneApiLogic::GetProgress();
	$resp = new Response(json_encode($result));
	$resp->setStatusCode($result->result ? 200 : 403);
	return $resp;
});

/************************************************************

MANUTENZIONE - RIALLINEAMENTO HEADER FITS A configuration.cfg

************************************************************/

/**
*
* SCAN: anteprima aggregata delle keyword da aggiornare
* Parametri (query): srcCode (default DEFAULT).
*
**/
$app->GET('/manutenzione/fits/scan', function(Application $app, Request $request) {

	$result = FitsHeaderApiLogic::Scan($request->query);
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
* RUN: applica i valori di configuration.cfg agli header FITS
* Parametri (body): token, srcCode.
*
**/
$app->POST('/manutenzione/fits/run', function(Application $app, Request $request) {

	$result = FitsHeaderApiLogic::Run($request->request);
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
