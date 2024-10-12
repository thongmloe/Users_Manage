<!-- Kết nối database -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}


try{
    if(class_exists('PDO')){

        $dsn = 'mysql:dbname='._DB.';host='._HOST;

        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Tạo thông báo ngoại lệ khi gặp lỗi
        ];

        $conn = new PDO($dsn,_USER,_PASS,$options);
    }
}catch(Exception $exp){
    echo '<div class = "error">';
    echo $exp ->getMessage().'<br>';
    echo '</div>';
    die();
}

