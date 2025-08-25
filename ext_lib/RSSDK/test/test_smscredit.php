<?php

require('../credits.php');

$credits = sdk_get_credits();
print_r($credits);
if ($credits['ok']) {
	for ($i=0;$i<$credits['count'];$i++) {
		if (!$credits[$i]->is_international()) {
			echo 'You can send '.$credits[$i]->availability.' smss';
			echo ' of type '.$credits[$i]->credit_type;
			echo ' in '.$credits[$i]->nation.' </br>';
		} else {
			if ($credits[$i]->credit_type == 'EE') {
				echo 'You can send '.$credits[$i]->availability;
				echo ' smss in foreign countries </br>';
			}
		}
	}
} else {
	echo 'Request failed: '.$credits['errcode'].' - '.$credits['errmsg'];
}


?>
