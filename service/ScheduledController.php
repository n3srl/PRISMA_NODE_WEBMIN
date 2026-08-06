<?php
/**
 * Class for ScheduledController
 *
 * @author: N3 S.r.l.
 */
class ScheduledController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck(1);
		global $Scheduled;
		global $params;
		$par = 0;
		if (isset($params[0]) && !empty($params[0])) {
			$par = $params[0];
		}
		if (!$permission) {
			@include "./view/user/login.php";
			exit;
		}
		echo '<script>var ObjID = ' . $par. ';</script>';
	}


}
