<?php
if(!defined('_CODE')){
    die('Access denied...');   
}

layouts('header');

?>

<div class="row">
    <div class="col-4 center-form" >
        <h2 class="text-center text-uppercase fs-4">Đăng nhập quản lý users</h2>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="email" name="" id="" placeholder="Địa chỉ email" class="form-control">
            </div>
            <div class="form-group mg-form">
                <label for="">Mật Khẩu</label>
                <input type="password" name="" id="" placeholder="Mật khẩu" class="form-control">
            </div>
            <div class="btn-form">
                <button type="submit" class="mg-form btn btn-primary btn-block">Đăng Nhập</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <div class="btn-form">
            <a href="?module=auth&action=register" class="btn btn-primary btn-block">Đăng Ký</a>
            </div>
        </form>
    </div>
</div>

<?php
    layouts('footer')
?>