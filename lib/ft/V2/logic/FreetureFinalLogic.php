<?php
/**
*
* @author: N3 S.r.l.
*/

class FreetureFinalLogic
{
	public static function Save($obj) {
			$Person = CoreLogic::VerifyPerson();
			N3BusinessObject::Init($obj, $Person);
			$res = FreetureFinalFactory::Save($obj);
			return $res;
	}
        
        
	public static function Update($obj){

		$Person = CoreLogic::VerifyPerson();
		N3BusinessObject::SetModified($obj, $Person);

		return FreetureFinalFactory::Update($obj);
	}

	public static function Erase($obj) {
			$Person = CoreLogic::VerifyPerson();
			return FreetureFinalFactory::Erase($obj);
	}

	public static function Delete($obj) {
			$Person = CoreLogic::VerifyPerson();
			return FreetureFinalFactory::Delete($obj);
	}

	public static function Get($id) {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			return FreetureFinalFactory::Get($id);
	}

	public static function GetList() {
			$Person = CoreLogic::VerifyPerson();
			$ob = FreetureFinalFactory::GetList();
			return $ob;
}
}

