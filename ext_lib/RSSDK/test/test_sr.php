<?php

require('../sms_mo.php');

echo 'History by time (last year):</br>';
$history = sdk_get_sms_mo_history(time()-(365*24*60*60),time());
if ($history['ok']) {
	for ($i=0;$i<$history['count'];$i++) {
		echo 'SMS id '.$history[$i]->id_message.' sent from '.$history[$i]->sms_sender;
		echo ' to '.$history[$i]->sms_recipient.': '.$history[$i]->message.' <br/>';
	}
} else {
	echo 'Request failed: '.$history['errcode'].' - '.$history['errmsg'];
}

echo 'History by id (from 850350):</br>';
$historyid = sdk_get_sms_mo_byid(850350);
if ($historyid['ok']) {
	for ($i=0;$i<$historyid['count'];$i++) {
		echo 'SMS id '.$historyid[$i]->id_message.' sent from '.$historyid[$i]->sms_sender;
		echo ' to '.$historyid[$i]->sms_recipient.': '.$historyid[$i]->message.' <br/>';
	}
} else {
	echo 'Request failed: '.$historyid['errcode'].' - '.$historyid['errmsg'];
}
?>
