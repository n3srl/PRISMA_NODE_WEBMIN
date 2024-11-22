<?php
    
    class HomeLogic {
    //controllo connessione ad internet 
    public static function checkInternet() {
        $Home = CoreLogic::VerifyPerson();
        if (@fsockopen('www.google.com', 80)) {
        fclose(@fsockopen('www.google.com', 80)); 
        return true; 
    }
    return false; 
    }
    
    //controllo presenza file mask
    public static function checkMask() {
        $Home = CoreLogic::VerifyPerson();
        if (!file_exists('/freeture/default.bmp')) { 

            return false;
        }else{
            return true;
        } 
    }

    //controllo presenza file di configurazione
    public static function checkConfig() {
        $Home = CoreLogic::VerifyPerson();
        if (!file_exists('/usr/local/share/freeture/configuration.cfg')) { 
            return false;
        }else{
            return true;
        } 
    }


}

    ?>