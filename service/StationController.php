<?php
/**
 * Class for StationController
 * 
 * @author: N3 S.r.l.
 */
class StationController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck();
		global $Station;
		global $params;
		$par = 0;
		if (isset($params[0]) && !empty($params[0])) {
			$par = $params[0];
		}
		echo '<script>var ObjID = ' . $par. ';</script>';
	}
        
}
