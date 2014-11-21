<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 18, 2014
 * Time:   8:18:59 PM
 */
class System_AuthLogin extends Zend_Controller_Plugin_Abstract{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $auth=  Zend_Auth::getInstance();
        $moduleName=$request->getModuleName();
        if(!DEV){
            if($moduleName=="portal"){//Kiem tra module reqest la Portal
                if(!$auth->hasIdentity()){ //neu chua dang nhap
                    $sessionLogin=new Zend_Session_Namespace("sessionLogin");
                    $sessionLogin->destination_url=$request->getPathInfo();
                    $request->setModuleName('portal');
                    $request->setControllerName('auth');
                    $request->setActionName('login');
                }else{//cap nhat thoi gian hieu luc cua phien dang nhap
                    $authns = new Zend_Session_Namespace($auth->getStorage()->getNamespace());
                    $authns->setExpirationSeconds(60 * 30);// expire auth storage after 30 min 
                }
            }
        }
    }
}
