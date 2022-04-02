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

$app->GET('/login', function(Application $app, Request $request) {
    /* $username = $request->get('username');
      $password = $request->get('password');
     */

    $result = CoreLogic::Login($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

$app->GET('/logout', function(Application $app, Request $request) {

    $result = CoreLogic::Logout();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

$app->GET('/menu', function(Application $app, Request $request) {

    $result = CoreLogic::Menu();

    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

$app->GET('/getcurrentuser', function(Application $app, Request $request) {
    $result = CoreApiLogic::GetCurrentUser();
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

/* * **********************************************************

  GROUP

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/group', function(Application $app, Request $request) {

    $result = GroupLogic::Save($request->request);
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
$app->PUT('/group', function(Application $app, Request $request) {

    $result = GroupLogic::Update($request->request);
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
$app->DELETE('/group', function(Application $app, Request $request) {

    $result = GroupLogic::Delete($request->request);
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
$app->PATCH('/group', function(Application $app, Request $request) {

    $result = GroupLogic::Erase($request->request);
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
$app->GET('/group/{groupId}', function(Application $app, Request $request, $groupId) {

    $result = GroupLogic::Get($groupId);
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
$app->GET('/group', function(Application $app, Request $request) {

    $result = GroupLogic::GetList();
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
$app->GET('/group/datatable/list', function(Application $app, Request $request) {

    $result = GroupLogic::GetListDatatable();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/group/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GroupLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/group/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GroupLogic::GetListFKAjax($companyColumn);
    if ($result->results) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/* * **********************************************************

  GROUPHASPERSON

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/grouphasperson', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::Save($request->request);
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
$app->PUT('/grouphasperson', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::Update($request->request);
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
$app->DELETE('/grouphasperson', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::Delete($request->request);
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
$app->PATCH('/grouphasperson', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::Erase($request->request);
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
$app->GET('/grouphasperson/{grouphaspersonId}', function(Application $app, Request $request, $grouphaspersonId) {

    $result = GroupHasPersonLogic::Get($grouphaspersonId);
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
$app->GET('/grouphasperson', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::GetList();
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
$app->GET('/grouphasperson/datatable/list', function(Application $app, Request $request) {

    $result = GroupHasPersonLogic::GetListDatatable();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/grouphasperson/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GroupHasPersonLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/grouphasperson/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GroupHasPersonLogic::GetListFKAjax($companyColumn);
    if ($result->results) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/* * **********************************************************

  GUI

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/gui', function(Application $app, Request $request) {

    $result = GuiLogic::Save($request->request);
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
$app->PUT('/gui', function(Application $app, Request $request) {

    $result = GuiLogic::Update($request->request);
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
$app->DELETE('/gui', function(Application $app, Request $request) {

    $result = GuiLogic::Delete($request->request);
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
$app->PATCH('/gui', function(Application $app, Request $request) {

    $result = GuiLogic::Erase($request->request);
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
$app->GET('/gui/{guiId}', function(Application $app, Request $request, $guiId) {

    $result = GuiLogic::Get($guiId);
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
$app->GET('/gui', function(Application $app, Request $request) {

    $result = GuiLogic::GetList();
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
$app->GET('/gui/datatable/list', function(Application $app, Request $request) {

    $result = GuiLogic::GetListDatatable();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/gui/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GuiLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/gui/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = GuiLogic::GetListFKAjax($companyColumn);
    if ($result->results) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(403);
    }
    return $resp;
});

/* * **********************************************************

  PERMISSION

 * ********************************************************** */

$app->GET('/permission', function(Application $app, Request $request) {

    $result = CoreLogic::Permission($request);

    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

$app->GET('/csfr', function(Application $app, Request $request) {

    $result = CoreLogic::GenerateCSRF();

    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

/* * **********************************************************

  PERSON

 * ********************************************************** */

/**
 *
 * INSERT
 *
 * */
$app->POST('/person', function(Application $app, Request $request) {

    $result = PersonApiLogic::Save($request->request);
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
$app->PUT('/person', function(Application $app, Request $request) {

    $result = PersonApiLogic::UpdatePassword($request->request);
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
$app->DELETE('/person', function(Application $app, Request $request) {

    $result = PersonApiLogic::Delete($request->request);
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
$app->PATCH('/person', function(Application $app, Request $request) {

    $result = PersonApiLogic::Erase($request->request);
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
$app->GET('/person/{personId}', function(Application $app, Request $request, $personId) {

    $result = PersonApiLogic::GetFromFile($personId);
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
$app->GET('/person', function(Application $app, Request $request) {

    $result = PersonApiLogic::GetList();
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
$app->GET('/person/datatable/list', function(Application $app, Request $request) {

    $result = PersonApiLogic::GetListDatatableFromFile();
    $resp = new Response(json_encode($result));
    $resp->setStatusCode(200);
    return $resp;
});

/**
 *
 * GET AUTOCOMPLETE
 *
 * */
$app->GET('/person/autocomplete/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = PersonApiLogic::GetListFilterAjax($companyColumn);
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
$app->GET('/person/foreignkey/{companyColumn}', function(Application $app, Request $request, $companyColumn) {

    $result = PersonApiLogic::GetListFKAjax($companyColumn);
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
 * EDIT PASSWORD
 *
 * */
$app->POST('/person/password', function(Application $app, Request $request) {

    $result = PersonApiLogic::UpdatePassword($request);
    if ($result->result) {
        $resp = new Response(json_encode($result));
        $resp->setStatusCode(200);
    } else {
        $resp = new Response();
        $resp->setStatusCode(401);
    }

    return $resp;
});

$app->run();

