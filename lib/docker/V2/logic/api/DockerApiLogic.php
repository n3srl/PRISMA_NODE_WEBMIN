<?php

/**
 *
 * @author: N3 S.r.l.
 */
class DockerApiLogic {

    public static function Save($request) {

        try {

            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];
            $ob->name = $tmp["name"];

            $res = self::sshContainerRestart($ob);
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function Update($request) {

        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];
            $ob->name = $tmp["name"];

            $res = self::sshContainerRestart($ob);
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function Erase($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = DockerLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DockerLogic::Erase($ob);
            CoreLogic::commitTransaction();
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function Delete($request) {
        try {
            $Person = CoreLogic::VerifyPerson();
            CoreLogic::CheckCSRF($request->get("token"));

            $ob = new Docker();
            $tmp = $request->get("data");

            $ob->id = $tmp["id"];

            $ob = DockerLogic::Get($ob->id);

            CoreLogic::beginTransaction();
            $res = DockerLogic::Delete($ob);
            CoreLogic::commitTransaction();
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function Get($id) {
        try {
            $res = false;
            $Person = CoreLogic::VerifyPerson();
            $ob = self::sshContainerGet($id);
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetList() {
        try {
            $Person = CoreLogic::VerifyPerson();
            $ob = DockerLogic::GetList();
            $res = true;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res, $ob);
    }

    public static function GetListFilterAjax($columnName) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $results = array();
            $data = new stdClass();
            $codes = DockerFactory::GetListFilter($columnName, $_GET['term']);
            foreach ($codes as $code) {
                $obj = new stdClass();
                $obj->id = $code->{$columnName};
                $obj->text = $code->{$columnName};
                $results[] = $obj;
            }
            $data->results = $results;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return $data;
    }

    public static function GetListFKAjax($columnName) {
        try {
            $Person = CoreLogic::VerifyPerson();
            $results = array();
            $data = new stdClass();
            switch ($columnName) {
                /*                 * * ESEMPIO **
                  case "created_by":
                  $foreignKey = end(DockerFactory::GetForeignKeyParams($columnName));
                  $codes = DockerFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME,array("id","CONCAT(last_name, ' ', first_name) AS full_name"),$_GET['term']);
                  $data = new stdClass();
                  foreach ($codes as $code){
                  $obj = new stdClass();
                  $obj->id = $code->id;
                  $obj->text = $code->full_name;
                  $results[] = $obj;
                  }
                  break;
                 */
                default:
                    $foreignKey = end(DockerFactory::GetForeignKeyParams($columnName));
                    $codes = DockerFactory::GetListFK($foreignKey->REFERENCED_TABLE_NAME, $foreignKey->REFERENCED_COLUMN_NAME, $_GET['term']);
                    $data = new stdClass();
                    foreach ($codes as $code) {
                        $obj = new stdClass();
                        $obj->id = $code->{$foreignKey->REFERENCED_COLUMN_NAME};
                        $obj->text = $code->{$foreignKey->REFERENCED_COLUMN_NAME};
                        $results[] = $obj;
                    }
            }
            $data->results = $results;
        } catch (ApiException $a) {
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return $data;
    }

    public static function GetListDatatable() {
        $reply = self::sshContainerList();
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "pageToShow" => 0,
            "iTotalRecords" => count($reply),
            "iTotalDisplayRecords" => count($reply),
            "aaData" => $reply
        );

        return $output;
    }

    //Access SSH container and get Container list
    public static function sshContainerList() {
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
    }

    //Access SSH container and get a single Container
    public static function sshContainerGet($id) {
        $list = array();
        $i = 0;
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        if ($session) {

            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {

                //Execute command to get containers
                //https://www.baeldung.com/ops/docker-list-containers
                $stream = ssh2_exec($session, "sudo docker container ls -a --format \"{{.Names}} {{.Image}} {{.Status}}\"");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $text = stream_get_contents($stream_out);

                //Parse Containers
                $containers = explode("\n", $text);
                foreach ($containers as $container) {

                    //Parse Container
                    if ($i < count($containers) - 1) {

                        if ("$i" === $id) {
                            $conta = explode(" ", $container);
                            $dc = new Docker();
                            $dc->id = $id;
                            $dc->name = $conta[0];
                            $dc->image = $conta[1];
                            $dc->status = $conta[2];
                            return $dc;
                        }


                        //$list[] = array($conta[0], $conta[1], "empty", $conta[2], "no date", 0, $i);

                        $i++;
                    }
                }
            }

            //ssh2_disconnect($session); -> This causes Segmentation fault !
            unset($session);
        }

        return false;
    }

    //Access SSH container and get a single Container
    public static function sshContainerStart($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker start " . $ob);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    //Access SSH container and get a single Container
    public static function sshContainerRestart($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker restart " . $ob);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    //Access SSH container and get a single Container
    public static function sshContainerStop($ob) {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker stop " . $ob);
                $result = true;
            }
            unset($session);
        }
        return CoreLogic::GenerateResponse($result, $ob);
    }

    public static function sshFreetureLog() {
        $session = ssh2_connect(_DOCKER_IP_, _DOCKER_PORT_);
        $print = ssh2_fingerprint($session);

        $cmd_out = "";
        $result = false;
        if ($session) {
            //Authenticate with keypair generated using "ssh-keygen -m PEM -t rsa -f /path/to/key"
            if (ssh2_auth_pubkey_file($session, "prisma", _DOCKER_SSH_PUB_, _DOCKER_SSH_PRI_, "uu4KYDAk")) {
                $stream = ssh2_exec($session, "docker logs freeture");
                stream_set_blocking($stream, true);
                $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $cmd_out = stream_get_contents($stream_out);
                $result = true;
            }
            
        }
        unset($session);
        return array(
            "res" => $result,
            "data" => $cmd_out
        );
    }

    public static function FreetureLog() {

        try {

            $Person = CoreLogic::VerifyPerson();

            $res = self::sshFreetureLog();
        } catch (ApiException $a) {
            CoreLogic::rollbackTransaction();
            return CoreLogic::GenerateErrorResponse($a->message);
        }
        return CoreLogic::GenerateResponse($res['res'], $res['data']);
    }

}
