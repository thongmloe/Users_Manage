<!-- Liệt kê tài khoản -->
<h1>List User</h1>
<?php
if(!defined('_CODE')){
    die('Access denied...');
}
$title = [
    'titlePage' => 'Danh sách người dùng'
];

layouts('header',$title);

// Kiểm tra trạng thái đăng nhập

if(!isLogin()){
    redirect('?module=auth&action=login');
}

// Lấy dữ liệu trong database hiển thị ra

$listUser = getRaw("SELECT * FROM users ORDER BY update_at");

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');


?>

<div class="container">
    <hr>
    <h2>Quản lý người dùng</h2>
    <p>
        <a href="?module=users&action=add" class="btn btn-success btn-sm">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
    </p>
    <?php
            if(!empty($msg)){
                getMsg($msg,$msg_type);
            }
        ?>
    <table class="table table-bordered">
        <thead>
            <th>STT</th>
            <th>Họ và tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th width = "5%">Sửa</th>
            <th width = "5%">Xóa</th>
        </thead>
        <tbody>
            
            <?php
            if(!empty($listUser)):
                $count = 0;
                foreach($listUser as $item):
                    $count++;   
            ?>
            <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $item['fullname']; ?></td>
            <td><?php echo $item['email']; ?></td>
            <td><?php echo $item['phone']; ?></td>
            <td><?php echo $item['status'] == 1 ? '<button class = "btn btn-success btn-sm">Đã kích hoạt</button>' : '<button class = "btn btn-danger btn-sm">Chưa kích hoạt</button>' ; ?></td>
            <td><a href="<?php echo _WEB_HOST; ?>?module=users&action=edit&id=<?php echo $item['id'];?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
            <td><a href="<?php echo _WEB_HOST; ?>?module=users&action=delete&id=<?php echo $item['id'];?>" onclick="return confirm('Bạn có chắc muốn xóa không?') " class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
            </tr>
            <?php
                endforeach;
            endif;
            ?>
        </tbody>
    </table>
</div>

<?php
layouts('footer');

?>