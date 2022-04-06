<?php
/**
 * Class for CaptureController
 * 
 * @author: N3 S.r.l.
 */
class CaptureController extends Controller
{
	public function editOperation() {
		parent::securityCheck();
		global $Capture;
		global $params;
		$par = 0;
		if (isset($params[0]) && !empty($params[0])) {
			$par = $params[0];
		}
		echo '<script>var ObjID = ' . $par. ';</script>';
	}


}
