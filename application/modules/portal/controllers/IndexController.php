<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 18, 2014
 * Time:   8:38:57 PM
 */
class Portal_IndexController extends System_Controller_Action {

    public function indexAction() {
        $this->view->headTitle("Admin panel");
        $this->loadTemplates("portal");
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/public/templates/portal/includes/ckeditor/ckeditor.js');
        // if the form is submitted
        if ($this->_request->isPost()) {
            $editor1 = stripslashes($this->_request->getPost('editor1'));
            echo $editor1;
            exit();
        }
    }

}
