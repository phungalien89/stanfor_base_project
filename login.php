<?php session_start();
    spl_autoload_register(function($class_name){
        include_once "admin/" . $class_name . ".php";
    });

    $userAdmin = false;
    $user = null;

    function formatPrice($price){
        $price = number_format($price, 0);
        return $price;
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css"> <!--fontAwesome-->

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>
<style>
    label{
        font-weight: bold;
    }
</style>
<?php
    $email = $password = "";
    $mes_email = $mes_password = "";
    $dataOK = true;
    if(isset($_REQUEST['btnLogin'])){
        function checkData($data){
            $data = htmlspecialchars($data);
            $data = trim($data);
            $data = stripslashes($data);
            return $data;
        }
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $email = $_POST['txtEmail'];
            $password = $_POST['txtPassword'];

            $email = checkData($email);
            if(strlen($email) == 0){
                $mes_email = "Email không thể để trống";
                $dataOK = false;
            }
            else{
                $email_match = filter_var($email, FILTER_VALIDATE_EMAIL);
                if(!$email_match){
                    $mes_email = "Email không đúng định dạng yêu cầu";
                    $dataOK = false;
                }
            }

            $password = checkData($password);
            if(strlen($password) == 0){
                $mes_password = "Mật khẩu không thể để trống";
            }

            if($dataOK){
                $userProvider = new UserProvider();
                $user = $userProvider->getUserByEmail($email);
                if(!$user){
                    $mes_email = "Email không đúng. Nhấn nút bên dưới để đăng ký";
                }
                else{
                    if($user->userEmail == "phungalien89@gmail.com"){
                        if(md5($password) == $user->userPassword){
                            $_SESSION['is_userAdmin'] = "true";
                            $_SESSION['is_logged'] = "true";
                            $_SESSION['loggedId'] = $user->userId;
                            $_SESSION['message'][] = ['title'=>'Đăng nhập', 'status'=>'success', 'content'=>'Chào mừng <b>ADMIN</b> đã trở lại!'];
                            header("location: http://localhost:63342/Website/admin/AdminPage.php");
                        }
                        else{
                            $mes_password = "Mật khẩu không đúng";
                        }
                    }
                    else{
                        if(md5($password) !== $user->userPassword){
                            $mes_password = "Mật khẩu không đúng";
                        }
                        else{
                            $_SESSION['is_userAdmin'] = "false";
                            $_SESSION['is_logged'] = "true";
                            $_SESSION['loggedId'] = $user->userId;
                            $_SESSION['message'][] = ['title'=>'Đăng nhập', 'status'=>'success', 'content'=>'Xin chào <b>'. $user->userDisplayName .'</b>. Bạn đã đăng nhập thành công!'];
                            header("location:http://localhost:63342/Website/HomePage.php");
                        }
                    }

                }
            }
        }
    }
?>
<style>
    @font-face{
        font-family: fontAwesome;
        src: url("webfonts/fa-solid-900.woff");
    }
    .invalid-feedback:before{
        content: "\f071";
        font-family: fontAwesome;
        padding-right: 5px;
    }
</style>
<body>
<?php include "layout/navbar.php" ?>
<div class="container pt-5">
    <div class="row pt-4">
        <div class="col-sm-10 col-md-8 col-lg-6 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-light text-center">
                    <h3>ĐĂNG NHẬP</h3>
                    <div>Nhập thông tin bên dưới để đăng nhập</div>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="txtEmail">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fa fa-user"></span>
                                    </div>
                                </div>
                                <input class="form-control" type="text" name="txtEmail" id="txtEmail" value="<?= $email; ?>">
                            </div>
                            <?php
                                if(strlen($mes_email) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_email ?></div>
                                <?php }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="txtPassword">Mật khẩu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="fa fa-lock"></span>
                                    </div>
                                </div>
                                <input class="form-control" type="password" name="txtPassword" id="txtPassword" value="<?= $password ?>">
                                <div class="input-group-prepend">
                                    <button type="button" id="btnViewPassword" data-toggle="tooltip" title="Hiện mật khẩu" class="btn btn-primary rounded-right"><span class="fas fa-eye"></span></button>
                                </div>
                            </div>
                            <?php
                            if(strlen($mes_password) > 0){ ?>
                                <div class="invalid-feedback d-block"><?= $mes_password ?></div>
                            <?php }
                            ?>
                        </div>
                        <div class="form-group pt-3">
                            <div class="d-flex">
                                <button id="btnLogin" name="btnLogin" class="btn btn-outline-primary">ĐĂNG NHẬP</button>
                                <a class="ml-auto align-self-center" href="register.php" class="btn-link">Chưa có tài khoản? Đăng ký</a>
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
        $('[data-toggle="tooltip"]').tooltip();
        var showPassword = false;
        $('#btnViewPassword').on({
            "click" : ()=>{
                showPassword = !showPassword;
                if(showPassword){
                    document.getElementById("txtPassword").type = "text";
                    $('#btnViewPassword span').removeClass("fa-eye");
                    $('#btnViewPassword span').addClass("fa-eye-slash");
                    $('#btnViewPassword').tooltip('hide').attr('title', "Ẩn mật khẩu").tooltip('_fixTitle').tooltip('show');
                }
                else{
                    document.getElementById("txtPassword").type = "password";
                    $('#btnViewPassword span').removeClass("fa-eye-slash");
                    $('#btnViewPassword span').addClass("fa-eye");
                    $('#btnViewPassword').tooltip('hide').attr('title', "Hiện mật khẩu").tooltip('_fixTitle').tooltip('show');
                }
            },
        })

    });
</script>
</body>
</html>