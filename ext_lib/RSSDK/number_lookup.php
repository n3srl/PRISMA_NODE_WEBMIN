<?php
require_once('util.php');
require_once('config.php');
require_once('smstype.php');
require_once('http_post.php');

function sdk_lookup_number($number) {
	$post = new Sdk_POST();
	$rp = $post->do_post(SDK_LOOKUP_REQUEST);
	$rp->add_param('num',$number);
	$res = $rp->get_result_array();
	if ($rp->isok) {
		$order_id = $res->next_string();
		$valid = $res->next_string();
		if ('valid' == $valid) {
			$res['lookup'] = new Sdk_LOOKUP_RESULT($order_id, $rp->next_string(), $rp->next_string(), $rp->next_string(), $rp->next_string());
		} else {
			$res['lookup'] = new Sdk_LOOKUP_RESULT($order_id);
		}
	}
	return $res;
}

class Sdk_LOOKUP_RESULT {
	var $valid;
	var $order_id;
	var $phone_num;
	var $nation;
	var $nationName;
	var $operator;
	
	function Sdk_LOOKUP_RESULT($order_id) {
		$this->order_id = $order_id;
		$this->valid = false;
	}
	function Sdk_LOOKUP_RESULT($order_id,$phone_num,$nation,$nationName,$operator) {
		$this->valid = true;
		$this->order_id = $order_id;
		$this->phone_num = $phone_num;
		$this->nation = $nation;
		$this->nationName = $nationName;
		$this->operator = $operator;
	}

}

?>
