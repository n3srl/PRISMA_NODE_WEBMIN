<?php
/**
*
* @author: N3 S.r.l.
*/

class CameraLogic
{
	public static function List() {

        $out = CameraLogic::ExecuteArvCommand();
        return $out;
	}

    public static function Reset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceReset");
        return $out;
	}

    public static function FactoryReset() {

        $out = CameraLogic::ExecuteArvCommand("control DeviceFactoryReset");
        return $out;
	}

    public static function Features() {

        $out = CameraLogic::ExecuteArvCommand("features");
        return $out;
	}

    public static function Values() {

        $out = CameraLogic::ExecuteArvCommand("values");
        return $out;
	}
    
    public static function Cmd($cmd) {

        $out = CameraLogic::ExecuteArvCommand($cmd);
        return $out;
	}

    private static function ExecuteArvCommand($cmd = "") {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";

        $command = "arv-tool-0.8 ".$cmd;

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
            }
        }

        unset($session);

        return array(
            "res" => true,
            "data" => $cmd_out
        );
	}

}




/* public static function sshContainerList() {
    $list = array();
    $i = 0;
    $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
    $print = ssh2_fingerprint($session);

    if ($session) {

        // Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
        if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

            // Execute command to get containers
            //https://www.baeldung.com/ops/docker-list-containers
            $stream = ssh2_exec($session, "docker container ls -a --format \"{{.Names}} {{.Image}} {{.Status}}\"");
            stream_set_blocking($stream, true);
            $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
            $text = stream_get_contents($stream_out);

            // Parse Containers
            $containers = explode("\n", $text);
            foreach ($containers as $container) {

                // Parse Container
                if ($i < count($containers) - 1) {

                    $conta = explode(" ", $container);

                    $list[] = array($conta[0], $conta[1], "empty", $conta[2], "no date", $conta[0], $conta[0], $i);

                    $i++;
                }
            }
        }

        //ssh2_disconnect($session); -> This causes Segmentation fault !
        unset($session);
    }

    return $list;
} */