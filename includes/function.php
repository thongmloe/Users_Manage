<!-- Các hàm chung của dự án -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

function layouts($layoutName = 'header',$data=[]){
    if(file_exists(_WEB_PATH_TEMPLATES."/layout/$layoutName.php")){
        require_once (_WEB_PATH_TEMPLATES."/layout/header.php");
    }
}