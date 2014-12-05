<?php

/**
 * @User:  ThanhChat
 * Date:   Dec 2, 2014
 * Time:   2:55:19 PM
 */
class System_Function {

//put your code here
    public function create_captcha($num_character) {
        $chuoi = "ABCDEFGHIJKLMNOPQRSTUVWYWZ0123456789";
        $i = 1;
        while ($i <= $num_character) {
            $vitri = mt_rand(0, 35);
            $giatri.=substr($chuoi, $vitri, 1);
            $i++;
        }
        return "vuon_len_" . strtolower($giatri);
    }

    public function createFolder($name, $url) {
        if ($name != "") {
            if (!is_dir($url . $name)) {
                if (!mkdir($url . $name, 0777, true))
                    return false;
            }else {
                return false;
            }
        }
        return true;
    }

    public function rmdirr($dirname) {//Xóa thư mục
        if (!file_exists($dirname)) {
            return false;
        }

        if (is_file($dirname)) {
            return unlink($dirname);
        }

        $dir = scandir($dirname);
        for ($i = 0; $i < count($dir); $i++) {
            if ($dir[$i] == "." || $dir[$i] == "..")
                continue;
            $f = $dir[$i];
            $this->rmdirr($dirname . "/" . $f);
        }
        return rmdir($dirname);
    }

    public function del_file($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname)) {
            return unlink($dirname);
        }
    }

    public function pareString($str) {
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

}
