<?php

class Info extends MY_Controller{
    function index(){
        echo phpinfo();
    }
    
    function phpbindir(){
        echo "PHPBINDIR: ". PHP_BINDIR;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

