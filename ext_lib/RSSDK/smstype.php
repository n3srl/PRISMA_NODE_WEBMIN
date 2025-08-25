<?php

define('SMSTYPE_ALTA','N');

function sdk_sms_type_valid($smstype) {
	return $smstype === SMSTYPE_ALTA;
}

function sdk_sms_type_has_custom_tpoa($smstype) {
	return $smstype === SMSTYPE_ALTA;
}

?>
