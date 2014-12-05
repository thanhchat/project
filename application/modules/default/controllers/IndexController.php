<?php
class IndexController extends System_Controller_Action
{
    public function init()
    {
        $this->view->headTitle("Trang chủ");
        $this->loadTemplates("default");
        $baseurl = $this->_request->getbaseurl();
        $this->view->doctype();
        $this->view->headMeta()->appendName("keyword","Zend Framework,Codeigniter,PHP Framework");
        $this->view->headMeta()->offsetSetName("1", "description","Khoa hoc PHP Framework tai QHOnline");      
        $this->view->headLink()->appendStylesheet($baseurl . "/public/templates/default/css/paging.css");
        $this->view->headscript()->appendFile($baseurl . "/public/templates/default/js/jquery.min.js","text/javascript");
        $this->view->headscript()->offsetSetFile("1", $baseurl . "/public/templates/default/js/paging.js","text/javascript");
    }
    public function indexAction()
    {
        $dbcache=$this->getInvokeArg('bootstrap')->getResource('cachemanager')->getCache('dbcache');
        $id='list_cache';
//        if(!$dbcache->load($id)){
//            $muser=new Default_Model_User;
//            $data=$muser->getCustomerLimit(20);
//            $dbcache->save($data,$id);     
//        }
//        $this->view->array=$dbcache->load($id);
        
       // $this->view->headScript()->appendFile($this->view->baseUrl().'/public/includes/ckeditor/ckeditor.js');
        // if the form is submitted
       // if ($this->_request->isPost()) {
        //	$editor1 = stripslashes($this->_request->getPost('editor1'));
        //	echo $editor1;
        //	exit();
       // }
    }
  
    public function pagingajaxAction(){
        if ($this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getparam('start');
            if(is_numeric($id)&&$id!=""){
                $start=$id;
                $idc='list_cache_'.$start;
                $dbcache=$this->getInvokeArg('bootstrap')->getResource('cachemanager')->getCache('dbcache');
                if(!$dbcache->load($idc)){
                    $muser=new Default_Model_User;
                    $array=$muser->getCustomer_Limit_start_end($start,10);
                    if(count($array)>0){
                        $dbcache->save($array,$idc);    
                    } 
                }
                $array=$dbcache->load($idc);
                   if(is_array($array)){
                    foreach($array as $rows){
                        echo "<tr>
                                <td>".$rows['id']."</td>
                                <td>".$rows['name']."</td>
                                <td>".$rows['address']."</td>
                                <td>".$rows['city']."</td>
                                <td>".$rows['state']."</td>
                                <td>".$rows['zip']."</td>
                        </tr>";
                    }
                }
            }
        }
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
}
?>