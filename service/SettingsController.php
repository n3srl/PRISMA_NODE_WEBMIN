<?php
/**
 * Class for SettingsController
 * 
 * @author: N3 S.r.l.
 */


class SettingsController extends Controller
{
    public function editOperation() {
     
        if (isset($_POST['language'])) {
            $lang = $_POST['language'];
            setcookie('lang', $lang, time() + strtotime('+30 days')); 

            $_SESSION['lang'] = $lang; 
            header("Location: " . $_SERVER['REQUEST_URI']);  
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
       
      
        //Debug output:
        /*
        echo "Available locales:<br>";
        setlocale(LC_ALL, "en_US");
        echo setlocale(LC_ALL, 0);
        */

        //$locale_path = './locale/en_US/LC_MESSAGES/en.mo'; 
        /*if ($_SESSION['lang'] === 'en_US'&& file_exists($locale_path)) {
            bindtextdomain('en', './locale');
            textdomain('en');
            bind_textdomain_codeset('en', 'UTF-8');
        }*/

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
        
        //Debug output:
        /*
        echo "<br>lang session: ";
        var_dump($_SESSION['lang']); 
        echo "<br>lang cookie: ";
        var_dump($_COOKIE['lang']); */
    }
}
