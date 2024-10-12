<!-- Đăng ký tài khoản -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

$kq = getRows('SELECT * FROM users');

echo '<pre>';
print_r($kq);
echo '</pre>';


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
                <input type="fullname" name="" id="" placeholder="Họ tên" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="email" name="" id="" placeholder="Địa chỉ email" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input type="number" name="" id="" placeholder="Số điện thoại" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Mật khẩu</label>
                <input type="password" name="" id="" placeholder="Mật khẩu" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="" id="" placeholder="Nhập lại mật khẩu" class="form-control">
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