<?php

require_once('util.php');
require_once('config.php');
require_once('smstype.php');
require_once('http_post.php');

function sdk_removeScheduledSend($order_id) {
	$post = new Sdk_POST();
	$post->add_param('order_id',$order_id);
	$post->do_post(SDK_REMOVE_DELAYED_REQUEST);
	return true;
}
class Sdk_SMS {
	var $order_id;
	var $sms_type;
	var $message;
	var $recipients;
	var $sender;
	var $scheduled_delivery;

	var $problem;

	function Sdk_SMS() {
		$this->sms_type = SMSTYPE_ALTA;
		$this->scheduled_delivery = null;
		$this->order_id = null;
	}

	function validate() {
		$this->problem = null;
		if ($this->sms_type == null) {
			$this->problem = 'SMS type cannot be null';
			return false;
		}
		if (strlen($this->message) == 0) {
			$this->problem = 'SMS text cannot be empty';
			return false;
		}
		if (count($this->recipients) == 0) {
			$this->problem = 'empty recipients list';
			return false;
		} else {
			foreach ($this->recipients as $recipient) {
				if (!sdk_is_valid_international($recipient)) {
					$this->problem = 'invalid recipient: '.$recipient;
					return false;
				}
			}
		}
		if (!sdk_sms_type_valid($this->sms_type)) {
			$this->problem = 'invalid SMS type: '.$this->sms_type;
			return false;
		}
		if (sdk_sms_type_has_custom_tpoa($this->sms_type)) {
			if (!sdk_is_valid_tpoa($this->sender)) {
				$this->problem = 'invalid sender: '.$this->sender ;
				return false;
			}
		}
		return true;
	}

	function problem() {
		return $this->problem;
	}

	function length() {
		$count = 0;
		$rawlen = strlen($this->message);
		for ($i=0;$i<$rawlen;$i++) {
			switch ($this->message[$i]) {
				case '|':
				case '^':
				case '€':
				case '}':
				case '{':
				case '[':
				case '~':
				case ']':
				case '\\':
					$count = $count + 2;
					break;
				default: $count++;
			}
		}
		return $count;
	}

	function count_smss() {
		$length = $this->length();
		return $length <= 160 ? 1 : (int)(($length-1)/153)+1;
	}
	function count_recipients() {
		return count($this->recipients);
	}

	function add_recipient($recipient) {
		$this->recipients[] = $recipient;
	}

	function set_scheduled_delivery($timestamp) {
		$this->scheduled_delivery = strftime(SDK_DATE_TIME_FORMAT,$timestamp);
	}
	function set_immediate() {
		$this->scheduled_delivery = null;
	}

	function send() {
		if (!$this->validate()) {
			return false;
		}
		$post = new Sdk_POST();
		$post->add_param('message',$this->message);
		$post->add_param('message_type',$this->sms_type);
		if ($this->scheduled_delivery != null) {
			$post->add_param('scheduled_delivery_time',$this->scheduled_delivery);
		}
		if ($this->order_id != null) {
			$post->add_param('order_id',$this->order_id);
		}
		if (sdk_sms_type_has_custom_tpoa($this->sms_type)) {
			$post->add_param('sender',$this->sender);
		}
		$isfirst = true;
		$recipient_list = '';
		foreach ($this->recipients as $recipient) {
			if ($isfirst) {
				$recipient_list = $recipient;
				$isfirst = false;
			} else {
				$recipient_list = $recipient_list.','.$recipient;
			}
		}
		$post->add_param('recipient',$recipient_list);
		$rp = $post->do_post(SDK_SEND_SMS_REQUEST);
		$res = $rp->get_result_array();
		$this->problem = null;
		if (!$res['ok']) {
			$this->problem = $res['errmsg'];
		}
		if ($rp->isok) {
			$res['order_id'] = $rp->next_string();
			$res['sentsmss'] = $rp->next_int();
		}
		return $res;
	}

}


?>
