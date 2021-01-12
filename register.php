<?php
    spl_autoload_register(function($class_name){
        include "admin/" . $class_name . ".php";
    });
    $userAdmin = false;
    $user = null;
    $userProvider = new UserProvider();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng ký người dùng</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/all.min.css">
</head>
<style>
    @font-face {
        font-family: fontAwesome;
        src: url("webfonts/fa-solid-900.woff");
    }
    label{
        font-weight: bold;
    }
    .invalid-feedback:before{
        content: "\f071";
        font-family: fontAwesome;
        padding-right: 5px;
    }
</style>
<body>
<?php
// include composer autoload
include 'vendor/autoload.php';

// import the Intervention Image Manager Class
use Intervention\Image\ImageManager;

// create an image manager instance with favored driver
$manager = new ImageManager(array('driver' => 'imagick'));

?>
<?php include_once "layout/navbar.php" ?>
<?php
    $email = $password = $rePassword = $displayName = $image = "";
    $mes_email = $mes_password = $mes_rePassword = $mes_displayName = $mess_image = "";
    $dataOK = true;
    $showPass = false; $showPassIcon = "eye";
    $showRePass = false;

    if(isset($_REQUEST['btnRegister'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            function checkData($data){
                $data = htmlspecialchars($data);
                $data = trim($data);
                $data = stripslashes($data);
                return $data;
            }
            $email = $_POST['txtEmail'];
            $password = $_POST['txtPassword'];
            $rePassword = $_POST['txtRePassword'];
            $displayName = $_POST['txtDisplayName'];
            $image = $_FILES['txtImage'];

            //processing email
            $email = checkData($email);
            if(strlen($email) == 0){
                $mes_email = "Emai không thể để trống";
                $dataOK = false;
            }
            else{
                if(strlen($email) > 50){
                    $mes_email = "Email dài tối đa 50 ký tự";
                    $mes_email .= " Độ dài hiện tại là " . strlen($email) . " ký tự";
                    $dataOK = false;
                }
                else{
                    $email_validate = filter_var($email, FILTER_VALIDATE_EMAIL);
                    if(!$email_validate){
                        $mes_email = "Email không đúng định dạng yêu cầu";
                        $dataOK = false;
                    }
                    else{
                        $res = $userProvider->getUserByEmail($email);
                        if($res != false){
                            $mes_email = "Email này đã tồn tại. Hãy chọn email khác";
                            $dataOK = false;
                        }
                    }
                }
            }

            //processing password
            $password = checkData($password);
            if(strlen($password) == 0){
                $mes_password = "Mật khẩu không thể để trống";
                $dataOK = false;
            }
            else{
                if(strlen($password) < 8){
                    $mes_password = "Mật khẩu dài tối thiểu 8 ký tự";
                    $mes_password .= " Độ dài hiện tại là " . strlen($password) . " ký tự";
                    $dataOK = false;
                }
                else{
                    $rePassword = checkData($rePassword);
                    if(strlen($rePassword) == 0){
                        $mes_rePassword = "Vui lòng nhập lại mật khẩu";
                        $dataOK = false;
                    }
                    else{
                        if($rePassword !== $password){
                            $mes_rePassword = "Mật khẩu nhập lại không khớp";
                            $dataOK = false;
                        }
                    }
                }
            }

            $displayName = checkData($displayName);
            if(strlen($displayName) > 50){
                $mes_displayName = "Tên hiển thị dài tối đa 50 ký tự";
                $mes_displayName .= " Độ dài hiện tại là " . strlen($displayName) . " ký tự";
                $dataOK = false;
            }

            $image_type = basename($image['type']);
            if($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "bmp"){
                $mess_image = "Ảnh phải có định dạng jpg, jpeg, png hoặc bmp";
                $mess_image .= " Định dạng hiện tại là " . $image_type;
                $dataOK = false;
            }

            if($dataOK){
                $upload_stat = move_uploaded_file($image['tmp_name'], "storage/uploads/profile/" . basename($image['name']));
                // to finally create image instances
                $image = $manager->make($_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\profile\\" . basename($image['name']))->fit(1200);
                $image->save();

                $user = new User();
                $user->userEmail = $email;
                $user->userPassword = md5($password);
                $user->userDisplayName = $displayName;
                $user->userImage = "http://localhost:63342/Website/storage/uploads/profile/" . basename($image['name']);
                $res = $userProvider->addUser($user);
                header("location:http://localhost:63342/Website/HomePage.php");
            }

        }
    }
?>
    <div class="container pt-5">
        <div class="row pt-4">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light text-center">
                        <h3>ĐĂNG KÝ NGƯỜI DÙNG</h3>
                        <div>Nhập thông tin bên dưới để đăng ký</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtEmail">Email</label>
                                <input value="<?= $email ?>" placeholder="your@email.com" class="form-control" type="text" name="txtEmail" id="txtEmail">
                                <?php
                                    if(strlen($mes_email) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_email ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtPassword">Mật khẩu</label>
                                <div class="input-group">
                                    <input placeholder="0-25 ký tự" value="<?= $password ?>" class="form-control" type="password" name="txtPassword" id="txtPassword">
                                    <div class="input-group-prepend">
                                        <button type="button" id="btnViewPassword" name="btnViewPassword" data-placement="top" data-toggle="tooltip" title="Hiện mật khẩu" class="btn btn-outline-primary rounded-right">
                                            <span class="fa fa-eye">
                                        </button>
                                    </div>
                                </div>
                                <?php
                                    if(strlen($mes_password) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_password ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtRePassword">Nhập lại mật khẩu</label>
                                <div class="input-group">
                                    <input placeholder="0-25 ký tự" value="<?= $rePassword ?>" class="form-control" type="password" name="txtRePassword" id="txtRePassword">
                                    <div class="input-group-prepend">
                                        <button type="button" id="btnViewRePassword" name="btnViewRePassword" data-toggle="tooltip" title="Hiện mật khẩu" class="btn btn-outline-primary rounded-right"><span class="fa fa-eye"></span></button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_rePassword) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_rePassword ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtDisplayName">Tên hiển thị</label>
                                <input value="<?= $displayName ?>" class="form-control" type="text" name="txtDisplayName" id="txtDisplayName">
                                <?php
                                if(strlen($mes_displayName) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_displayName ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Ảnh đại diện</label>
                                <div class="custom-control custom-file">
                                    <input class="custom-file-input" type="file" name="txtImage" id="txtImage">
                                    <label id="file_label" for="txtImage" class="custom-file-label">Chọn file để upload</label>
                                </div>
                                <div class="row mx-auto w-50 pt-3">
                                    <img id="file_image" class="rounded-circle w-100" src="" alt="">
                                </div>
                                <?php
                                if(strlen($mess_image) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mess_image ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnRegister" name="btnRegister" class="btn btn-outline-primary">ĐĂNG KÝ</button>
                                    <a class="ml-auto align-self-center" href="login.php" class="btn-link">Đã có tài khoản? Đăng nhập</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(()=>{
        $("[data-toggle = 'tooltip']").tooltip();

        var showPassword = false;
        $('#btnViewPassword').on({
            'click' : ()=>{
                showPassword = !showPassword;
                if(showPassword){
                    document.getElementById('txtPassword').type = "text";
                    $('#btnViewPassword span').removeClass('fa-eye');
                    $('#btnViewPassword span').addClass('fa-eye-slash');
                    $('#btnViewPassword').tooltip('hide').attr('title', 'Ẩn mật khẩu').tooltip('_fixTitle').tooltip('show');
                }
                else{
                    document.getElementById('txtPassword').type = "password";
                    $('#btnViewPassword span').removeClass('fa-eye-slash');
                    $('#btnViewPassword span').addClass('fa-eye');
                    $('#btnViewPassword').tooltip('hide').attr('title', 'Hiện mật khẩu').tooltip('_fixTitle').tooltip('show');
                }
            },
        });

        var showRePassword = false;
        $('#btnViewRePassword').on({
            'click' : ()=>{
                showRePassword = !showRePassword;
                if(showRePassword){
                    document.getElementById('txtRePassword').type = "text";
                    $('#btnViewRePassword span').removeClass('fa-eye');
                    $('#btnViewRePassword span').addClass('fa-eye-slash');
                    $('#btnViewRePassword').tooltip('hide').attr('title', 'Ẩn mật khẩu').tooltip('_fixTitle').tooltip('show');
                }
                else{
                    document.getElementById('txtRePassword').type = "password";
                    $('#btnViewRePassword span').removeClass('fa-eye-slash');
                    $('#btnViewRePassword span').addClass('fa-eye');
                    $('#btnViewRePassword').attr('title', 'Hiện mật khẩu');
                    $('#btnViewRePassword').tooltip('hide').attr('title', 'Hiện mật khẩu').tooltip('_fixTitle').tooltip('show');
                }
            },
        });

        $('#txtImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#file_label').html(name);
                $('#file_image').attr("src", path);
                $('#file_image').addClass("img-thumbnail");
            },
        });


    });
</script>
</body>
</html>