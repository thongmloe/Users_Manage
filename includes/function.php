<!-- Các hàm chung của dự án -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Hàm gọi header và footer
function layouts($layoutName = 'header',$data=[]){
    if(file_exists(_WEB_PATH_TEMPLATES."/layout/$layoutName.php")){
        require_once (_WEB_PATH_TEMPLATES."/layout/$layoutName.php");
    }
}

// Hàm gửi mail
function sendMail($toEmail,$subject,$body){

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->CharSet = 'UTF-8';                                 //Chỉnh sửa thành tiếng việt
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hobadong7777777@gmail.com';                     //SMTP username
        $mail->Password   = 'ilktcgtdiqwlurbp';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        // Bảo mật SSL của phpmailer
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        //Recipients
        $mail->setFrom('hoanggvannthongg@gmail.com', 'ThongLoe');
        $mail->addAddress($toEmail);     //Add a recipient
    
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
    
        $sendMail = $mail->send();
        if($sendMail){
            return $sendMail;
        }

    } catch (Exception $e) {
        echo "Gửi thất bại: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức get
function isGet(){
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        return true;
    }
    return false;
}
function isPost(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        return true;
    }
    return false;
}

// Hàm filter lọc đầu vào

function filter(){
    $filterArr = [];
    // Phương thức Get
    if(isGet()){
        if(!empty($_GET)){
            foreach ($_GET as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                }else{
                    $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    // Xử lý dữ liệu đầu vào khi hiển thị ra phương thức Post
    if(isPost()){
        if(!empty($_POST)){
            foreach ($_POST as $key => $value){
                $key = strip_tags($key);
                if(is_array($value)){
                    $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                }else{
                    $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $filterArr;
}


// Hàm kiểm tra email

function isEmail($email){
    $checkEmail = filter_var($email,FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

// Hàm kiểm tra số nguyên INT

function isNumberInt($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_INT);
    return $checkNumber;
}

// Hàm kiểm tra số thực FLOAT
function isNumberFloat($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

// Hàm kiểm tra số điện thoại

function isPhone($phone){
    // Điều kiện 1: Kiểm tra số đầu tiên là số 0
    $checkZero = false;
    if($phone[0] == '0'){
        $checkZero = true;
        $phone = substr($phone,1);
    }
    // Điều kiện 2: Kiểm tra đầu số điện thoại ở VN: 03,05,07,08,09
    $checkNumBegin = false;
    $validStarts = ['3', '5', '7', '8', '9'];
    if (in_array($phone[0], $validStarts)) {
        $checkNumBegin = true;
    }
    // Điều kiện 3: Kiểm tra chuỗi số bên sau đủ 8 chữ số
    $checkNumber = false;
    if(isNumberInt($phone) && (strlen($phone) == 9)){
        $checkNumber = true;
    }
    if($checkNumber && $checkZero && $checkNumBegin){
        return true;
    }
    return false;
}

// Thông báo lỗi
function getMsg($Msg, $type = 'success'){
    echo '<div class = "alert alert-'.$type.'">';
    echo $Msg;
    echo '</div>';
}

// Hàm điều hướng để load lại trang
function redirect($path='index.php'){
    header("Location: $path");
    exit;
}

// Hàm thông báo lỗi
function formError($fileName,$errors,$beforeHtml='',$afterHtml='',$default){
    return (!empty($errors[$fileName])) ? '<span class="error">'.reset($errors[$fileName]).'</span>' : $default;
}
// Hàm hiển thị lại dữ liệu cũ
function old($fileName,$oldData,$default){
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}

// Hàm kiểm tra loginToken
function isLogin(){
    $checkLogin = false;
    if(getSession('loginToken')){
        $tokenLogin = getSession('loginToken');
        
        // Kiểm tra token có giống ở database không
        $queryToken = oneRaw("SELECT user_Id FROM logintoken WHERE token = '$tokenLogin'");
        if(!empty($queryToken)){
            $checkLogin = true;
        }
        else{
            deleteSession('loginToken');
        }
    }
    return $checkLogin;
}
