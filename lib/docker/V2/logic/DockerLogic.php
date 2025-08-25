<?php
/**
*
* @author: N3 S.r.l.
*/

class DockerLogic
{
	public static function Save($obj) {
			$Person = CoreLogic::VerifyPerson();
			N3BusinessObject::Init($obj, $Person);
			$res = DockerFactory::Save($obj);
			return $res;
	}

	public static function Update($obj){

		$Person = CoreLogic::VerifyPerson();
		N3BusinessObject::SetModified($obj, $Person);

		return DockerFactory::Update($obj);
	}

	public static function Erase($obj) {
			$Person = CoreLogic::VerifyPerson();
			return DockerFactory::Erase($obj);
	}

	public static function Delete($obj) {
			$Person = CoreLogic::VerifyPerson();
			return DockerFactory::Delete($obj);
	}

	public static function Get($id) {
			$res = false;
			$Person = CoreLogic::VerifyPerson();
			return DockerFactory::Get($id);
	}

	public static function GetList() {
			$Person = CoreLogic::VerifyPerson();
			$ob = DockerFactory::GetList();
			return $ob;
}
}

