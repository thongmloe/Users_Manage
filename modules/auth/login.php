<?php
if(!defined('_CODE')){
    die('Access denied...');   
}

$title = [
    'titlePage' => 'Đăng nhập tài khoản'
];

layouts('header-login',$title);

if(isLogin()){
    redirect('?module=home&action=dashboard');
}

if(isPost()){
    $filterAll = filter();
    if(!empty($filterAll['email']) && !empty($filterAll['password'])){
        // Lấy dữ liệu khách hàng nhập vào
        $email = $filterAll['email'];
        $password = $filterAll['password'];
        // Truy vấn dữ liệu thông tin user theo email
        $userQuery = oneRaw("SELECT pass, id FROM users WHERE email = '$email'");
        if(!empty($userQuery)){
            $passwordHash = $userQuery['pass'];
            $userId = $userQuery['id'];
            if(password_verify($password,$passwordHash)){
                // Tạo Logintoken
                $tokenLogin = sha1(uniqid().time());

                // Insert vào bảng loginToken trong database
                $dataInssert = [
                    'user_Id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date("Y-m-d H:i:s")
                ];

                $insertStatus = insert('logintoken',$dataInssert);
                if($insertStatus){
                    // Insert thành công
                    // Lưu cái loginToken vào session
                    setSession('loginToken',$tokenLogin);
                    redirect('?module=home&action=dashboard');
                }else{
                    setFlashData('msg','Không thể đăng nhập vui lòng thử lại sau');
                    setFlashData('msg_type','danger');
                }
            }
            else{
                setFlashData('msg','Mật khẩu không chính xác');
                setFlashData('msg_type','danger');
            }
        }else{
            setFlashData('msg','Email không tồn tại');
            setFlashData('msg_type','danger');
        }
    }else{
        setFlashData('msg','Vui lòng nhập tài khoản và mật khẩu');
        setFlashData('msg_type','danger');
    }   
    redirect('?module=auth&action=login');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');

?>

<div class="row">
    <div class="col-4 center-form" >
        <h2 class="text-center text-uppercase fs-4">Đăng nhập quản lý users</h2>
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
            <div class="form-group mg-form">
                <label for="">Mật Khẩu</label>
                <input type="password" name="password" id="" placeholder="Mật khẩu" class="form-control">
            </div>
            <div class="btn-form">
                <button type="submit" class="mg-btn btn btn-primary btn-block">Đăng Nhập</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <div class="btn-form">
            <a href="?module=auth&action=register" class="mg-btn btn btn-primary btn-block">Đăng Ký</a>
            </div>
        </form>
    </div>
</div>

<?php
    layouts('footer-login')
?>