<!-- Quên mật khẩu -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

$title = [
    'titlePage' => 'Quên mật khẩu'
];

layouts('header-login',$title);

// Kiểm tra đăng nhập
if(isLogin()){
    redirect('?module=home&action=dashboard');
}
if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email'])){
        // Lấy dữ liệu nhập vào
        $email = $filterAll['email'];
        // Truy vấn dữ liệu thông tin user theo email
        $userQuery = oneRaw("SELECT id FROM users WHERE email = '$email'");
        if(!empty($userQuery)){
            $userId = $userQuery['id'];
            // Tạo forgot token
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];
            // Thêm token vào database
            $updateStatus = update('users',$dataUpdate,"id=$userId");
            if($updateStatus){
                // Thiết lập link forgot
                $linkForgot = _WEB_HOST .'/?module=auth&action=reset&token='.$forgotToken;
                // Thiết lập gửi email
                $subject = 'Lấy lại mật khẩu của bạn';
                $content = 'Vui lòng click vào đường link dưới đây để khôi phục mật khẩu <br>';
                $content .= $linkForgot.'<br>';
                // Tiến hành gửi Email
                $sendMail = sendMail($email,$subject,$content);
                if($sendMail){
                    setFlashData('msg','Vui lòng xem Email để xem hướng dẫn đặt lại mật khẩu !!!');
                    setFlashData('msg_type','success');
                }else{
                    setFlashData('msg','Hệ thống đang gặp sự cố vui lòng thử lại sau !!!');
                    setFlashData('msg_type','danger');
                }

            }else{
                setFlashData('msg','Hệ thống bị lỗi vui lòng thử lại sau');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Địa chỉ Email không tồn tại trong hệ thống');
            setFlashData('msg_type','danger');
        }
    }else{
        setFlashData('msg','Vui lòng nhập Email');
        setFlashData('msg_type','danger');
    }
    redirect('?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

?>

<div class="row">
    <div class="col-4 center-form" >
        <h2 class="text-center text-uppercase fs-4">Quên mật khẩu</h2>
        <?php
            if(!empty($msg)){
                getMsg($msg,$msg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="email" name="email" id="" placeholder="Địa chỉ email" class="form-control">
            </div>
            <div class="btn-form">
                <button type="submit" class="mg-btn btn btn-primary btn-block">Gửi</button>
            </div>
            <hr>
            <div class="btn-form">
            <a href="?module=auth&action=login" class="mg-btn btn btn-primary btn-block">Đăng Nhập</a>
            </div>
            <div class="btn-form">
            <a href="?module=auth&action=register" class="mg-btn btn btn-primary btn-block">Đăng Ký Tài Khoản</a>
            </div>
        </form>
    </div>
</div>

<?php
layouts('footer-login');
?>