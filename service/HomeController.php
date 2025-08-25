<?php
//sposta tutto in logic sotto file panelerrorlogic(?), 
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultController
 *
 * @author Alessandro
 */
class HomeController extends Controller {

    function HomeController($param) {
        return;        
    }

    public function HomeOperation($param) {
    $permission = parent::securityCheck();
    global $params;
    $par = 0;
    global $verrors; //vettore contenente gli errori
    global $vstatus; //vettore contenente informazioni sullo stato

    if (isset($params[0]) && !empty($params[0])) {
        $par = $params[0];
    }
            if(!$permission){
                @include "./view/user/login.php";
                exit;
            }
    echo '<script>var ObjID = ' . $par. ';</script>';
    

    if (!HomeLogic::checkMask()) {
        $verrors[] = _('Maschera non presente');
    }else{
        $vstatus[] = _('Maschera presente');
    }
   
    if (!HomeLogic::checkInternet()) {
       $verrors[] = _('Mancata connessione a Internet.');
    }else{
        $vstatus[] = _('Connessione ad internet stabilita');
    }

    if (!HomeLogic::checkConfig()) {
        $verrors[] = _('File di configurazione non presente');
    }else{
        $vstatus[] = _('File di configurazione presente');
    }
}
}







