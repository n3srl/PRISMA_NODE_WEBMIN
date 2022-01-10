<?php
/**
 * Class for FreetureFinalController
 * 
 * @author: N3 S.r.l.
 */
class FreetureFinalController extends Controller
{
	public function editOperation() {
		parent::securityCheck();
		global $FreetureFinal;
		global $params;
		$par = 0;
		if (isset($params[0]) && !empty($params[0])) {
			$par = $params[0];
		}
		echo '<script>var ObjID = ' . $par. ';</script>';
	}


}
