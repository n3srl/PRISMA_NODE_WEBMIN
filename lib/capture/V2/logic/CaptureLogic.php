<?php
/**
*
* @author: N3 S.r.l.
*/

class CaptureLogic
{
	public static function Save($obj) {
			$Person = CoreLogic::VerifyPerson();
			N3BusinessObject::Init($obj, $Person);
			$res = CaptureFactory::Save($obj);
			return $res;
	}
        

	public static function Update($obj){

		$Person = CoreLogic::VerifyPerson();
		N3BusinessObject::SetModified($obj, $Person);

		return CaptureFactory::Update($obj);
	}

	public static function Erase($obj) {
			$Person = CoreLogic::VerifyPerson();
			return CaptureFactory::Erase($obj);
	}

	public static function Delete($obj) {
			$Person = CoreLogic::VerifyPerson();
			return CaptureFactory::Delete($obj);
	}

	public static function Get($id) {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			return CaptureFactory::Get($id);
	}

	public static function GetList() {
			$Person = CoreLogic::VerifyPerson();
			$ob = CaptureFactory::GetList();
			return $ob;
}
}

