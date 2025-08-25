<?php
/**
*
* @author: N3 S.r.l.
*/

class StackLogic
{
	public static function Save($obj) {
			$Person = CoreLogic::VerifyPerson();
			N3BusinessObject::Init($obj, $Person);
			$res = StackFactory::Save($obj);
			return $res;
	}
        

	public static function Update($obj){

		$Person = CoreLogic::VerifyPerson();
		N3BusinessObject::SetModified($obj, $Person);

		return StackFactory::Update($obj);
	}

	public static function Erase($obj) {
			$Person = CoreLogic::VerifyPerson();
			return StackFactory::Erase($obj);
	}

	public static function Delete($obj) {
			$Person = CoreLogic::VerifyPerson();
			return StackFactory::Delete($obj);
	}

	public static function Get($id) {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			return StackFactory::Get($id);
	}

	public static function GetList() {
			$Person = CoreLogic::VerifyPerson();
			$ob = StackFactory::GetList();
			return $ob;
}
}

