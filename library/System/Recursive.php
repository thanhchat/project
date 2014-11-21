<?php 
    class System_Recursive{ 
        
       protected $_sourceArr; 
       public function __construct($sourceArr = null){ 
          $this->_sourceArr = $sourceArr; 
       } 
        
       public function buildRecursive($parents = 0){ 
          $resultArr = array(); 
          $level = array(); 
          //$this->recursive1($this->_sourceArr,$parents,$level,$resultArr,0); 
          $resultArr = $this->getCategory($this->_sourceArr,$parents); 
          return $resultArr; 
       } 
       public function getCategory($data, $prentsID= 0) { 
           $newArray = array(); 
           foreach($data as $value) { 
               
              if ($value['parents'] == $prentsID) { 
                 $arr['label']       = $value['name']; 
                 if($value['type']=='root'){ 
                    $arr['uri']    = "#";    
                 }else if($value['type']=='link'){ 
                    $arr['uri']    = $value['url']; 
                 } 
                 else{ 
                    $arr['module']      = $value['module']; 
                    $arr['controller']    = $value['controller']; 
                    $arr['action']       = $value['action']; 
                 } 
                  
                 $arr['pages']       = $this->getCategory($data, $value['id']); 
                $newArray[]       = $arr; 
                     
              } 
           } 
           return $newArray; 
       } 

        
    }