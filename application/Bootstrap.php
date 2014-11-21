<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public function _initAutoload() {
        $front = Zend_Controller_Front::getInstance();
        $array = array('module' => 'error',
            'controller' => 'error',
            'action' => 'error'
        );
        $obj = new Zend_Controller_Plugin_ErrorHandler($array);
        $front->registerPlugin($obj);
        
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace(array("System_"));
    }

    protected function _initCachemanager() {
        $cacheManager = new Zend_Cache_Manager;
        $dbcache = array(//bắt đầu khai báo template
            'frontend' => array(
                'name' => 'Core',
                'options' => array(
                    'lifetime' => 3600 * 24, //cache is cleaned once a day
                    'automatic_serialization' => 'true',
                ),
            ),
            'backend' => array(
                'name' => 'File',
                'options' => array(
                    'cache_dir' => CACHE_DIR,
                ),
            ),
        );                    //kết thúc khai báo template
        $cacheManager->setCacheTemplate('dbcache', $dbcache); //gán template $dbcache với tên là dbcache
        return $cacheManager;
    }

}
