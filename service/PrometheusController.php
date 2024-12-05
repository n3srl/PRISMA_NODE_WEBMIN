<?php
/**
 * Class for FreetureFinalController
 * 
 * @author: N3 S.r.l.
 */
class PrometheusController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck(2);
		global $FreetureFinal;
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
        
      	public function node_exporterOperation() {
		$permission = parent::securityCheck();
		global $FreetureFinal;
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
