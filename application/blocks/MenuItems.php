<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 18, 2014
 * Time:   3:52:36 PM
 */
class Block_MenuItems extends Zend_View_Helper_Abstract{
    public function MenuItems(){
        $dbcache = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('cachemanager')->getCache('dbcache');
        $id_cache_menu='cache_item_menu';
        if(!$dbcache->load($id_cache_menu)){
            $menuItems=new Default_Model_MenuItem;
            $data=$menuItems->getListMenuItem();
            $dbcache->save($data,$id_cache_menu);
        }
        $array=$dbcache->load($id_cache_menu);
        $uri = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri(); 
        $uri_cur=explode("/",$uri);
        $i=0;
        if(isset($uri_cur[2])){
           foreach($array as $key=> $value){
            $str=explode("/",$value["URL"]);
            if(isset($str[1])){
                if($str[1]==$uri_cur[2]){
                    $array[$key]["STATUS"]="active";
                    $i=1;
                    break;
                }else{
                    $array[$key]=$value;
                }
            }else{
               $array[$key]=$value; 
            }
           } 
        }
        if($i==1){
            foreach($array as $key=>$value){
                if($value["URL"]==""){
                    $array[$key]["STATUS"]="";
                    $i=0;
                    break;
                }
            }
        }
        return $array;
    }
}
?>