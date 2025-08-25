<?php
/**
 * Class for SettingsController
 * 
 * @author: N3 S.r.l.
 */


class SettingsController extends Controller
{
    public function changeLanguageOperation() {
        $redir="/";
        if (isset($_POST["redir"])) {
            $redir = $_POST["redir"];
        } 

        if (isset($_POST['language'])) {
            $lang = $_POST['language'];
            setcookie('lang', $lang, time() + strtotime('+30 days')); 

            $_SESSION['lang'] = $lang; 
            header("Location: ".$redir);  
            exit();
        } else {
            if (isset($_COOKIE['lang'])) {
                $_SESSION['lang'] = $_COOKIE['lang'];
                $lang= $_SESSION['lang'];

            } else {
                $_SESSION['lang'] = 'it_IT';
                $lang=$_SESSION['lang'];
            }
        }
        
        header("location: $redir");

        
    }


    public function editOperation() {
     
        $permission = parent::securityCheck();
        global $params;
        $par = 0;
        if (isset($params[0]) && !empty($params[0])) {
            $par = $params[0];
        }
        
        if (!$permission) {
            @include "./view/user/login.php";
            exit;
        }
        echo '<script>var ObjID = ' . $par . ';</script>';
        
    }
}
