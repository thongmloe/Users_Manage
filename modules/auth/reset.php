<!-- Reset mật khẩu -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}


$title = [
    'titlePage' => 'Khôi phục mật khẩu'
];

layouts('header-login',$title);

// Kiểm tra đăng nhập
if(isLogin()){
    redirect('?module=home&action=dashboard');
}

$token = filter()['token'];
if(!empty($token)){
    // Truy vấn database kiểm tra token với database
    $tokenQuery = oneRaw("SELECT id,fullname,email FROM users WHERE forgotToken = '$token'");
    if(!empty($tokenQuery)){
        $userID = $tokenQuery['id'];
        if(isPost()){
            $filterAll = filter();
            $errors = []; //Mảng chứa các lỗi

            // Validate password: Bắt buộc phải nhập, lớn hơn 8 kí tự
            if(empty($filterAll['password'])){
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            }else{
                if(strlen($filterAll['password']) < 8){
                    $errors['password']['min-length'] = 'Mật khẩu phải từ 8 kí tự trở lên';
                }
            }
            // Validate password-confirm: Phải nhập và phải giống với bên trên
            if(empty($filterAll['password-confirm'])){
                $errors['password-confirm']['required'] = 'Bạn phải nhập lại mật khẩu';
            }else{
                if($filterAll['password-confirm'] != $filterAll['password']){
                    $errors['password-confirm']['match'] = 'Mật khẩu phải giống mật khẩu vừa nhập';
                }
            }

            if(empty($errors)){
                // Xử lý việc update mật khẩu
                $passwordHash = password_hash($filterAll['password'],PASSWORD_DEFAULT);
                $dataUpdate = [
                    'pass' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date("Y-m-d H:i:s")
                ];

                $updateStatus = update('users',$dataUpdate,"id=$userID");
                if($updateStatus){
                    setFlashData('msg','Thay đổi mật khẩu thành công!!!');
                    setFlashData('msg_type','success');  
                    redirect('?module=auth&action=login');
                }else{
                    setFlashData('msg','Lỗi hệ thống vui lòng thử lại sau!!!');
                    setFlashData('msg_type','danger');  
                }

            }
            else{
                setFlashData('msg','Vui lòng kiểm tra dữ liệu!!!');
                setFlashData('msg_type','danger');
                setFlashData('errors',$errors);
                redirect('?module=auth&action=reset&token='.$token);
            }
        }

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');

        ?>
        <!-- Form đặt lại mật khẩu -->
        <div class="row">
            <div class="col-4 center-form" >
                <h2 class="text-center text-uppercase fs-4">Khôi phục mật khẩu</h2>
                <?php
                    if(!empty($msg)){
                        getMsg($msg,$msg_type);
                    }
                ?>
                <form action="" method="post">
                <div class="form-group mg-form">
                        <label for="">Mật khẩu mới</label>
                        <input type="password" name="password" id="" placeholder="Mật khẩu" class="form-control">
                        <?php
                            echo formError('password',$errors,'','',null);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại mật khẩu</label>
                        <input type="password" name="password-confirm" id="" placeholder="Nhập lại mật khẩu" class="form-control">
                        <?php
                            echo formError('password-confirm',$errors,'','',null);
                        ?>
                    </div>
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <div class="btn-form">
                        <button type="submit" class="mg-btn btn btn-primary btn-block">Xác nhận</button>
                    </div>
                    <hr>
                    <div class="btn-form">
                    <a href="?module=auth&action=register" class="mg-btn btn btn-primary btn-block">Đăng Ký Tài Khoản</a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }else{
        getMsg('Liên kết không tồn tại hoặc đã hết hạn','danger');
    }
}else{
    getMsg('Liên kết không tồn tại hoặc đã hết hạn','danger');
}

?>

<?php
layouts('footer-login');
?>