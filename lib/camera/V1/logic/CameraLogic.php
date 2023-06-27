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

    public static function Bounds($cmd) {

        $out = CameraLogic::GetBounds($cmd);
        return $out;
	}

    public static function CanRunCalibration() {

        $can = self::CanCalibrate();

        return array(
            "res" => true,
            "data" => $can
        );
	}

    private static function ExecuteArvCommand($cmd = "") {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && $cmd != "")
        {
            $host = '-a '.$_POST['ip'];
        }
        

        $command = "arv-tool-0.8 $host $cmd";

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

    private static function GetBounds() {

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        if(isset($_POST['ip']) && isset($_POST['camera']))
        {
            $host = $_POST['ip']." ".$_POST['camera'];
        }
        

        $command = "python3 /home/prisma/SETUP/bounds.py $host";

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

    public static function CanCalibrate()
    {
        if(file_exists("/var/www/html/calibration/calibrationstarted.txt")) return false;
        return true;
    }

    public static function Calibration() {

        if(!self::CanCalibrate())
        {
            return array(
                "res" => true,
                "data" => "Una calibrazione e gia in corso"
            );
        }

        DockerApiLogic::sshContainerStop("freeture");
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";
        $host = "";

        $command_count = $_POST['maxGain'] - $_POST['minGain'];
        $cam = $_POST['camera'];

        $now = date("dmY", time());
        $exp = $_POST['exposure'];
        $cam = 1;

        $v1 = "-v /prismadata/freeture-conf/configuration.cfg:/usr/local/share/freeture/configuration.cfg";
        $v2 = "-v /prismadata/freeture-conf/calibration:/usr/local/share/freeture/calibration/";
        $image = "n3srl/freeture13";

        if(isset($_POST['image']))
        {
            $image = $_POST['image'];
        }

        $cmd_seq = array();
        
        for($gain = $_POST['minGain'];$gain <= $_POST['maxGain']; $gain++)
        {
            
            $params = "-m 4 -e $exp -g $gain --id $cam --fits --savepath /usr/local/share/freeture/calibration/";
            $name = "calibration-g".$gain."e".$exp."-".$now;
            $cmd = "docker run --rm --network host $v1 $v2 $image $params --filename  $name";

            $cmd_seq[] = $cmd;
        }

        
        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {
                $stream = ssh2_exec($session, "touch /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "chmod +x /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, $command);
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "touch /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                foreach($cmd_seq as $command)
                {
                    $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                    stream_set_blocking($stream, true);
                    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                    $cmd_out = stream_get_contents($stream_out);
                }

                // Now create zip
            }
        }

        

        unset($session);

        

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if($session)
        {
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk"))
            {

                $command = "docker exec prisma-orma chown -R 1000:www-data /usr/local/share/freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "docker exec prisma-orma chmod -R 770 /freeture";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "cd /prismadata/freeture-conf/calibration/ && zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip .";
                //$command = "zip -r /prismadata/orma-src/calibration/calibration-e$exp-$now.zip /prismadata/freeture-conf/calibration/";
                $stream = ssh2_exec($session,"echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
                $command = "rm -rf /prismadata/freeture-conf/calibration";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm /prismadata/orma-src/calibration/calibrationstarted.txt";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "rm -rf /home/prisma/calibration.sh";
                $stream = ssh2_exec($session, "echo '$command' >> /home/prisma/calibration.sh");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                $command = "nohup /home/prisma/calibration.sh > out.txt";
                $stream = ssh2_exec($session, $command);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);

                
            }
        }

        return array(
            "res" => true,
            "data" => "Calibrazione iniziata, presto disponibile"
        );

	}

    public static function GetCameraCalibrations()
    {
        $path = _CALIBRATION_PATH_;
        $files = glob($path. "/*");

        $count = count($files);

        if($count == 0)
        {
            $data = "<tr><td>Nessuna calibrazione recente</td></tr>";
        } else 
        {
            $data = "";
            foreach($files as $f)
            {
                
                $calibration = basename($f);

                if($calibration == "calibrationstarted.txt") continue;  

                $date = explode("-", $calibration)[2];
                $date = substr($date, 0, -4);
                $day = substr($date, 0, 2);
                $month = substr($date, 2, 2);
                $year = substr($date, 4, 4);
                $date = $day . "/" . $month . "/" . $year;  

                $data .= "<tr><td>$calibration</td><td>$date</td><td><a style = 'font-size:22px;color:black; text-decoration:none' href = '/calibration/$calibration'><i class='fa fa-download'></i></a></td><td><span name = '$calibration' class = 'calibration_delete'><i style = 'cursor:pointer;font-size:22px' class='fa fa-trash'></i></span></td></tr>";
                
            }
        }
        

        return array(
            "res" => true,
            "data" => $data
        );
    }

    public static function DeleteCalibration($request)
    {

        $calibration = $request->query->get('calibration');

        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $cmd_out = "";

        $command = "rm /prismadata/orma-src/calibration/$calibration";

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

