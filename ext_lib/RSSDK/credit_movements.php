<?php

require_once('util.php');
require_once('config.php');
require_once('smstype.php');
require_once('http_post.php');
require_once('nations.php');

function sdk_get_subaccount_credits($login) {
	$post = new Sdk_POST();
	$post->add_param('op','GET_CREDITS');
	$post->add_param('subaccount',$login);
	$rp = $post->do_post(SDK_SUBACCOUNTS_REQUEST);
	$res = $rp->get_result_array();
	$count = 0;
	if ($rp->isok) {
		while ($rp->go_next_line()) {
			$res[] = new Sdk_CREDIT($rp->next_string(), $rp->next_string(), $rp->next_int());
			$count++;
		}
	}
	$res['count'] = $count;
	return $res;
}
function sdk_get_subaccount_purchases($login) {
	$post = new Sdk_POST();
	$post->add_param('op','GET_PURCHASES');
	$post->add_param('subaccount',$login);
	$rp = $post->do_post(SDK_SUBACCOUNTS_REQUEST);
	$res = $rp->get_result_array();
	$count = 0;
	if ($rp->isok) {
		while ($rp->go_next_line()) {
			$cm = new Sdk_CREDIT_MOVEMENT();
			$cm->subaccount_login = $login;
			$cm->super_to_sub = $rp->next_string();
			$cm->amount = $rp->next_long();
	
			$cm->recording_date = $rp->next_string();
			$cm->id_purchase = $rp->next_long();
			
			$cm->price = $rp->next_long();
			$cm->available_amount = $rp->next_long();
			
			$cm->sms_types = explode(';', $rp->next_string());
			$cm->price_per_message = explode(';', $rp->next_string());
			
			$res[] = $cm;
			$count++;
		}
	}
	$res['count'] = $count;
	return $res;
}

class Sdk_CREDIT_MOVEMENT {

	var $subaccount_login;
	var $super_to_sub;
	var $amount;
	var $sms_type;
	var $sms_types;
	var $price;
	var $price_per_message;
	var $is_donation;
	var $id_purchase;
	var $recording_date;
	var $available_amount;
	
	function moveCredits() {
		$post = new Sdk_POST();
		$post->add_param('op','MOVE_CREDITS');
		$post->add_param('subaccount',$this->subaccount_login);
		$post->add_param('super_to_sub',$this->super_to_sub);
		$post->add_param('amount',$this->amount);
		$post->add_param('message_type',$this->message_type);
		$post->do_post(SDK_SUBACCOUNTS_REQUEST);
	}
	
	function createPurchase() {
		$post = new Sdk_POST();
		$post->add_param('op','CREATE_PURCHASE');
		$post->add_param('subaccount',$this->subaccount_login);
		$post->add_param('message_types', implode(';', $this->sms_types));
		$post->add_param('price_per_messages', implode(';', $this->price_per_message));
		$post->add_param('price',$this->price);

		$post->do_post(SDK_SUBACCOUNTS_REQUEST);
	}
	
	function deletePurchase() {
		$post = new Sdk_POST();
		$post->add_param('op','DELETE_PURCHASE');
		$post->add_param('subaccount',$this->subaccount_login);
		$post->add_param('id_purchase',$this->id_purchase);
		$post->do_post(SDK_SUBACCOUNTS_REQUEST);
		
	}
	function Sdk_CREDIT_MOVEMENT() {
		$this->is_donation = false;
	}
}
?>
