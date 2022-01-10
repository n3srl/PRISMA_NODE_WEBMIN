<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultController
 *
 * @author Alessandro
 */
class HomeController extends Controller {
    function HomeController($param) {
        return;        
    }
    public function HomeOperation($param) {
        parent::securityCheck();
    }
   
}
