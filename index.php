<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');
define('APPLICATION_PATH',realpath(dirname(__FILE__) . '/application')); 
define('APPLICATION_ENV','production');
define("PUBLIC_PATH" , realpath(dirname(__FILE__)) . "/public");
set_include_path(APPLICATION_PATH . '/../library'); 
define('CACHE_DIR',APPLICATION_PATH.'/cache/');
define("TEMPLATES" , "/public/templates");
define("TEMPLATES_PATH" , PUBLIC_PATH . "/templates"); 
define('DEV', FALSE);
require_once 'Zend/Application.php' ;
$application = new Zend_Application( 
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini' 
); 
$application->bootstrap()->run();