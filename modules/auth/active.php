<!-- Kích hoạt tài khoản -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

$title = [
    'titlePage' => 'Kích hoạt tài khoản'
];

layouts('header-login',$title);

// Lấy token 
$token = filter()['token'];
if(!empty($token)){
    // Truy vấn để kiểm tra token với database
    $tokenQuery = oneRaw("SELECT id FROM users WHERE activeToken = '$token'");
    if(!empty($tokenQuery)){
        $userID = $tokenQuery['id'];
        $dataUpdate =[
            'status' => 1,
            'activeToken' => null
        ];

        $updateStatus = update('users',$dataUpdate,"id=$userID");

        if($updateStatus){
            setFlashData('msg','Kích hoạt tài khoản thành công bạn có thể đăng nhập ngay bây giờ');
            setFlashData('msg_type','success');
        }else{
            setFlashData('msg','Kích hoạt tài khoản không thành công vui lòng liên hệ hỗ trợ');
            setFlashData('msg_type','danger');
        }
        redirect('?module=auth&action=login');

    }else{
        getMsg('Liên kết không tồn tại hoặc đã hết hạn','danger');
    }
}else{
    getMsg('Liên kết không tồn tại hoặc đã hết hạn','danger');
}

?>

<h1>ACTIVE</h1>

<?php
layouts('footer-login');    
?>