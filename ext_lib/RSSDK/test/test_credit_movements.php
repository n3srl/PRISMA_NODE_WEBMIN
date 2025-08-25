<?php

require('../credit_movements.php');
require('../credits.php');

echo 'Giving credits to subaccount pdvS_004<br>';
$cm1= new Sdk_CREDIT_MOVEMENT();
$cm1->subaccount_login = 'pdvS_004';
$cm1->super_to_sub = 'y';
$cm1->amount=35;
$cm1->message_type='N';
$cm1->moveCredits();

echo 'Listing credits for subaccount pdvS_004<br>';
$credits = sdk_get_subaccount_credits('pdvS_004');
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


echo 'Creating purchase for subaccount pdvS_001<br>';
$cm2= new Sdk_CREDIT_MOVEMENT();
$cm2->subaccount_login = 'pdvS_001';
$cm2->price = '56.7';
$cm2->sms_types = explode(';', 'N;L');
$cm2->price_per_message = explode(';', '0.02;0.009');
$cm2->createPurchase();

echo 'Listing purchases for subaccount pdvS_001<br>';
$purchases = sdk_get_subaccount_purchases('pdvS_001');
if ($purchases['ok']) {
	for ($i=0;$i<$purchases['count'];$i++) {
		
		echo ($purchases[$i]->super_to_sub ? '--> ' : '<-- ');
		echo $purchases[$i]->amount;
		echo '(price '.$purchases[$i]->price.', available '.$purchases[$i]->available_amount.') ppm: ';

		for ($j=0;$j<count($purchases[$i]->sms_types);$j++) {
			echo $purchases[$i]->sms_types[$j].' '.$purchases[$i]->price_per_message[$j].', ';
		}
		echo '<br>';
	}
} else {
	echo 'Request failed: '.$purchases['errcode'].' - '.$purchases['errmsg'];
}
echo 'Deleting last purchase<br>';
$purchases[0]->deletePurchase();

?>
