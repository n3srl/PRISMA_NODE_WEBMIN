<?php

/**
 *
 * @author: N3 S.r.l.
 */
class GroupHasPersonLogic {

    public static function Save($obj) {
        $Person = CoreLogic::VerifyPerson();
        N3BusinessObject::Init($obj, $Person);
        $res = GroupHasPersonFactory::Save($obj);
        return $res;
    }

    public static function Update($obj) {

        $Person = CoreLogic::VerifyPerson();
        N3BusinessObject::SetModified($obj, $Person);

        return GroupHasPersonFactory::Update($obj);
    }

    public static function Erase($obj) {
        $Person = CoreLogic::VerifyPerson();
        return GroupHasPersonFactory::Erase($obj);
    }

    public static function Delete($obj) {
        $Person = CoreLogic::VerifyPerson();
        return GroupHasPersonFactory::Delete($obj);
    }

    public static function SaveGroup($person_id) {

        $Person = CoreLogic::VerifyPerson();

        $ob = new GroupHasPerson();
        N3Object::InitN3Object($ob);
        $ob->person_id = $person_id;
        $ob->group_id = GroupLogic::GetAccountGroup($Person);

        $res = GroupHasPersonFactory::Save($ob);
        return $res;
    }

    public static function VerifyGroup($person_id) {

        $Person = CoreLogic::VerifyPerson();

        $group_id = GroupLogic::GetAccountGroup($Person);
        return !empty(GroupHasPersonFactory::GetList(" group_id = $group_id and person_id = $person_id"));
    }

    public static function GetQuery($group_id) {

        return GroupHasPersonFactory::GetQuery($group_id);
    }

    public static function Get($id) {
        $res = false;
        $Person = CoreLogic::VerifyPerson();
        return GroupHasPersonFactory::Get($id);
    }

    public static function GetList() {
        $Person = CoreLogic::VerifyPerson();
        $ob = GroupHasPersonFactory::GetList();
        return $ob;
    }

}
