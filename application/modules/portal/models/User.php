<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author ThanhChat
 */
class Portal_Model_User extends Zend_Db_Table_Abstract {

    //put your code here
    protected $_name = "user_login";
    protected $_primary = "USER_LOGIN_ID";
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function getListUserLogin() {
        $sql = $this->select()->from('user_login', array('USER_LOGIN_ID', 'EMAIL', 'FULLNAME', 'PHONE', 'ADDRESS', 'LAST_UPDATED', 'CREATED_BY_USER_LOGIN', 'CREATED_DATE', 'ENABLED', 'USER_ROLE')); //("SELECT * FROM user_login WHERE ENABLED = 'Y' ORDER BY USER_LOGIN_ID DESC");
        return $sql;
    }

    public function searchUser($userLoginId, $name, $email, $phone, $active) {
        $flag = false;
        $sql = $this->select()->from('user_login', array('USER_LOGIN_ID', 'EMAIL', 'FULLNAME', 'PHONE', 'ADDRESS', 'LAST_UPDATED', 'CREATED_BY_USER_LOGIN', 'CREATED_DATE', 'ENABLED', 'USER_ROLE'));
        if ($userLoginId != "") {
            $sql->where("USER_LOGIN_ID = ?", $userLoginId);
            $flag = true;
        }
        if ($name != "" && strlen($name) > 0) {
            $sql->where('FULLNAME like ?', "%" . trim($name) . "%");
        }
        if ($email != "" && strlen($email) > 0) {
            $sql->where("EMAIL=?", $email);
        }
        if (is_numeric($phone) && $phone != "" && strlen($phone) > 0) {
            $sql->where("PHONE=?", $phone);
        }
        if ($active != "" && strlen($active) > 0) {
            $sql->where("ENABLED=?", $active);
        }
        return $sql;
    }

    public function deleteUser($id) {
        $sql = "delete from user_login where USER_LOGIN_ID=$id";
        $this->db->query($sql);
    }

    public function updateStatus($id, $value) {
        $sql = "update user_login set ENABLED='" . $value . "' where USER_LOGIN_ID=$id";
        $this->db->query($sql);
    }

    public function addUser($data) {
        $this->insert($data);
    }

    public function getUserById($id) {
        $sql = "select * from user_login where USER_LOGIN_ID=$id";
        return $this->db->query($sql)->fetchAll();
    }
    public function getUserByEmail($email) {
        $sql = "select * from user_login where EMAIL='".$email."'";
        return $this->db->query($sql)->fetchAll();
    }

    public function updateInfor($data, $where) {
        $this->update($data, $where);
    }

    public function updatePass($data, $where) {
        $this->update($data, $where);
    }

}
