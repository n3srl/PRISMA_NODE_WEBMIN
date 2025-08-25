<?php

require_once('util.php');
require_once('config.php');
require_once('smstype.php');
require_once('http_post.php');

function sdk_get_sms_history($fromtime, $totime) {
	return sdk_get_sms_history_fmt(strftime(SDK_DATE_TIME_FORMAT,$fromtime),strftime(SDK_DATE_TIME_FORMAT,$totime));
}

function sdk_get_sms_history_fmt($fromdate, $todate) {
	$post = new Sdk_POST();
	$post->add_param('from',$fromdate);
	$post->add_param('to',$todate);
	$rp = $post->do_post(SDK_HISTORY_REQUEST);
	$res = $rp->get_result_array();
	$count = 0;
	if ($rp->isok) {
		while ($rp->go_next_line()) {
			$res[] = new Sdk_SENT_SMS($rp->next_string(), $rp->next_string(), $rp->next_string(),
				$rp->next_string(), $rp->next_string(), $rp->next_string());
			$count++;
		}
	}
	$res['count'] = $count;
	return $res;
}

class Sdk_SENT_SMS {
	var $order_id;
	var $create_time;
	var $sms_type;
	var $sender;
	var $recipients_count;
	var $scheduled_send;

	function Sdk_SENT_SMS($order_id, $create_time, $sms_type, $sender, $recipients_count, $scheduled_send) {
		$this->order_id = $order_id;
		$this->create_time = $create_time;
		$this->sms_type = $sms_type;
		$this->sender = $sender;
		$this->recipients_count = $recipients_count;
		$this->scheduled_send = $scheduled_send;
	}

	function get_create_time() {
		return sdk_date_to_unix_timestamp($this->create_time);
	}
}

?>
