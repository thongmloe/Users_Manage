<?php
session_start();
require_once('config.php');
require_once('./includes/function.php');


// Set mặc định cho module và action
$module = _MODULE;
$action = _ACTION;



if(!empty($_GET['module'])){
    if(is_string($_GET['module'])){
        $module = trim(($_GET['module']));
    }
}
if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim(($_GET['action']));
    }
}

// echo $module.'<br>';
// echo $action;

// Để đường dẫn chuyển hướng đến trang

$path = 'modules/'.$module.'/'.$action.'.php';

if(file_exists($path)){
    require_once($path);
}else{
    require_once('modules/error/404.php');
}