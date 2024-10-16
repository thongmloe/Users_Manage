<!-- Thêm tài khoản -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

$title = [
    'titlePage' => 'Thêm danh sách người dùng'
];

layouts('header',$title);

// Kiểm tra trạng thái đăng nhập

if(!isLogin()){
    redirect('?module=auth&action=login');
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

    // Validate Số điện thoại: Bắt buộc phải nhập, có đúng định dạng không, đã có người sử dụng để đăng ký
    if(empty($filterAll['phone'])){
        $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
    }else{
        if(!isPhone($filterAll['phone'])){
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ';
        }else{
            $phone = $filterAll['phone'];
            $sql = "SELECT id FROM users WHERE phone = '$phone'";
            if(getRows($sql) > 0){
                $errors['phone']['unique'] = 'Số điện thoại này đã có người đăng ký';       
            }
        }
    }
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

    // Xử lý dữ liệu vào database
    if(empty($errors)){
        // Thông báo và insert vào database
        $dataInsert = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'pass' => password_hash($filterAll['password'],PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users',$dataInsert);
        if($insertStatus){
            setFlashData('msg','Tạo tài khoản thành công');
            setFlashData('msg_type','success');
            redirect('?module=users&action=list');
        }
        else{
            setFlashData('msg','Tạo tài khoản không thành công');
            setFlashData('msg_type','danger');
            redirect('?module=users&action=add');
        }

    }
    else{
        setFlashData('msg','Vui lòng kiểm tra dữ liệu!!!');
        setFlashData('msg_type','danger');
        setFlashData('errors',$errors);
        setFlashData('old',$filterAll);
        redirect('?module=users&action=add');
    }
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old'); 


?>

<div class="container">
    <div class="row center-form" >
        <h2 class="text-center text-uppercase fs-4">Thêm người dùng</h2>
        <?php
            if(!empty($msg)){
                getMsg($msg,$msg_type);
            }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Họ tên</label>
                        <input type="fullname" name="fullname" id="" placeholder="Họ tên" class="form-control" value="<?php echo old('fullname',$old,null); ?>">
                        <?php
                            echo formError('fullname',$errors,'','',null);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input type="email" name="email" id="" placeholder="Địa chỉ email" class="form-control" value="<?php echo old('email',$old,null);?>">
                        <?php
                            echo formError('email',$errors,'','',null);
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số điện thoại</label>
                        <input type="phone" name="phone" id="" placeholder="Số điện thoại" class="form-control" value="<?php echo old('phone',$old,null); ?>">
                        <?php
                            echo formError('phone',$errors,'','',null);
                        ?>
                    </div>
                </div>
                <div class="col"> 
                    <div class="form-group mg-form">
                        <label for="">Mật khẩu</label>
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
                    <div class="form-control">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-control">
                            <option value="0" <?php echo (old('status',$old,null) == 0)  ? 'selected': false; ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo (old('status',$old,null) == 1)  ? 'selected': false; ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="btn-form">
            <button type="submit" class="btn-add btn btn-primary btn-block">Xác nhận</button>
            <a href="?module=users&action=list" class="btn-add btn btn-success btn-block">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php
layouts('footer');

?>