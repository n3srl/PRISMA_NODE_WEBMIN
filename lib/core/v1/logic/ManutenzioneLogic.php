<?php
/**
*
* @author: N3 S.r.l.
*/

class ManutenzioneLogic 
{

// Funzione reboot
    public static function rebootServer() {
        CoreLogic::VerifyPerson();

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_); // IP e porta del server Docker
        $cmd_out = "";
        $command = "sudo reboot"; 
		
        if ($session) {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            } else {
                return CoreLogic::GenerateErrorResponse(array("res" => false, "error" => _("Autenticazione SSH fallita")));

            }
        } else {
            return CoreLogic::GenerateErrorResponse( array(
                "res" => false,
                "error" => _("Connessione SSH fallita")
            ));
            
        }

        unset($session);//chiusura ssh 

        return  CoreLogic::GenerateResponse(true, array(
            "res" => true,
            "result" => _("Reboot in corso..."),
            "data" => $cmd_out
        )); 
       
        
    }

}

       