<?php

/**
 * @User:  ThanhChat
 * Date:   Dec 4, 2014
 * Time:   3:55:58 PM
 */
class Portal_Model_ImageAlbum extends Zend_Db_Table_Abstract{
    //put your code here
     protected $_name = "album_image";
    protected $_primary = "AB_IMAGE_ID";
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }
    public function addImage($data){
        $this->insert($data);
    }
}
