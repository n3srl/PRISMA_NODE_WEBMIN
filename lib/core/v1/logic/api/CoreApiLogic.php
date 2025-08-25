<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CoreApiLogic {

    public static function GetCurrentUser() {
        try {
            $res = false;
            $Person = CoreLogic::VerifyPerson();
            $ob = CoreLogic::GetPersonLogged();
            unset($ob->password);
            unset($ob->id);
            unset($ob->oid);
            unset($ob->modified_by);
            unset($ob->created_by);
            unset($ob->assigned);
            unset($ob->create_date);
            unset($ob->valid_from);
            unset($ob->valid_to);
            unset($ob->erased);
            unset($ob->last_update);
            
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

}
