<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Alessandro
 */
class Controller {

    public static function getFromArray($ob, $array, $sanitize = true) {
        global $db_conn;
        $temp_array = get_object_vars($ob);
        
        foreach ($temp_array as $key => $value) {
            if (isset($array[$key])) {
                if ($sanitize) {
                    $ob->$key = mysqli_real_escape_string($db_conn,$array[$key]);
                } else {
                    $ob->$key = $array[$key];
                }
            }else{
                $ob->$key = null;
            }
        }
    }
    
    public static function getTextArea($ob) {
        return stripslashes(str_replace("\\r\\n", "&#13;&#10;",$ob));
    }

    public static function utf8ize($d)
        { 
            if (is_array($d) || is_object($d))
                foreach ($d as &$v) $v = clienteController::utf8ize($v);
            else
                return utf8_encode($d);

            return $d;
        }
    
    public function securityCheck($level_required = 0) {
       
        /*$current_user = UserFactory::getCurrent();
        if ($level_required != UserLevel::ALL) {
            if (!isset($current_user) || $current_user->level < $level_required) {
                throw new SecurityException();
            }
        }*/
    }
    
    public function checkLoginOperatore(){
            global $db_conn;
            try {
                    $data = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents("php://input");
                } catch (Exception $e) {
                    $data = "";
                }
                $jsonArray = null;
                if($data != ""){
                    
                    $data = file_get_contents("php://input");
                    $data_strip  = chop($data);
                    $jsonArray = json_decode($data_strip);        
                    
                    if ($jsonArray == null) {
                        echo '{"Error":"Malformed JSON"}';
                        die;
                    }
                   
                    
                    
                    if ($jsonArray != null) {
                        
                        
                        if (isset($jsonArray[0]->{'username'}))
                            $username = mysqli_real_escape_string($db_conn, $jsonArray[0]->{'username'});
                        else
                            return $jsonArray;
                        
                        if (isset($jsonArray[0]->{'password'}))
                            $password = mysqli_real_escape_string($db_conn, $jsonArray[0]->{'password'});
                        else
                            return $jsonArray;
                        
                        $Op = OperatoreFactory::GetOperatoreLogin($username,$password);
                        
                       
                        if($Op!= null){
                            
                            return $jsonArray;
                        }else{
                            
                            return null;
                        }
                    }
                    
                    
                    
                }   
                return $jsonArray;
        }
    
    
    public function generateResponse($res) {
        if ($res){
            return new Response();
        } else {
            throw new Exception;
        }
    }
    
    public function generateResponseRedirect($res, $redirect) {
        if ($res){
            $response = new Response();
            $response->redirect = $redirect;
            return $response;
        } else {
            throw new Exception;
        }
    }

}
