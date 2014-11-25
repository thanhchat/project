<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author ThanhChat
 */
class Portal_UserController extends System_Controller_Action {

    //put your code here
    public function init() {
        $this->loadTemplates('portal');
    }

    public function indexAction() {
        $this->view->headTitle('Quản lý tài khoản.');
        if ($this->_request->isPost()) {
            $userId = $this->_request->getPost('user_id');
            $this->view->userId = $userId;
            $name = $this->_request->getPost('fullname');
            $this->view->name = $name;
            $email = $this->_request->getPost('email');
            $this->view->email = $email;
            $phone = $this->_request->getPost('phone');
            $this->view->phone = $phone;
            $active = $this->_request->getPost('active');
            $this->view->active = $active;
            $userLogin = new Portal_Model_User;
            $array = $userLogin->searchUser($userId, $name, $email, $phone, $active);
        } else {
            $userLogin = new Portal_Model_User;
            $array = $userLogin->getListUserLogin();
        }
        $adapter = new Zend_Paginator_Adapter_DbSelect($array);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(4);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->dataPaginator = $paginator;
    }

    public function editAction() {
        $this->view->headTitle('Sửa thông tin tài khoản.');
        $id = $this->_request->getParam('id');
        if ($id > 0) {
            $this->view->id = $id;
            $objUser = new Portal_Model_User;
            $user = $objUser->getUserById($id);
            $this->view->email = $user[0]['EMAIL'];
            $this->view->fullname = $user[0]['FULLNAME'];
            $this->view->phone = $user[0]['PHONE'];
            $this->view->address = $user[0]['ADDRESS'];
            $this->view->active = ($user[0]['ENABLED'] == "Y") ? TRUE : FALSE;
            if ($user[0]['USER_ROLE'] == "1")
                $this->view->role1 = "selected";
            else
                $this->view->role2 = "selected";
        }
        if ($this->_request->isPost()) {
            $id = $this->_request->getParam('id');
            $this->view->id = $id;
            $fullname = $this->_request->getPost('fullname');
            $email = $this->_request->getPost('email');
            $phone = $this->_request->getPost('phone');
            $address = $this->_request->getPost('address');
            $active = $this->_request->getPost('active');
            $role = $this->_request->getPost('role');
            $active = ($active) ? "Y" : "N";
            $objUser = new Portal_Model_User;
            $timestamp = date("Y-m-d H:i:s");
            $data = array('PHONE' => $phone, 'ADDRESS' => $address, 'ENABLED' => $active, 'USER_ROLE' => $role, 'LAST_UPDATED' => $timestamp, 'FULLNAME' => $fullname);
            $objUser->updateInfor($data, "USER_LOGIN_ID=".$id);
            $this->_redirect("/portal/user/");
        }
        //$this->getHelper("viewRenderer")->setNoRender();
    }

    public function addAction() {
        $this->view->headTitle('Thêm tài khoản.');
        if ($this->_request->isPost()) {
            $fullname = $this->_request->getPost('fullname');
            $email = $this->_request->getPost('email');
            $this->view->email = $email;
            $phone = $this->_request->getPost('phone');
            $address = $this->_request->getPost('address');
            $pass = $this->_request->getPost('password');
            $re_pass = $this->_request->getPost('re_password');
            $active = $this->_request->getPost('active');
            $role = $this->_request->getPost('role');
            $active = ($active) ? "Y" : "N";
            $empty = new Zend_Validate_NotEmpty();
            if ($empty->isValid($email)) {
                $val = new Zend_Validate_EmailAddress();
                if ($val->isValid($email)) {
                    if ($empty->isValid($pass) && $empty->isValid($re_pass)) {
                        if ($pass != $re_pass) {
                            $this->view->errorMessage = "Mật khẩu không khớp";
                        } else {
                            $userSession = Zend_Auth::getInstance();
                            $idUser = $userSession->getIdentity()->USER_LOGIN_ID;
                            $data = array('EMAIL' => $email, 'PASSWORD' => md5($pass), 'PHONE' => $phone, 'ADDRESS' => $address, 'ENABLED' => $active, 'USER_ROLE' => $role, 'CREATED_BY_USER_LOGIN' => $idUser, 'FULLNAME' => $fullname);
                            $user = new Portal_Model_User;
                            $user->addUser($data);
                            $this->view->successMessage = "Thêm thành viên thành công.";
                            $fullname = "";
                            $email = "";
                            $phone = "";
                            $pass = "";
                            $re_pass = "";
                            $address = "";
                            $active = FALSE;
                            $this->view->email = "";
                            $this->view->active = $active;
                        }
                    } else {
                        $this->view->errorMessage = "Mật khẩu không được để trống";
                    }
                } else {
                    $this->view->errorMessage = "Email không đúng định dạng";
                }
            } else {
                $this->view->errorMessage = "Email không được để trống";
            }
        }
        //$this->getHelper("viewRenderer")->setNoRender();
    }

    public function deleteAction() {
        $data = $this->getRequest()->getParams('id');
        $id = $data['id'];
        $user = new Portal_Model_User();
        $user->deleteUser($id);
        $this->_redirect("/portal/user/");
        $this->getHelper("viewRenderer")->setNoRender();
    }

    public function activeAction() {
        $id = $this->getRequest()->getParams('id');
        $value = $this->getRequest()->getParams('value');
        $user = new Portal_Model_User();
        $user->updateStatus($id['id'], $value['value']);
        $this->_redirect("/portal/user/");
        $this->getHelper("viewRenderer")->setNoRender();
    }

}
