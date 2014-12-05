<?php

/**
 * @User:  ThanhChat
 * Date:   Dec 1, 2014
 * Time:   10:00:45 PM
 */
class Portal_Model_Album extends Zend_Db_Table_Abstract {

    //put your code here
    protected $_name = "albums";
    protected $_primary = "ALBUM_ID";
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function getListAblum() {
        $sql = "select ALBUM_ID,ALBUM_TITLE,ALBUM_FOLDER_NAME from albums";
        return $this->db->query($sql)->fetchAll();
    }

    public function getListAllAblum() {
        $select = $this->select();
        $select->from('albums', array('ALBUM_ID', 'MENU_ITEM_ID', 'ALBUM_TITLE',
            'ALBUM_DESCRIPT', 'ALBUM_CONTENT', 'MEDIUM_IMAGE_URL',
            'ORIGINAL_IMAGE_URL',
            'CREATED_DATE', 'LAST_UPDATE','ALBUM_FOLDER_NAME','CREATED_BY_USER',
            'IS_ACTIVE','VIEW','TAGS','IMAGE_SOURCE'));
        $select->setIntegrityCheck(FALSE);
        $select->join('menu_items','menu_items.MENU_ID=albums.MENU_ITEM_ID', array('NAME'));
        $select->join('user_login','user_login.USER_LOGIN_ID=albums.CREATED_BY_USER',array('EMAIL'));
        return $select;
    }
    public function addAlbum($data){
        $this->insert($data);
       return $this->getAdapter()->lastInsertId();
    }
    
    public function getAlbumById($idAlbum){
        $sql = "select * from albums where ALBUM_ID=$idAlbum";
        return $this->db->query($sql)->fetchAll();
    }
    public function deleteAlbum($idAlbum){
        $sql = "delete from albums where ALBUM_ID=$idAlbum";
        $this->db->query($sql);
    }
    public function editAlbum($data,$where){
        $this->update($data, $where);
    }

}
