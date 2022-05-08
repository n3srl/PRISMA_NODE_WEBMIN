<?php
/**
 * Class for SettingsController
 * 
 * @author: N3 S.r.l.
 */
class SettingsController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck();
		//global $Ovpn;
		global $params;
		$par = 0;
		if (isset($params[0]) && !empty($params[0])) {
			$par = $params[0];
		}
                if(!$permission){
                    @include "./view/user/login.php";
                    exit;
                }
		echo '<script>var ObjID = ' . $par. ';</script>';
	}
        
}
