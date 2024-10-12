<!-- Các hàm liên quan đến session hay cookie -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

// Hàm gán session 
function setSession($key,$value){
    return $_SESSION[$key] = $value;
}

// Hàm đọc  session

function getSession($key=''){
    if(empty($key)){
        return $_SESSION;
    }else{
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
}

// Hàm xóa session

function deleteSession($key=''){
    if(empty($key)){
        session_destroy();
        return true;
    }else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
}

// Hàm gán FlashData
function setFlashData($key,$value){
    $key = 'flash_' .$key;
    return setSession($key,$value);
}

// Hàm đọc FlashFata
function getFlashData($key){
    $key = 'flash_'.$key;
    $data = getSession($key);
    deleteSession($key);
    return $data;
}