<?php

require_once('util.php');
require_once('config.php');
require_once('smstype.php');
require_once('http_post.php');
require_once('nations.php');


define('SUBACCOUNT_TYPE_COMPANY','COMPANY');
define('SUBACCOUNT_TYPE_PRIVATE','PRIVATE');

function sdk_get_subaccounts() {
	$post = new Sdk_POST();
	$post->add_param('op','LIST_SUBACCOUNTS');
	$rp = $post->do_post(SDK_SUBACCOUNTS_REQUEST);
	$res = $rp->get_result_array();
	$count = 0;
	if ($rp->isok) {
		while ($rp->go_next_line()) {
			$subaccount = new Sdk_SUBACCOUNT();
			$subaccount->login = $rp->next_string();
			$subaccount->active = $rp->next_int();
			$subaccount->credit_mode = $rp->next_string();
			$subaccount->subaccount_type = $rp->next_string();
			$subaccount->company_name = $rp->next_string();
			$subaccount->fiscal_code = $rp->next_string();
			$subaccount->vat_number = $rp->next_string();
			$subaccount->name = $rp->next_string();
			$subaccount->surname = $rp->next_string();
			$subaccount->email = $rp->next_string();
			$subaccount->address = $rp->next_string();
			$subaccount->city = $rp->next_string();
			$subaccount->province = $rp->next_string();
			$subaccount->zip = $rp->next_string();
			$subaccount->mobile = $rp->next_string();
			$res[] = $subaccount;
			$count++;
		}
	}
	$res['count'] = $count;
	return $res;
}

class Sdk_SUBACCOUNT {

	var $credit_mode;
	var $company_name;
	var $fiscal_code;
	var $vat_number;
	var $name;
	var $surname;
	var $email;
	var $address;
	var $city;
	var $province;
	var $zip;
	var $mobile;
	var $login;
	var $password;
	var $active;
	var $subaccount_type;
	var $sub_password;
	
	function createSubaccount() {
		$post = new Sdk_POST();
		$post->add_param('op','CREATE_SUBACCOUNT');
		$post->add_param('credit_mode',$this->credit_mode);
	
		if (strlen($this->company_name) > 0)
			$post->add_param('company_name',$this->company_name);
		$post->add_param('fiscal_code',$this->fiscal_code);
		$post->add_param('vat_number',$this->vat_number);
		$post->add_param('name',$this->name);
		$post->add_param('surname',$this->surname);
		$post->add_param('email',$this->email);
		$post->add_param('address',$this->address);
		$post->add_param('city',$this->city);
		$post->add_param('province',$this->province);
		$post->add_param('zip',$this->zip);
		$post->add_param('mobile',$this->mobile);
		if (strlen($this->login) > 0)
			$post->add_param('sub_login',$this->sub_login);
		if (strlen($this->sub_password) > 0)
			$post->add_param('sub_password',$this->sub_password);
		
		$rp = $post->do_post(SDK_SUBACCOUNTS_REQUEST);
		$res = $rp->get_result_array();
		if ($rp->isok) {
			$this->login = $rp->next_string();
			$this->password = $rp->next_string();
		}
	}
	
	function lockSubaccount() {
		$post = new Sdk_POST();
		$post->add_param('op','LOCK_SUBACCOUNT');
		$post->add_param('subaccount',$this->login);
		$post->do_post(SDK_SUBACCOUNTS_REQUEST);
		$this->active = false;
	}
	function unlockSubaccount() {
		$post = new Sdk_POST();
		$post->add_param('op','UNLOCK_SUBACCOUNT');
		$post->add_param('subaccount',$this->login);
		$post->do_post(SDK_SUBACCOUNTS_REQUEST);
		$this->active = true;
	}

	function Sdk_SUBACCOUNT() {
		$this->subaccount_type = SUBACCOUNT_TYPE_COMPANY;
	}
}
?>
