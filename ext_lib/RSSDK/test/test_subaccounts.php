<?php

require('../subaccounts.php');

echo 'Listing subaccounts:</br>';
$subs = sdk_get_subaccounts();
if ($subs['ok']) {
	for ($i=0;$i<$subs['count'];$i++) {
		echo 'Subaccount login: '.$subs[$i]->login.' active: '.$subs[$i]->active;
		echo ' type: '.$subs[$i]->credit_mode.' name: '.$subs[$i]->name.' '.$subs[$i]->surname.' <br/>';
	}
} else {
	echo 'Request failed: '.$subs['errcode'].' - '.$subs['errmsg'];
}

echo 'Creating new subaccount:</br>';

$subaccount = new Sdk_SUBACCOUNT();
$subaccount->credit_mode = 2;
$subaccount->company_name = 'azienda prova';
$subaccount->fiscal_code = 'DVGPTR79S13L378X';
$subaccount->vat_number = '012345678901234';
$subaccount->name = 'testnome';
$subaccount->surname = 'testcognome';
$subaccount->email = 'testmail@sdk.it';
$subaccount->address = 'testindirizzo';
$subaccount->city = 'testcittà';
$subaccount->province = 'TN';
$subaccount->zip = '38100';
$subaccount->mobile = '3490000000';
$subaccount->active = true;
$subaccount->createSubaccount();

echo 'Subaccount login: '.$subaccount->login.' password: '.$subaccount->password.' active: '.$subaccount->active.' <br/>';
echo 'Disabling subaccount:</br>';
$subaccount->lockSubaccount();
echo 'Subaccount login: '.$subaccount->login.' password: '.$subaccount->password.' active: '.$subaccount->active.' <br/>';
echo 'Enabling subaccount:</br>';
$subaccount->unlockSubaccount();
echo 'Subaccount login: '.$subaccount->login.' password: '.$subaccount->password.' active: '.$subaccount->active.' <br/>';

?>
