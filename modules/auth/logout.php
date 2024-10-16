<!-- Đăng xuất tài khoản -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

if(isLogin()){
    $token = getSession('loginToken');
    delete('loginToken' , "token='$token'");
    deleteSession('loginToken');
    redirect('?module=auth&action=login');
}