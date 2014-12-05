<?php

/**
 * @User:  ThanhChat
 * Date:   Dec 3, 2014
 * Time:   1:08:08 PM
 */
class Portal_Model_TagAlbum extends Zend_Db_Table_Abstract {

    //put your code here
    protected $_name = 'tag_album';
    protected $_primary = 'ID_TAG';
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function addTagsValue($data) {
        $this->insert($data);
        return $this->getAdapter()->lastInsertId();
    }
    public function getTagsByName($name){
        $sql = "select * from tag_album where TAG_NAME='".$name."'";
        return $this->db->query($sql)->fetchAll();
    }

}
