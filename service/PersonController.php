<?php
/**
 * Class for PersonController
 * 
 * @author: N3 S.r.l.
 */
class PersonController extends Controller
{
	public function editOperation() {
		$permission = parent::securityCheck(1);
		global $Person;
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
