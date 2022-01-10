<?php

require('../sms_status.php');

$status = sdk_get_message_status('999FFF111');
if ($status['ok']) {
    for ($i=0;$i<$status['count'];$i++) {
        if ($status[$i]->is_error()) {
            echo 'Error sending sms to '.$status[$i]->recipient.': ';
            echo $status[$i]->str_status();
        } else {
            echo 'Message to number '.$status[$i]->recipient.': ';
            if ($status[$i]->status == 'DLVRD') {
                echo 'correctly delivered on ';
                echo date('l ',$status[$i]->get_sms_received_timestamp());
                echo ' at ';
                echo date('H:i',$status[$i]->get_sms_received_timestamp());
            } else {
                echo $status[$i]->str_status();
            }
        }
        echo '<br/>';
    }
} else {
    echo 'Request failed: '.$status['errcode'].' - '.$status['errmsg'];
}


?>
