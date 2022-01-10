<?php

set_time_limit(0);
ini_set('auto_detect_line_endings', TRUE);
require('/var/www/marmilaperla/config/config.php');

$db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);
mysqli_select_db($db_conn, $db_name) or die;
mysqli_set_charset($db_conn, "utf8");

require(_WEBROOTDIR_ . 'lib/core/v1/logic/DateLogic.php');
require(_WEBROOTDIR_ . 'lib/extmodule/v1/model/Appuntamento.php');
require(_WEBROOTDIR_ . 'lib/extmodule/v1/model/AppuntamentoFactoryBase.php');
require(_WEBROOTDIR_ . 'lib/extmodule/v1/model/AppuntamentoFactory.php');

require(_WEBROOTDIR_ . 'ext_lib/RSSDK/sendsms.php');


$SmsDaInviare = AppuntamentoFactory::GetListSms();
foreach ($SmsDaInviare as $smsAppuntamento) {

    $sms = new Sdk_SMS();
    $sms->sms_type = SMSTYPE_ALTA;
    $sms->add_recipient($smsAppuntamento->telefono);
    
    $sms->message = 'Promemoria appuntamento del '.$smsAppuntamento->data.' con Cusano Luca di Brokerage System';
    $sms->sender = _SMSMITTENTE_;
    $sms->set_immediate();

    if ($sms->validate()) {
        $res = $sms->send();
        if ($res['ok']) {
            $smsAppuntamento->stato_sms = Appuntamento::$SMSINVIATO;
            //echo $res['sentsmss'] . ' SMS sent, order id is ' . $res['order_id'] . ' </br>';
        } else {
            $smsAppuntamento->stato_sms = Appuntamento::$SMSERRORE;
            //echo 'Error sending SMS: ' . $sms->problem() . ' </br>';
        }
        AppuntamentoFactory::UpdateSms($smsAppuntamento);
    } else {
        $smsAppuntamento->stato_sms = Appuntamento::$SMSERRORE;
        AppuntamentoFactory::UpdateSms($smsAppuntamento);
        //echo 'invalid SMS: ' . $sms->problem() . ' </br>';
    }
}
?>




