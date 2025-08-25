<?php

define('SDK_CREDITS_REQUEST','/Aruba/CREDITS');
define('SDK_SEND_SMS_REQUEST','/Aruba/SENDSMS');
define('SDK_REMOVE_DELAYED_REQUEST','/Aruba/REMOVE_DELAYED');
define('SDK_MSG_STATUS_REQUEST','/Aruba/SMSSTATUS');
define('SDK_HISTORY_REQUEST','/Aruba/SMSHISTORY');
define('SDK_LOOKUP_REQUEST','/OENL/NUMBERLOOKUP');
define('SDK_NEW_SMS_MO_REQUEST','/OESRs/SRNEWMESSAGES');
define('SDK_MO_HIST_REQUEST','/OESRs/SRHISTORY');
define('SDK_MO_BYID_REQUEST','/OESRs/SRHISTORYBYID');
define('SDK_SUBACCOUNTS_REQUEST','/Aruba/SUBACCOUNTS');

define('SDK_DATE_TIME_FORMAT','%Y%m%d%H%M%S');

class Sdk_POST {
	var $params;
	var $result;

	function Sdk_POST() {
		$this->params['login'] = SDK_USERNAME;
		$this->params['password'] = SDK_PASSWORD;
	}

	function add_param($name, $value) {
		$this->params[$name] = $value;
	}

	function do_post($request) {
		$request_url = 'http://'.SDK_HOSTNAME;
		if (SDK_DEFAULT_PORT != 80) {
			$request_url = $request_url.':'.SDK_DEFAULT_PORT;
		}
		$request_url = $request_url.$request;
		$postdata = http_build_query($this->params);
		if (SDK_PROXY == '') {
			$opts = array('http' =>
	    		array(
	        		'method'  => 'POST',
			        'header'  => 'Content-type: application/x-www-form-urlencoded',
			        'content' => $postdata
			    )
			);
		}
		else
		{
			$opts = array('http' =>
	    		array(
	        		'method'  => 'POST',
			        'header'  => 'Content-type: application/x-www-form-urlencoded',
			        'content' => $postdata,
	    			'proxy' => SDK_PROXY.':'.SDK_PROXY_PORT,
	    			'request_fulluri' => true
			    )
			);

		}
		$context  = stream_context_create($opts);
		$this->result = file_get_contents($request_url, false, $context);
		list($version,$status_code,$msg) = explode(' ',$http_response_header[0], 3);
		switch($status_code) {
			case 200: return new Sdk_response_parser($this->result);
			// maybe we could implement better error handling?
			default: return null;
		}
	}

}

define('SDK_SEPARATOR','|');
define('SDK_NEWLINE',';');
class Sdk_response_parser {
	var $cursor;
	var $response;
	var $isok;
	var $errcode;
	var $errmsg;

	function Sdk_response_parser($response) {
		$this->response = $response;
		$this->cursor = 0;
		if (strlen($response) >= 2) {
			$code = $this->next_string();
			if ('OK' == $code) {
				$this->isok = true;
			}
			if ('KO' == $code) {
				$this->isok = false;
				$this->errcode = $this->next_int();
				$this->errmsg = $this->next_string();
			}
		}
	}

	function next_string() {
		$nstr = '';
//		echo 'cursor:|'.$this->cursor.'|';
//		echo 'nstr:|'.$nstr.'|';
		while (($this->response[$this->cursor] != SDK_SEPARATOR) &&
			($this->response[$this->cursor] != SDK_NEWLINE)) {
//		echo 'Cnstr:|'.$nstr.'|';
			$nstr = $nstr.$this->response[$this->cursor++];
			if ($this->cursor >= strlen($this->response))
				break;
		}
//		echo 'Enstr:|'.$nstr.'|';
		if ($this->cursor < strlen($this->response) && $this->response[$this->cursor] != SDK_NEWLINE) {
			$this->cursor++;
		}
		return urldecode($nstr);
	}
	function next_int() {
		return (int)$this->next_string();
	}
	function next_long() {
		return (float)$this->next_string();
	}

	function go_next_line() {
		while ($this->response[$this->cursor++] != SDK_NEWLINE) {
			if ($this->cursor > strlen($this->response)) {
				return false;
			}
		}
		return strlen($this->response) != $this->cursor;
	}

	function get_result_array() {
		return array('ok' => $this->isok, 'errcode' => $this->errcode, 'errmsg' => $this->errmsg);
	}
}

?>
