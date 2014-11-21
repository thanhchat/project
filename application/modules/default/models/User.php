<?php

class Default_Model_User extends Zend_Db_Table_Abstract {

    protected $_name = "user"; //Table name
    protected $_primary = "id"; //Primary key
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Zend_Registry::get('db');
    }

    public function listall2() {
        $sql = $this->db->query("select * from user order by id DESC");
        return $sql->fetchAll();
    }

    public function UserById($id) {
        $sql = $this->db->query("select * from user where id=$id ");
        return $sql->fetchAll();
    }

    public function getCustomerLimit($limit) {
        $sql = $this->db->query("SELECT * FROM customers ORDER BY id ASC LIMIT $limit");
        return $sql->fetchAll();
    }

    public function getCustomer_Limit_start_end($start, $limit) {
        $sql = $this->db->query("SELECT * FROM customers ORDER BY id ASC LIMIT $start, $limit");
        return $sql->fetchAll();
    }

    public function listall() {
        $data = $this->select();
        $data->from('user', array('id', 'username'));
        $data->order('id ASC');
        //$data=$this->fetchAll($data);
        return $data;
    }

    public function insert_user($data) {
        $this->insert($data);
    }

    public function update_user($data, $where) {
        $this->update($data, $where);
    }

    public function delete_user($id) {
        $where = "id=" . $id;
        $this->delete($where);
    }

    public function getUserList() {
        $data = $this->select()->from($this->_name, array('key' => 'id', 'value' => 'username'));
        $result = $this->getAdapter()->fetchAll($data);
        return $result;
    }

    public function array_merge_keys($ray1, $ray2) {
        $keys = array_merge(array_keys($ray1), array_keys($ray2));
        $vals = array_merge($ray1, $ray2);
        return array_combine($keys, $vals);
    }

    public function chuyenChuoi($str) {
        // In thường
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = html_entity_decode($str);
        $str = str_replace(array(' ', '_'), '-', $str);
        $str = html_entity_decode($str);
        $str = str_replace("ç", "c", $str);
        $str = str_replace("Ç", "C", $str);
        $str = str_replace(" / ", "-", $str);
        $str = str_replace("/", "-", $str);
        $str = str_replace(" - ", "-", $str);
        $str = str_replace("_", "-", $str);
        $str = str_replace(" ", "-", $str);
        $str = str_replace("ß", "ss", $str);
        $str = str_replace("&", "", $str);
        $str = str_replace("%", "percent", $str);
        $str = str_replace("----", "-", $str);
        $str = str_replace("---", "-", $str);
        $str = str_replace("--", "-", $str);
        $str = str_replace(".", "-", $str);
        $str = str_replace(",", "", $str);
        // In đậm
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str; // Trả về chuỗi đã chuyển
    }

    public function remove_allFile($dir) {
        if ($handle = opendir("$dir")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dir/$item")) {
                        remove_directory("$dir/$item");
                    } else {
                        unlink("$dir/$item");
                    }
                }
            }
            closedir($handle);
        }
    }

}
