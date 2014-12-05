<?php

/**
 * @User:  ThanhChat
 * Date:   Dec 3, 2014
 * Time:   3:33:47 PM
 */
class Portal_Model_TagAlbumValue extends Zend_Db_Table_Abstract {
    //put your code here
    //put your code here
    protected $_name = 'tag_album_value';
    protected $_primary = 'ID_TAG_VALUE';
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function addTagsAlbumValue($data) {
        $this->insert($data);
    }
    public function deleteTagAlbumValue($idAlbum){
        $sql = "delete from tag_album_value where ALBUM_ID=$idAlbum";
        $this->db->query($sql);
        
    }
}
