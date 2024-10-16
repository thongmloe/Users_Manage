<?php
if(!defined('_CODE')){
    die('Access denied...');
}
$title = [
    'titlePage' => 'Trang Dashboard'
];

layouts('header',$title);

// echo getSession('loginToken');

if(!isLogin()){
    redirect('?module=auth&action=login');
}

?>
<h1>Dashboard</h1>
<?php
layouts('footer');

?>