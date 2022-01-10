<?php

class ApiLogic {

    public static $INFO = "00";
    public static $WARNING = "11";
    public static $ERROR = "22";
    public static $FIELD_CODE = "01";
    public static $PERSON_CODE = "02";
    public static $CSFR_CODE = "03";
    public static $CRUD_CODE = "04";

    public static function getFieldErrorMessage() {
        return ApiLogic::getErrorMessage();
    }

    public static function getFieldErrorCode() {
        return "Code:" . ApiLogic::$ERROR . ApiLogic::$FIELD_CODE;
    }

    public static function getErrorMessage() {
        return _("Si è verificato un errore<br />Contattare l'amministratore di sistema");
    }

    public static function getCrudErrorCode() {
        return ApiLogic::getErrorMessage() . "<br />Code:" . ApiLogic::$ERROR . ApiLogic::$CRUD_CODE;
    }

    public static function getDcmtNumberErrorMessage() {
        return _("Il numero documento è già in utilizzo<br />Ricarica il numero per procedere");
    }

    public static function getDcmtValidNumberErrorMessage() {
        return _("Il numero documento non è nel formato corretto");
    }

}
