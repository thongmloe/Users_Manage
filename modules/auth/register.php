<!-- Đăng ký tài khoản -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}
 
if(isPost()){
    $filterAll = filter();
    $errors = []; //Mảng chứa các lỗi

    // Validate fullname: bắt buộc phải nhập và ít nhất 5 kí tự
    if(empty($filterAll['fullname'])){
        $errors['fullname']['required'] = 'Họ và tên bắt buộc phải nhập';
    }else{
        if(strlen($filterAll['fullname']) < 5){
            $errors['fullname']['min-length'] = 'Họ tên ít nhất phải có 5 ký tự';
        }
    }
    // Validate email: bắt buộc phải nhập , đúng định dạng email và kiểm tra email đã đăng kí chưa
    if(empty($filterAll['email'])){
        $errors['email']['required'] = 'Email bắt buộc phải nhập';
    }else{
        $email = $filterAll['email'];
        $sql = "SELECT id FROM users WHERE email = '$email'";
        if(getRows($sql) > 0){
            $errors['email']['unique'] = 'Email này đã tồn tại';       
        }
    }

    // Validate Số điện thoại: Bắt buộc phải nhập, có đúng định dạng không
    if(empty($filterAll['phone'])){
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    }else{
        if(!isPhone($filterAll['phone'])){
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ';
        }
    }


    echo '<pre>';
    print_r($errors);
    echo '</pre>';
}


$title = [
    'titlePage' => 'Đăng ký tài khoản'
];

layouts('header',$title);
?>

<div class="row">
    <div class="col-4 center-form" >
        <h2 class="text-center text-uppercase fs-4">Đăng ký tài khoản User</h2>
        <form action="" method="post">
        <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input type="fullname" name="fullname" id="" placeholder="Họ tên" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="email" name="email" id="" placeholder="Địa chỉ email" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input type="phone" name="phone" id="" placeholder="Số điện thoại" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" id="" placeholder="Mật khẩu" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirm" id="" placeholder="Nhập lại mật khẩu" class="form-control">
            </div>
            <div class="btn-form">
                <button type="submit" class="mg-btn btn btn-primary btn-block">Đăng Ký</button>
            </div>
            <hr>
            <div class="btn-form">
            <a href="?module=auth&action=login" class="mg-btn btn btn-primary btn-block">Đăng Nhập</a>
            </div>
        </form>
    </div>
</div>

<?php
    layouts('footer')
?>