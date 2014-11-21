<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Default_Bootstrap extends Zend_Application_Module_Bootstrap{
     protected function _initDatabase(){
        $db = $this->_application->getPluginResource('db')->getDbAdapter();
        Zend_Registry::set('db', $db);    
    }
    
}
