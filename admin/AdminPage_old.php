<?php session_start();
    spl_autoload_register(function($class_name){
       include_once $class_name . ".php";
    });
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration Page</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>
<style>
    *{
        box-sizing: border-box;
    }
    :root{
        --blue: #007bff;
        --indigo: #6610f2;
        --purple: #6f42c1;
        --pink: #e83e8c;
        --red: #dc3545;
        --orange: #fd7e14;
        --yellow: #ffc107;
        --green: #28a745;
        --teal: #20c997;
        --cyan: #17a2b8;
        --white: #fff;
        --gray: #6c757d;
        --gray-dark: #343a40;
        --primary: #007bff;
        --secondary: #6c757d;
        --success: #28a745;
        --info: #17a2b8;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #343a40;
        --theme: var(--primary);
    }
    @font-face{
        font-family: fontAwesome;
        src: url("../webfonts/fa-solid-900.woff");
    }
    .hover-shadow-outline{
        transition: all 0.15s ease;
    }
    .hover-shadow-outline:hover{
        box-shadow: 0px 0px 0px 3px var(--light);
    }
    .sidebar{
        position: fixed;
        height: 100%;
        width: 50%;
        margin-left: -50%;
        transition: all 0.5s ease;
        background-color: var(--light);
    }
    .sidebar-menu{
        opacity: 1;
    }
    .main-container{
        width: 100%;
    }
    .btn-outline-primary{
        border: none;
    }
    ul.my-radio{
        list-style-type: none;
    }
    .my-radio input{
        display: none;
    }
    .my-radio label{
        padding: 10px;
        width: 100%;
        transition: all 0.15s ease;
        position: relative;
    }
    .my-radio label:hover{
        background-color: var(--primary);
        color: var(--light);
    }
    .my-radio label:hover span{
        color: var(--light) !important;
    }
    .my-radio input:checked + label{
        background-color: var(--primary);
        color: var(--light);
    }
    .my-radio input:checked + label span{
        color: var(--light) !important;
    }
    .my-radio input:checked + label:after{
        content: '\f0da';
        font-family: fontAwesome;
        color: var(--light);
        font-size: 1.5em;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 5%;
    }
    @media (min-width: 576px) {
        .sidebar-menu{
            opacity: 0;
        }
        .sidebar{
            margin-left: 0%;
            width: 200px;
        }
        .main-container{
            margin-left: 200px;

        }
    }
</style>
<?php
    $userImage = "../storage/uploads/profile/user1.jpeg";
    $userName = "Admin";

    $page = "layout/UserManager.php";
    $opt = "user";
    if(isset($_REQUEST['menu1'])){
        $val = $_POST['menu1'];
        $opt = $val;
        switch($val){
            case 'user':
                $page = "layout/UserManager.php";
                break;
            case 'product':
                if(isset($_REQUEST['user_action'])){
                    $opt = "product";
                    header("location:AdminPage.php");
                }
                $page = "layout/BikeManager.php";
                break;
            case 'post':
                if(isset($_REQUEST['user_action'])){
                    $opt = "post";
                    header("location:AdminPage.php");
                }
                $page = "layout/PostManager.php";
                break;
            default: break;
        }
    }
    if(isset($_REQUEST['user_action'])){
        if($_REQUEST['user_action'] == "add"){
            $page = "layout/AddUser.php";
        }
        if($_REQUEST['user_action'] == "edit"){
            $page = "layout/EditUser.php";
        }
    }
?>
<body>
    <?php include_once "../layout/navbar.php"?>
    <div class="row" style="margin-top: 50px;">
        <div id="sidebar" class="sidebar border">
            <div class="btn-group-vertical w-100 pl-3 my-radio" style="row-gap: 0.5em;">
                <form action="" method="post">
                    <div class="pt-4"></div>
                    <input onchange="this.form.submit()" type="radio" <?= $opt=="user" ? "checked" : "" ?> name="menu1" id="user" value="user">
                    <label for="user">
                        <span class="fas fa-user text-primary pr-2"></span>
                        <span class="text-uppercase">người dùng</span>
                    </label>

                    <input onchange="this.form.submit()" type="radio" <?= $opt=="product" ? "checked" : "" ?> name="menu1" id="product" value="product">
                    <label for="product">
                        <span class="fas fa-gift text-primary pr-2"></span>
                        <span class="text-uppercase">sản phẩm</span>
                    </label>
                    <input onchange="this.form.submit()" type="radio" <?= $opt=="post" ? "checked" : "" ?> name="menu1" id="post" value="post">
                    <label for="post">
                        <span class="far fa-newspaper text-primary pr-2"></span>
                        <span class="text-uppercase">bài đăng</span>
                    </label>
                </form>
            </div>
        </div>
        <div id="main-container" class="main-container">
            <div class="container-fluid pt-3">
                <?php include_once $page ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(()=>{
            var showSideMenu = false;
            $('#btnSideMenu').on({
                'click' : ()=>{
                    showSideMenu = !showSideMenu;
                    if(showSideMenu){
                        $('#sidebar').css({"margin-left":"0"});
                    }
                    else{
                        $('#sidebar').css({"margin-left" : "-50%"});
                    }
                },
            });
        });
    </script>
</body>
</html>
