<?php
/**
*
* @author: N3 S.r.l.
*/

$dir = $_SERVER['DOCUMENT_ROOT']. "/lib/network/V2/logic";
if (is_dir($dir)) {
	$files_tmp = scandir($dir);
	$files = array();
	foreach ($files_tmp as $key => $value) {
		if (!in_array($value, array(".", "..")) && !is_dir($dir . "/" . $value)) {
			$files[] = $value;
		}
	}
	foreach ($files as $file) {
		if (strpos($file, 'Logic.php') !== false) {
			include $dir."/".$file;
		}
	}
}

$dir = $_SERVER['DOCUMENT_ROOT']. "/lib/network/V2/logic/api";
if (is_dir($dir)) {
	$files_tmp = scandir($dir);
	foreach ($files_tmp as $value) {
		if (!in_array($value, array(".", "..")) && !is_dir($dir . "/" . $value)) {
			include $dir."/".$value;
		}
	}
}
