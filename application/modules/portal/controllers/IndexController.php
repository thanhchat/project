<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 18, 2014
 * Time:   8:38:57 PM
 */
class Portal_IndexController extends System_Controller_Action{ 
    public function indexAction(){ 
        $this->view->headTitle("Admin panel");
        $this->loadTemplates("portal");
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $this->view->userLogin=$auth->getIdentity()->EMAIL;
        }
        $mess=array("Loi email");
        $messo=new System_Message;
        $this->view->errorMessage=$messo->addMessage($mess);
    } 

}