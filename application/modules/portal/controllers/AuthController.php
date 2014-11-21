<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthController
 *
 * @author ThanhChat
 */
class Portal_AuthController extends Zend_Controller_Action {

    //put your code here
    public function init() {
        $baseurl = $this->_request->getbaseurl();
        $this->view->headLink()->appendStylesheet($baseurl . "/public/templates/portal/css/main.css");
        $this->view->headLink()->appendStylesheet($baseurl . "/public/templates/portal/css/message.css");
        $this->view->headscript()->appendFile($baseurl . "/public/templates/portal/js/jquery.js", "text/javascript");
    }

    public function loginAction() {
        //$this->_helper->layout->disableLayout();
        $this->view->headTitle("Portal login");
        $form = new Portal_Form_LoginForm;
        $form->getElement('signin')->setLabel(' ');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $form->getValues();
                $val = new Zend_Validate_EmailAddress;
                if ($val->isValid($data['email'])) {
                    $auth = Zend_Auth::getInstance();
                    $db = Zend_Registry::get('db');
                    $authAdapter = new Zend_Auth_Adapter_DbTable($db);
                    $authAdapter->setTableName('user_login')
                            ->setIdentityColumn('EMAIL')
                            ->setCredentialColumn('PASSWORD');
                    $authAdapter->setIdentity($data['email']);
                    $password = md5($data['password']);
                    $authAdapter->setCredential($password);
                    $select = $authAdapter->getDbSelect();
                    $select->where("ENABLED = 'Y'");
                    $result = $auth->authenticate($authAdapter);
                    if ($result->isValid()) {
                        $auth->getStorage()->write($authAdapter->getResultRowObject());
                        $mysession = new Zend_Session_Namespace('sessionLogin');
                        if (isset($mysession->destination_url)) {
                            $url = $mysession->destination_url;
                            unset($mysession->destination_url);
                            $this->_redirect($url);
                        }
                        $this->_redirect('portal/');
                    } else {
                        $this->view->errorMessage = '<div class="alert alert-error">
                        <a class="close" data-dismiss="alert" onclick="$(this).parent().slideUp();return false;">×</a>
                        <strong>Error! </strong>Email hoặc mật khẩu không chính xác.
                      </div>';
                    }
                } else {
                    $this->view->errorMessage = '<div class="alert alert-error">
                        <a class="close" data-dismiss="alert" onclick="$(this).parent().slideUp();return false;">×</a>
                        <strong>Error! </strong>Email không đúng định dạng.
                      </div>';
                }
            }
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $r->gotoUrl('/portal')->redirectAndExit();
    }

}
