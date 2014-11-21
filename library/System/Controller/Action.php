<?php

class System_Controller_Action extends Zend_Controller_Action {

    public function loadTemplates($section = "default") {
        $baseUrl = $this->_request->getBaseUrl();
        $filename = TEMPLATES_PATH . "/templates.ini";
        $configs = new Zend_Config_Ini($filename, $section);
        //file layout se duoc load
        $layout = $configs->layout;
        $templates_path = $configs->TemPath;
        $UrlTem = $baseUrl . $configs->UrlTem;
        $DirCss = $UrlTem . '/' . $configs->DirCss;
        $DirJs = $UrlTem . '/' . $configs->DirJs;
        $DirImg = $UrlTem . '/' . $configs->DirImg;
        $DirFlash = $UrlTem . '/' . $configs->DirFlash;
        $this->view->DirImg = $DirImg;
        $this->view->DirFlash = $DirFlash;
        $title = $configs->title;
        $this->view->headTitle($title);

        if (count($configs->metaHttp) > 0) {
            foreach ($configs->metaHttp as $value) {
                $value = explode("|", $value);
                $this->view->headMeta()->appendHttpEquiv($value[0], $value[1]);
            }
        }

        if (count($configs->metaName) > 0) {
            foreach ($configs->metaName as $value) {
                $value = explode("|", $value);
                $this->view->headMeta()->appendName($value[0], $value[1]);
            }
        }

        if (count($configs->FileCss) > 0) {
            foreach ($configs->FileCss as $value) {
                $this->view->headLink()->appendStylesheet($DirCss . '/' . $value);
            }
        }

        if (count($configs->FileJs) > 0) {
            foreach ($configs->FileJs as $value) {
                $this->view->headScript()->appendFile($DirJs . '/' . $value);
            }
        }

        $option = array("layoutpath" => $templates_path, "layout" => $layout);
        Zend_Layout::startMvc($option);
    }

}
