<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 26, 2014
 * Time:   10:50:57 AM
 */
class Portal_MenuItemController extends System_Controller_Action {

    //put your code here
    public function init() {
        $this->loadTemplates('portal');
    }

    public function indexAction() {
        $this->view->headTitle('Quản lý danh mục');
        $objMenuItem = new Portal_Model_MenuItem();
        $array = $objMenuItem->getListMenuItems();
        $adapter = new Zend_Paginator_Adapter_DbSelect($array);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(4);
        $current = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($current);
        $this->view->dataPaginator = $paginator;
        //if ($this->_request->isPost()) {
        $act = $this->_request->getParam('act');
        $id = $this->_request->getParam('id');
        $this->view->act = strip_tags($act);
        $this->view->id = strip_tags($id);
        $this->view->act = "";
        $this->view->action = $this->_request->getBaseUrl() . "/portal/menuitem/index/id/$id/act/add";
        if ($act == "get_infor_edit" & $id > 0) {
            $this->view->action = $this->_request->getBaseUrl() . "/portal/menuitem/index/id/$id/act/edit";
            $data = $objMenuItem->getMenuItemById($id);
            if (is_array($data)) {
                $this->view->name = $data[0]['NAME'];
                $this->view->url = $data[0]['URL'];
                $this->view->order = $data[0]['ORDERING'];
                $this->view->level = $data[0]['LEVEL'];
                $this->view->parent = $data[0]['PARENTS'];
                $this->view->active = ($data[0]['ENABLED'] == 1) ? TRUE : FALSE;
                $this->view->listMenuItems = $objMenuItem->getListMenuToArray();
            }
            $this->view->act = "edit";
        }
        // }
        if ($this->_request->isPost() && $act == "edit" && $id > 0) {
            $menuName = $this->_request->getPost('menuName');
            $url = $this->_request->getPost('url');
            $order = $this->_request->getPost('order');
            $active = $this->_request->getPost('active');
            $parent = $this->_request->getPost('listMenuItem');
            $level = $this->_request->getPost('level');
            $active = ($active) ? 1 : 0;
            $this->view->name = $menuName;
            $this->view->url = $url;
            $this->view->order = $order;
            $this->view->level = $level;
            $this->view->parent = $parent;
            $this->view->active = ($active == TRUE) ? "1" : "0";
            $empty = new Zend_Validate_NotEmpty();
            if ($empty->isValid($menuName)) {
                $data = array('NAME' => $menuName, 'URL' => $url, 'PARENTS' => $parent, 'LEVEL' => $level, 'ORDERING' => $order, 'ENABLED' => $active);
                $objMenuItem->updateInfor($data, "MENU_ID=" . $id);
                $this->view->successMessage = "Cập nhật thông tin danh mục thành công";
                $this->view->name = "";
                $this->view->url = "";
                $this->view->order = "";
                $this->view->level = "";
                $this->view->parent = "";
                $this->view->active = "0";
            } else {
                $this->view->errorMessage = "Vui lòng nhập tên danh mục";
                $this->view->act = "edit";
            }
            $this->view->id = 0;
        }
        if ($this->_request->isPost() && $act == "add") {
            $menuName = $this->_request->getPost('menuName');
            $url = $this->_request->getPost('url');
            $order = $this->_request->getPost('order');
            $active = $this->_request->getPost('active');
            $parent = $this->_request->getPost('listMenuItem');
            $level = $this->_request->getPost('level');
            $active = ($active) ? 1 : 0;
            $this->view->name = strip_tags($menuName);
            $this->view->url = strip_tags($url);
            $this->view->order = strip_tags($order);
            $this->view->level = strip_tags($level);
            $this->view->parent = strip_tags($parent);
            $this->view->active = ($active == TRUE) ? "1" : "0";
            $empty = new Zend_Validate_NotEmpty();
            if ($empty->isValid($menuName)) {
                $data = array('NAME' => $menuName, 'URL' => $url, 'PARENTS' => $parent, 'LEVEL' => $level, 'ORDERING' => $order, 'ENABLED' => $active);
                $objMenuItem->addMenuItem($data);
                $this->view->successMessage = "Thêm danh mục thành công";
                $this->view->name = "";
                $this->view->url = "";
                $this->view->order = "";
                $this->view->level = "";
                $this->view->parent = "";
                $this->view->active = "0";
            } else {
                $this->view->errorMessage = "Vui lòng nhập tên danh mục";
            }
            $this->view->id = 0;
        }
        if ($act == "delete") {
            $objMenuItem->deleteMenuItem($id);
            $this->view->successMessage = "Xóa danh mục thành công";
        }

        $this->view->listMenuItems = $objMenuItem->getListMenuToArray();
    }

}
