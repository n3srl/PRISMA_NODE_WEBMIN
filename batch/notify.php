<?php

set_time_limit(0);
ini_set('auto_detect_line_endings', TRUE);
require('/var/www/marmilaperla/config/config.php');

$db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
mysqli_select_db($db_conn, $db_name) or die;
mysqli_set_charset($db_conn, "utf8"); 

require(_WEBROOTDIR_.'lib/core/v1/logic/DateLogic.php');
require(_WEBROOTDIR_.'lib/extmodule/v1/model/Appuntamento.php');
require(_WEBROOTDIR_.'lib/extmodule/v1/model/Notifiche.php');
require(_WEBROOTDIR_.'lib/extmodule/v1/model/NotificheFactoryBase.php');
require(_WEBROOTDIR_.'lib/extmodule/v1/model/NotificheFactory.php');


$Notifiche = NotificheFactory::GetListScadenzaNotifiche();

foreach ($Notifiche as $Notifica){
    
    $notificaS = new Notifiche();
    //var_dump($Notifica);
    $stato = $Notifica->stato == Appuntamento::$DAFARE ? "Appuntamento del " : "Telefonata del ";
    $notificaS->titolo = $stato. DateLogic::dateYmdTOdmY($Notifica->data) ." ore ". substr($Notifica->ora,0,5);

    switch($Notifica->scadenza){
        case Notifiche::$GIORNOCORRENTE:
            $scadenza = "<br/>Da effettuare oggi";
            break;
        case Notifiche::$INSCADENZA:
            $scadenza = "<br/>In scadenza tra ". str_replace("-", "", $Notifica->differenza_giorni) . " giorni";
            break;
        case Notifiche::$SCADUTA:
            $scadenza = "<br/>Scaduta da ". str_replace("-", "", $Notifica->differenza_giorni) . " giorni";
            break;
    }
    
    $notificaS->messaggio = $Notifica->name.$scadenza;
    $notificaS->link_riferimento = "/cliente/edit/".$Notifica->name_company_id;
    $notificaS->stato = $Notifica->scadenza;
    $notificaS->letta = 0;
    NotificheFactory::Save($notificaS);
    
}




 