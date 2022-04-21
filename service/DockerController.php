<?php
/**
 * Class for DockerController
 * 
 * @author: N3 S.r.l.
 */
class DockerController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck(1);
		global $Docker;
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
