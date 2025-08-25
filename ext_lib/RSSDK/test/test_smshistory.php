<?php

require('../sms_history.php');

$history = sdk_get_sms_history(time()-(7*24*60*60),time());

if ($history['ok']) {
	for ($i=0;$i<$history['count'];$i++) {
		echo 'SMS id '.$history[$i]->order_id.' sent from '.$history[$i]->sender;
		echo ' to '.$history[$i]->recipients_count.' recipients<br/>';
	}
} else {
	echo 'Request failed: '.$history['errcode'].' - '.$history['errmsg'];
}

?>
