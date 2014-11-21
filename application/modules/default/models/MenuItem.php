<?php
class Default_Model_MenuItem extends Zend_Db_Table_Abstract{
    protected $db;
    public function __construct(){
        parent::__construct(); 
        $this->db=Zend_Registry::get('db');
    }
    public function getListMenuItem(){
        $sql=$this->db->query("select * from menu_items where PARENTS=0 and ENABLED=1 order by ORDERING ASC");
        return $sql->fetchAll();
    }
}
?>