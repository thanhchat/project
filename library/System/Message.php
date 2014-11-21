<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of message
 *
 * @author ThanhChat
 */
class System_Message {

    //put your code here
    public function addMessage($arrayMess) {
        if (is_array($arrayMess)) {
            $str = "";
            for ($i = 0; $i < count($arrayMess); $i++) {
                $str.="<span>" . $arrayMess[$i] . "</span><br>";
            }
        }
        return $str;
    }

}
