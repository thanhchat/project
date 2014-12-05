<?php

/**
 * @User:  ThanhChat
 * Date:   Nov 27, 2014
 * Time:   8:26:17 AM
 */
class Portal_AlbumController extends System_Controller_Action {

    //put your code here
    public function init() {
        //parent::init();
        $this->loadTemplates('portal');
    }

    public function indexAction() {
        $this->view->headTitle('Quản lý hình ảnh.');
        $objAlbum = new Portal_Model_Album;
        $listAlbum = $objAlbum->getListAllAblum();

        $adapter = new Zend_Paginator_Adapter_DbSelect($listAlbum);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(5);
        $paginator->setPageRange(4);
        $currentPage = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $this->view->dataPaginator = $paginator;
    }

    public function addAction() {
        $this->view->headTitle('Thêm Album hình ảnh');
        if ($this->_request->isPost()) {
            $empty = new Zend_Validate_NotEmpty();
            $albumName = $this->_request->getPost('album_name');
            $this->view->album_name = $albumName;
            $description = $this->_request->getPost('description');
            $this->view->description = $description;
            $contentDes = $this->_request->getPost('contentDes');
            $this->view->contentDes = $contentDes;
            $imageName = strip_tags($_FILES['image']['name']);
            $active = $this->_request->getPost('active');
            $active = ($active) ? 1 : 0;
            $this->view->active = $active;
            $tags = $this->_request->getPost('tags');
            $this->view->tags = $tags;
            $sources = $this->_request->getPost('sources');
            $this->view->sources = $sources;
            $menuItemId = $this->_request->getPost('listMenuItem');
            $image_folder = $this->_request->getPost('image_folder');
            $this->view->image_folder = $image_folder;
            $img = strip_tags($_FILES['image']['tmp_name']);
            if ($empty->isValid(trim($albumName))) {
                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                    if ($this->checkImage($imageName)) {
                        if ($empty->isValid(trim($image_folder))) {
                            $objFunction = new System_Function;
                            $image_folder = $objFunction->pareString($image_folder);
                            $auth = Zend_Auth::getInstance();
                            $i = 0;
                            if ($objFunction->createFolder($image_folder, "public/images/")) {
                                if ($objFunction->createFolder("medium", "public/images/" . $image_folder . "/") && $objFunction->createFolder("original", "public/images/" . $image_folder . "/")) {
                                    $i = 1;
                                }
                            } else {
                                $this->view->errorMessage = "Thư mục trùng hoặc tạo không thành công";
                            }
                            if ($i == 1) {
                                //header('Content-Type: image/jpeg'); 
//                            $i = strrpos($imageName, ".");
//                            $l = strlen($imageName) - $i;
//                            $ext = substr($imageName, $i + 1, $l);
                                $resize = new System_ResizeImageClass($img);
                                if ($_FILES['image']['size'] <= FILE_MAX) {
                                    if ($resize != "error") {
                                        $resize->resizeTo(300, 300);
                                        $resize->saveImage('public/images/' . $image_folder . "/medium/" . $imageName);
                                        $resize->resizeTo(500, 500);
                                        $resize->saveImage('public/images/' . $image_folder . "/original/" . $imageName);

                                        $array = array('MENU_ITEM_ID' => $menuItemId, 'ALBUM_TITLE' => $albumName,
                                            'ALBUM_DESCRIPT' => $description, 'ALBUM_CONTENT' => $contentDes, 'MEDIUM_IMAGE_URL' => $imageName,
                                            'ORIGINAL_IMAGE_URL' => $imageName, 'ALBUM_FOLDER_NAME' => $image_folder, 'CREATED_BY_USER' => $auth->getIdentity()->USER_LOGIN_ID,
                                            'IS_ACTIVE' => $active, 'VIEW' => 0, 'TAGS' => $tags, 'IMAGE_SOURCE' => $sources);
                                        $objAlbum = new Portal_Model_Album();
                                        $albumId = $objAlbum->addAlbum($array);
                                        if (trim($tags) != "") {
                                            $listTags = explode(';', $tags);
                                            if (is_array($listTags) && count($listTags) > 0) {
                                                $objTags = new Portal_Model_TagAlbum;
                                                $objTagsAlbumValue = new Portal_Model_TagAlbumValue();
                                                foreach ($listTags as $key => $value) {
                                                    $checkIsExist = $objTags->getTagsByName(trim($value));
                                                    $idTags = 0;
                                                    if (count($checkIsExist) > 0) {
                                                        $idTags = $checkIsExist[0]['ID_TAG'];
                                                    } else {
                                                        $arrayTagsName = array('TAG_NAME' => $value);
                                                        $idTags = $objTags->addTagsValue($arrayTagsName);
                                                    }

                                                    $arrayTagValue = array('ALBUM_ID' => $albumId, 'TAG_VALUE_ID' => $idTags);
                                                    $objTagsAlbumValue->addTagsAlbumValue($arrayTagValue);
                                                }
                                            }
                                        }
                                        $this->view->successMessage = "Thêm album thành công";
                                        $this->view->album_name = "";
                                        $this->view->description = "";
                                        $this->view->contentDes = "";
                                        $this->view->active = 0;
                                        $this->view->tags = "";
                                        $this->view->sources = "";
                                        $this->view->image_folder = "";
                                    } else {
                                        $this->view->errorMessage = "Upload bị lỗi hoặc file không đúng định dạng";
                                    }
                                } else {
                                    $this->view->errorMessage = "File hình chọn quá lớn";
                                }
                            }
                        } else {
                            $this->view->errorMessage = "Tên thư mục hình không được bỏ trống";
                        }
                    } else {
                        $this->view->errorMessage = "Hình không đúng định dạng";
                    }
                } else {
                    $this->view->errorMessage = "Vui lòng chọn hình để upload";
                }
            } else {
                $this->view->errorMessage = "Tên album không được bỏ trống";
            }
        }
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/public/templates/portal/includes/ckeditor/ckeditor.js');
        $objListItem = new Portal_Model_MenuItem();
        $this->view->listMenuItems = $objListItem->getListMenuToArray();
    }

    public function deleteAction() {
        $idAlbum = $this->getRequest()->getParams('id');

        $objFunction = new System_Function;
        $objAlbum = new Portal_Model_Album();
        $album = $objAlbum->getAlbumById($idAlbum['id']);
        if (isset($album[0]['ALBUM_FOLDER_NAME']) && !empty($album[0]['ALBUM_FOLDER_NAME'])) {
            $folder_name = $album[0]['ALBUM_FOLDER_NAME'];
            if (file_exists('public/images/' . $folder_name . "/medium")) {
                $objFunction->rmdirr('public/images/' . $folder_name . "/medium");
            }
            if (file_exists('public/images/' . $folder_name . "/original")) {
                $objFunction->rmdirr('public/images/' . $folder_name . "/original");
            }
            if (file_exists('public/images/' . $folder_name)) {
                $objFunction->rmdirr('public/images/' . $folder_name);
            }
        }
        $objTagsAlbumValue = new Portal_Model_TagAlbumValue();
        $objTagsAlbumValue->deleteTagAlbumValue($idAlbum['id']);
        $objAlbum->deleteAlbum($idAlbum['id']);
        $this->_redirect("/portal/album/");
        $this->getHelper("viewRenderer")->setNoRender();
    }

    public function editAction() {
        $this->view->headTitle('Sửa thông tin album');
        $id = $this->_request->getParam('id');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/public/templates/portal/includes/ckeditor/ckeditor.js');
        $this->view->action = $this->_request->getBaseUrl() . "/portal/album/edit/id/$id";
        $objAlbum = new Portal_Model_Album();
        $album = $objAlbum->getAlbumById($id);
        $this->view->imageView = $album[0]['MEDIUM_IMAGE_URL'];
        if ($this->_request->isPost()) {
            $empty = new Zend_Validate_NotEmpty();
            $albumName = $this->_request->getPost('album_name');
            $this->view->album_name = $albumName;
            $description = $this->_request->getPost('description');
            $this->view->description = $description;
            $contentDes = $this->_request->getPost('contentDes');
            $this->view->contentDes = $contentDes;
            $imageName = strip_tags($_FILES['image']['name']);
            $active = $this->_request->getPost('active');
            $active = ($active) ? 1 : 0;
            $this->view->active = $active;
            $tags = $this->_request->getPost('tags');
            $this->view->tags = $tags;
            $sources = $this->_request->getPost('sources');
            $this->view->sources = $sources;
            $menuItemId = $this->_request->getPost('listMenuItem');
            $image_folder = $album[0]['ALBUM_FOLDER_NAME'];
            $this->view->image_folder = $image_folder;
            $img = strip_tags($_FILES['image']['tmp_name']);
            if ($empty->isValid(trim($albumName))) {
                $i = 0;
                // if ($this->checkImage($imageName)) {
                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                    if ($this->checkImage($imageName)) {
                        $resize = new System_ResizeImageClass($img);
                        if ($_FILES['image']['size'] <= FILE_MAX) {
                            if ($resize != "error") {
                                $resize->resizeTo(300, 300);
                                $resize->saveImage('public/images/' . $image_folder . "/medium/" . $imageName);
                                $resize->resizeTo(500, 500);
                                $resize->saveImage('public/images/' . $image_folder . "/original/" . $imageName);
                                if (file_exists('public/images/' . $image_folder . "/medium/" . $album[0]['MEDIUM_IMAGE_URL'])) {
                                    unlink('public/images/' . $image_folder . "/medium/" . $album[0]['MEDIUM_IMAGE_URL']);
                                }
                                if (file_exists('public/images/' . $image_folder . "/original/" . $album[0]['ORIGINAL_IMAGE_URL'])) {
                                    unlink('public/images/' . $image_folder . "/original/" . $album[0]['ORIGINAL_IMAGE_URL']);
                                }
                                $i = 1;
                            } else {
                                $this->view->errorMessage = "Upload bị lỗi hoặc file không đúng định dạng";
                            }
                        } else {
                            $this->view->errorMessage = "File hình chọn quá lớn";
                        }
                    } else {
                        $this->view->errorMessage = "Hình không đúng định dạng";
                    }
                }
                $array = array();
                $timestamp = date("Y-m-d H:i:s");
                if ($i == 1) {
                    $array = array('MENU_ITEM_ID' => $menuItemId, 'ALBUM_TITLE' => $albumName,
                        'ALBUM_DESCRIPT' => $description, 'ALBUM_CONTENT' => $contentDes, 'MEDIUM_IMAGE_URL' => $imageName, 'LAST_UPDATE' => $timestamp,
                        'ORIGINAL_IMAGE_URL' => $imageName, 'ALBUM_FOLDER_NAME' => $image_folder,
                        'IS_ACTIVE' => $active, 'VIEW' => 0, 'TAGS' => $tags, 'IMAGE_SOURCE' => $sources);
                    $this->view->imageView = $imageName;
                } else {
                    $array = array('MENU_ITEM_ID' => $menuItemId, 'ALBUM_TITLE' => $albumName, 'LAST_UPDATE' => $timestamp,
                        'ALBUM_DESCRIPT' => $description, 'ALBUM_CONTENT' => $contentDes,
                        'IS_ACTIVE' => $active, 'TAGS' => $tags, 'IMAGE_SOURCE' => $sources);
                    $this->view->imageView = $album[0]['MEDIUM_IMAGE_URL'];
                }
                $objAlbum = new Portal_Model_Album();
                $objAlbum->editAlbum($array, "ALBUM_ID=" . $id);
                if (trim($tags) != "") {
                    $listTags = explode(';', $tags);
                    if (is_array($listTags) && count($listTags) > 0) {
                        $objTags = new Portal_Model_TagAlbum;
                        $objTagsAlbumValue = new Portal_Model_TagAlbumValue();
                        $objTagsAlbumValue->deleteTagAlbumValue($id);
                        foreach ($listTags as $key => $value) {
                            $checkIsExist = $objTags->getTagsByName(trim($value));
                            $idTags = 0;
                            if (count($checkIsExist) > 0) {
                                $idTags = $checkIsExist[0]['ID_TAG'];
                            } else {
                                $arrayTagsName = array('TAG_NAME' => $value);
                                $idTags = $objTags->addTagsValue($arrayTagsName);
                            }
                            $arrayTagValue = array('ALBUM_ID' => $id, 'TAG_VALUE_ID' => $idTags);
                            $objTagsAlbumValue->addTagsAlbumValue($arrayTagValue);
                        }
                    }
                } else {
                    $objTagsAlbumValue = new Portal_Model_TagAlbumValue();
                    $objTagsAlbumValue->deleteTagAlbumValue($id);
                }
                $this->view->successMessage = "Cập nhật thông tin thành công";
                $this->view->album_name = "";
                $this->view->description = "";
                $this->view->contentDes = "";
                $this->view->active = 0;
                $this->view->tags = "";
                $this->view->sources = "";
                $this->view->image_folder = "";
            } else {
                $this->view->errorMessage = "Thêm mới album không được bỏ trống";
            }
        }
        $album = $objAlbum->getAlbumById($id);
        $this->view->album_name = $album[0]['ALBUM_TITLE'];
        $this->view->description = $album[0]['ALBUM_DESCRIPT'];
        $this->view->contentDes = $album[0]['ALBUM_CONTENT'];
        $active = ($album[0]['IS_ACTIVE']) ? 1 : 0;
        $this->view->active = $active;
        $this->view->tags = $album[0]['TAGS'];
        $this->view->sources = $album[0]['IMAGE_SOURCE'];
        $this->view->menuItemId = $album[0]['MENU_ITEM_ID'];
        $image_folder = $album[0]['ALBUM_FOLDER_NAME'];
        $this->view->image_folder = $image_folder;
        $objListItem = new Portal_Model_MenuItem();
        $this->view->listMenuItems = $objListItem->getListMenuToArray();
    }

    public function uploadsAction() {
        $this->view->headTitle('Upload hình ảnh');
        $this->view->headScript()->appendFile($this->_request->getbaseurl() . '/public/templates/portal/js/jquery.form.js');
        $this->view->headScript()->offsetSetFile("1", $this->_request->getbaseurl() . '/public/templates/portal/js/das.js');
        $this->view->headLink()->appendStylesheet($this->_request->getbaseurl() . '/public/templates/portal/css/uploads.css');
        $idAlbum = $this->_request->getParam('id');
        $this->view->action = $this->_request->getBaseUrl() . "/portal/album/uploads/id/$idAlbum";
        $this->view->id = $idAlbum;
        $objAlbum = new Portal_Model_Album;
        $this->view->listAlbum = $objAlbum->getListAblum();
        $messError=array();
        $messSucc=array();
        if ($this->_request->isPost() && $idAlbum > 0) {
            if (isset($_FILES['file_uploads_with_title']) && !empty($_FILES["file_uploads_with_title"]['name'][0])) {
                $userSession = Zend_Auth::getInstance();
                $objImageValue = new Portal_Model_ImageAlbum;
                $idUser = $userSession->getIdentity()->USER_LOGIN_ID;
                $valueName = $this->_request->getPost('listAlbumItem');
                $nameFolder = split('@@_', $valueName);
                $no_files = count($_FILES["file_uploads_with_title"]['name']);
                for ($i = 0; $i < $no_files; $i++) {
                    if ($_FILES["file_uploads_with_title"]["error"][$i] > 0) {
                        $messError[] = "Lỗi : " . $_FILES["file_uploads_with_title"]["name"][$i] . "<br>";
                    } else {
                        if ($this->checkImage($_FILES["file_uploads_with_title"]["name"][$i])) {
                            $img = strip_tags($_FILES["file_uploads_with_title"]["tmp_name"][$i]);
                            $imageName = $_FILES["file_uploads_with_title"]["name"][$i];
                            if ($_FILES['file_uploads_with_title']['size'][$i] <= FILE_MAX) {
                                $resize = new System_ResizeImageClass($img);
                                if ($resize != "error") {
                                    $resize->resizeTo(75, 75, 'exact');
                                    $resize->saveImage('public/images/' . $nameFolder[0] . "/medium/" . $imageName);
                                    $resize->resizeTo(502, 502);
                                    $resize->saveImage('public/images/' . $nameFolder[0] . "/original/" . $imageName);
                                    $title = $this->_request->getPost('title' . ($i + 1));
                                    $active = ($this->_request->getPost('chk' . ($i + 1))) ? 1 : 0;
                                    $data = array('ALBUM_ID' => $nameFolder[1], 'DESCRIPT' => $title, 'SMALL_IMAGE_URL' => $imageName, 'ORIGINAL_IMAGE_URL' => $imageName, 'IS_ACTIVE' => $active, 'CREATED_BY_USER' => $idUser);
                                    $objImageValue->addImage($data);
                                    $messSucc[] = "Success : " . $_FILES["file_uploads_with_title"]["name"][$i] . "<br>";
                                } else {
                                    $messError[] = "Lỗi upoad file : " . $_FILES["file_uploads_with_title"]["name"][$i];
                                }
                            } else {
                                $messError[] = "File : " . $_FILES["file_uploads_with_title"]["name"][$i] . " quá lớn";
                            }
                        } else {
                            $messError[] = "File : " . $_FILES["file_uploads_with_title"]["name"][$i] . " không đúng định dạng.";
                        }
                    }
                    $_FILES["file_uploads_with_title"]["name"][$i] = "";
                }
            } 
            if (isset($_FILES['file_uploads_not_title']) && !empty($_FILES["file_uploads_not_title"]['name'][0])) {
                $userSession = Zend_Auth::getInstance();
                $objImageValue = new Portal_Model_ImageAlbum;
                $idUser = $userSession->getIdentity()->USER_LOGIN_ID;
                $valueName = $this->_request->getPost('listAlbumItem');
                $nameFolder = split('@@_', $valueName);
                foreach ($_FILES['file_uploads_not_title']['name'] as $f => $name) {
                    if ($_FILES["file_uploads_not_title"]["error"][$f] == 4) {
                        $messError[] = "Lỗi : " . $_FILES["file_uploads_not_title"]["name"][$f] . "<br>";
                    } else {
                        $img = strip_tags($_FILES["file_uploads_not_title"]["tmp_name"][$f]);
                        $imageName = $_FILES["file_uploads_not_title"]["name"][$f];
                        if ($this->checkImage($imageName)) {
                            if ($_FILES['file_uploads_not_title']['size'][$f] <= FILE_MAX) {
                                $resize = new System_ResizeImageClass($img);
                                if ($resize != "error") {
                                    $resize->resizeTo(75, 75, 'exact');
                                    $resize->saveImage('public/images/' . $nameFolder[0] . "/medium/" . $imageName);
                                    $resize->resizeTo(502, 502);
                                    $resize->saveImage('public/images/' . $nameFolder[0] . "/original/" . $imageName);
                                    $active = 0;
                                    $data = array('ALBUM_ID' => $nameFolder[1], 'SMALL_IMAGE_URL' => $imageName, 'ORIGINAL_IMAGE_URL' => $imageName, 'IS_ACTIVE' => $active, 'CREATED_BY_USER' => $idUser);
                                    $objImageValue->addImage($data);
                                    $messSucc[] = "Success : " . $_FILES["file_uploads_not_title"]["name"][$f] . "<br>";
                                } else {
                                    $messError[] = "Lỗi upoad file : " . $_FILES["file_uploads_not_title"]["name"][$f];
                                }
                            } else {
                                $messError[] = "File : " . $_FILES["file_uploads_not_title"]["name"][$f] . " quá lớn";
                            }
                        }else{
                            $messError[] = "File : " . $_FILES["file_uploads_not_title"]["name"][$f] . " không đúng định dạng.";
                        }
                    }
                    $_FILES["file_uploads_not_title"]["name"][$f] = "";
                }
            } 
            if (isset($messSucc) && count($messSucc) > 0) {
                $objMess = new System_Message;
                $this->view->successMessage = $objMess->addMessage($messSucc);
            }
            if (isset($messError) && count($messError) > 0) {
                $objMess = new System_Message;
                $this->view->errorMessage = $objMess->addMessage($messError);
            }
        }
    }

    public function checkImage($image) {
        $valid_formats = array("jpg", "JPG", "png", "PNG", "gif", "GIF", "jpeg", "JPEG", "bmp", "BMP");
        if (!in_array(pathinfo($image, PATHINFO_EXTENSION), $valid_formats))
            return FALSE;
        else
            return TRUE;
    }

}
