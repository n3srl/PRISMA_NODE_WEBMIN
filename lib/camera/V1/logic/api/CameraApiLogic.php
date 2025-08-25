<?php

/**
 *
 * @author: N3 S.r.l.
 */
class CameraApiLogic {

    public static function List($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::List();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Reset($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Reset();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function FactoryReset($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::FactoryReset();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Features($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Features();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Values($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Values();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Cmd($cmd)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Cmd($cmd);

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Bounds($cmd)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Bounds($cmd);

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function Calibration()
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::Calibration();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function CanCalibrate()
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::CanRunCalibration();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function GetCalibration()
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::GetCameraCalibrations();

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

    public static function DeleteCalibration($request)
    {
        try {

            $Person = CoreLogic::VerifyPerson();

            $res = CameraLogic::DeleteCalibration($request);

        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res["res"], $res["data"]);
    }

}