<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 26, 2014
 * Time:   2:21:06 PM
 */
class Portal_Model_MenuItem extends Zend_Db_Table_Abstract {

    //put your code here
    protected $_name = "menu_items";
    protected $_primary = "MENU_ID";
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function getListMenuItems() {
        $sql = $this->select()->from('menu_items', array('MENU_ID', 'NAME', 'PARENTS', 'LEVEL', 'URL', 'ORDERING', 'ENABLED')); //("SELECT * FROM user_login WHERE ENABLED = 'Y' ORDER BY USER_LOGIN_ID DESC");
        return $sql;
    }

    public function getMenuItemById($id) {
        $sql = "select * from menu_items where MENU_ID=$id";
        return $this->db->query($sql)->fetchAll();
    }

    public function getListMenuToArray() {
        $sql = "select * from menu_items";
        return $this->db->query($sql)->fetchAll();
    }

    public function updateInfor($data, $where) {
        $this->update($data, $where);
    }

    public function addMenuItem($data) {
        $this->insert($data);
    }

    public function deleteMenuItem($id) {
        $sql = "delete from menu_items where MENU_ID=$id";
        $sql_delete_Chil = "delete from menu_items where PARENTS=$id";
        $this->db->query($sql_delete_Chil);
        $this->db->query($sql);
    }

}
