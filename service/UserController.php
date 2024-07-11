<?php

/**
 * Class for UserFactory
 * 
 * @author: Alessandro Cappelletti 
 */
class UserController extends Controller {

    public function loginOperation() {
        parent::securityCheck(UserLevel::ALL);
        $ob = new User();
    }

    public function logoutOperation() {
        parent::securityCheck();
        UserFactory::logout();
        global $operazione;
        $operazione = "login";
    }

    public function checkOperation() {
        $ob = new User(UserLevel::ALL);
        parent::getFromArray($ob, $_POST);

        $dbUser = UserFactory::GetFromUsername($ob->username);

        $resp = new Response();
        if (md5($ob->password) == $dbUser->password) {
            UserFactory::login($dbUser);
            $resp->redirect = "self";
        } else {
            $resp->success = FALSE;
            $resp->content = "Username o password non validi";
        }


        return $resp;
    }

    public function registerOperation() {
        global $User;
        $User = new User();
    }

    public function listOperation() {
        parent::securityCheck(UserLevel::ADMIN);
        global $Users;
        $Users = UserFactory::GetList();
    }

    public function editOperation() {
        parent::securityCheck(UserLevel::ADMIN);
        global $User;
        global $params;
        $User = UserFactory::Get($params[0]);
        //var_dump($User);
    }

    public function listAjaxOperation() {
        parent::securityCheck(UserLevel::ADMIN);
        global $db_conn;
        include "./view/user/listAjax.php";
    }

    public function saveOperation() {
        parent::securityCheck(UserLevel::ADMIN);
        $ob = new User();
        parent::getFromArray($ob, $_POST);
        if (isset($ob->id) and $ob->id != '') {
            $old = UserFactory::Get($ob->id);
            if ($old->password != $ob->password) {
                $ob->password = md5($ob->password);
            }
            $res = UserFactory::Update($ob);
        } else {
            //Verificare che non esista già un utente con lo stesso nome
            $Us = UserFactory::GetFromUsername($ob->username);

            if($Us){
                $response = $this->generateResponse(TRUE);
                $response->success = FALSE;
                $response->content = _("Il nome utente inserito esiste già")."\n"._("E' necessario modificarlo per poter continuare");
                return $response;
            }
            
            
            $ob->password = md5($ob->password);
            $res = UserFactory::Save($ob);
        }

        return $this->generateResponseRedirect($res, "self");
    }

    public function deleteOperation() {
        parent::securityCheck(UserLevel::ADMIN);
        global $params;
        $res = UserFactory::Delete($params[0]);

        return $this->generateResponseRedirect($res, "self");
    }

}
