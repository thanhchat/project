/*
 Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */
        var str="http://localhost:8090/mien_que/public/templates/portal";
        CKEDITOR.editorConfig = function (config)
        {
            // Define changes to default configuration here. For example:
            // config.language = 'fr';
            // config.uiColor = '#AADC6E';
            config.filebrowserBrowseUrl = str+'/includes/ckfinder/ckfinder.html';
            config.filebrowserImageBrowseUrl = str+'/includes/ckfinder/ckfinder.html?type=Images';
            config.filebrowserFlashBrowseUrl = str+'/includes/ckfinder/ckfinder.html?type=Flash';
            config.filebrowserUploadUrl = str+'/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
            config.filebrowserImageUploadUrl = str+'/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
            config.filebrowserFlashUploadUrl = str+'/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
            config.filebrowserWindowWidth = 700;
            config.filebrowserWindowHeight = 600
        };
