<?php
/**
*
* @author: N3 S.r.l.
*/

class DetectionLogic
{
	public static function Save($obj) {
			$Person = CoreLogic::VerifyPerson();
			N3BusinessObject::Init($obj, $Person);
			$res = DetectionFactory::Save($obj);
			return $res;
	}
        

	public static function Update($obj){

		$Person = CoreLogic::VerifyPerson();
		N3BusinessObject::SetModified($obj, $Person);

		return DetectionFactory::Update($obj);
	}

	public static function Erase($obj) {
			$Person = CoreLogic::VerifyPerson();
			return DetectionFactory::Erase($obj);
	}

	public static function Delete($obj) {
			$Person = CoreLogic::VerifyPerson();
			return DetectionFactory::Delete($obj);
	}

	public static function Get($id) {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			return DetectionFactory::Get($id);
	}

	public static function GetList() {
			$Person = CoreLogic::VerifyPerson();
			$ob = DetectionFactory::GetList();
			return $ob;
}
}

