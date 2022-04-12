<?php
/**
 * Class for FreetureFinalController
 * 
 * @author: N3 S.r.l.
 */
class OvpnController extends Controller
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
                    header("Location: /capture/edit");
                }
		echo '<script>var ObjID = ' . $par. ';</script>';
	}
        
}


