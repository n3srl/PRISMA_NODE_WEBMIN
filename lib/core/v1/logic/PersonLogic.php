<?php

/**
 *
 * @author: N3 S.r.l.
 */
class PersonLogic {

    public static function Save($obj) {
        $Person = CoreLogic::VerifyPerson();
        N3BusinessObject::Init($obj, $Person);
        $res = PersonFactory::Save($obj);
        return $res;
    }

    public static function Update($obj) {
        $Person = CoreLogic::VerifyPerson();
        N3BusinessObject::SetModified($obj, $Person);

        return PersonFactory::Update($obj);
    }

    public static function Erase($obj) {
        $Person = CoreLogic::VerifyPerson();
        return PersonFactory::Erase($obj);
    }

    public static function Delete($obj) {
        $Person = CoreLogic::VerifyPerson();
        return PersonFactory::Delete($obj);
    }

    public static function Get($id) {
        $res = false;
        $Person = CoreLogic::VerifyPerson();
        return PersonFactory::Get($id);
    }

    public static function GetList() {
        $Person = CoreLogic::VerifyPerson();
        $ob = PersonFactory::GetList();
        return $ob;
    }

}
