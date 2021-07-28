<?php session_start();
    spl_autoload_register(function($class_name){
       include_once $class_name . ".php";
    });
    include "check_admin.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Administration Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css"> <!--fontAwesome-->
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
    .sidebar-menu{
        display: block;
    }
    .sidebar{
        position: fixed;
        height: 100%;
        width: 50%;
        margin-left: -50%;
        transition: all 0.5s ease;
        background-color: var(--light);
    }
    .sidebar{
        opacity: 1;
        z-index: 1;
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
        font-weight: bold;
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
            display: none !important;
        }
        .sidebar{
            margin-left: 0%;
            width: 220px;
        }
        .main-container{
            margin-left: 220px;

        }
    }
</style>
<?php
    if(isset($_REQUEST['q'])){
        if($_REQUEST['q'] == "reset"){
            unset($_SESSION['user_action']);
            unset($_SESSION['bike_action']);
            unset($_SESSION['brand_action']);
            unset($_SESSION['type_action']);
            unset($_SESSION['post_action']);
        }
    }
    if(isset($_REQUEST['selected_tab'])){
        $_SESSION['selected_tab'] = $_REQUEST['selected_tab'];
    }
    if(!isset($_SESSION['selected_tab'])){
        $_SESSION['selected_tab'] = "user";
    }
    function formatPrice($price){
        $price = number_format($price, 0);
        return $price;
    }

?>
<body>
    <?php
        include "../layout/navbar.php";
        include "../inc/message.php";
        include "../inc/scrollToTop.php";
    ?>
    <div class="row" style="margin-top: 45px;">
        <div id="sidebar" class="sidebar border">
            <div class="btn-group-vertical w-100 pl-3 my-radio" style="row-gap: 0.5em;">
                <div class="pt-4"></div>
                <input type="radio" <?= $_SESSION['selected_tab'] == "user" ? "checked" : "" ?> name="menu1" id="user" value="user">
                <label for="user">
                    <span class="fas fa-user text-primary pr-2"></span>
                    <span class="text-uppercase">người dùng</span>
                </label>

                <input type="radio" <?= $_SESSION['selected_tab'] == "bike" ? "checked" : "" ?> name="menu1" id="bike" value="bike">
                <label for="bike">
                    <span class="fas fa-gift text-primary pr-2"></span>
                    <span class="text-uppercase">sản phẩm</span>
                </label>
                <input type="radio" <?= $_SESSION['selected_tab'] == "post" ? "checked" : "" ?> name="menu1" id="post" value="post">
                <label for="post">
                    <span class="far fa-newspaper text-primary pr-2"></span>
                    <span class="text-uppercase">bài đăng</span>
                </label>
                <input type="radio" <?= $_SESSION['selected_tab'] == "brand" ? "checked" : "" ?> name="menu1" id="brand" value="brand">
                <label for="brand">
                    <span class="fas fa-motorcycle text-primary pr-2"></span>
                    <span class="text-uppercase">Hãng sản xuất</span>
                </label>
                <input type="radio" <?= $_SESSION['selected_tab'] == "type" ? "checked" : "" ?> name="menu1" id="type" value="type">
                <label for="type">
                    <span class="fas fa-cogs text-primary pr-2"></span>
                    <span class="text-uppercase">Kiểu xe</span>
                </label>
                <input type="radio" <?= $_SESSION['selected_tab'] == "banner" ? "checked" : "" ?> name="menu1" id="banner" value="banner">
                <label for="banner">
                    <span class="fas fa-images text-primary pr-2"></span>
                    <span class="text-uppercase">Banner</span>
                </label>
            </div>
        </div>
        <div id="main-container" class="main-container">
            <div class="container-fluid pt-3">
                <div class="menu_option" id="user_manager" style='<?= $_SESSION["selected_tab"] == "user" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/UserManager.php";
                    ?>
                </div>
                <div class="menu_option" id="bike_manager" style='<?= $_SESSION["selected_tab"] == "bike" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/BikeManager.php";
                    ?>
                </div>
                <div class="menu_option" id="post_manager" style='<?= $_SESSION["selected_tab"] == "post" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/PostManager.php";
                    ?>
                </div>
                <div class="menu_option" id="brand_manager" style='<?= $_SESSION["selected_tab"] == "brand" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/BrandManager.php";
                    ?>
                </div>
                <div class="menu_option" id="type_manager" style='<?= $_SESSION["selected_tab"] == "type" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/TypeManager.php";
                    ?>
                </div>
                <div class="menu_option" id="banner_manager" style='<?= $_SESSION["selected_tab"] == "banner" ? "display: block" : "display: none" ?>'>
                    <?php
                        include "layout/BannerManager.php";
                    ?>
                </div>
                <div class="menu_option" id="user_add" style="display: none">
                    <?php
                        if(isset($_SESSION['user_action'])){
                            if($_SESSION['user_action'] == "add"){
                                include "layout/AddUser.php";
                                ?>

                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#user_add').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="user_edit" style="display: none">
                    <?php
                        include "layout/EditUser.php";
                        if(isset($_SESSION['user_action'])){
                            if($_SESSION['user_action'] == "edit"){ ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#user_edit').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="bike_add" style="display: none">
                    <?php
                        if(isset($_SESSION['bike_action'])){
                            if($_SESSION['bike_action'] == "add"){
                                include "layout/AddBike.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#bike_add').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="bike_edit" style="display: none">
                    <?php
                        if(isset($_SESSION['bike_action'])){
                            if($_SESSION['bike_action'] == "edit"){
                                include "layout/EditBike.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#bike_edit').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="brand_add" style="display: none">
                    <?php
                        if(isset($_SESSION['brand_action'])){
                            if($_SESSION['brand_action'] == "add"){
                                include "layout/AddBrand.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#brand_add').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="brand_edit" style="display: none">
                    <?php
                        if(isset($_SESSION['brand_action'])){
                            if($_SESSION['brand_action'] == "edit"){
                                include "layout/EditBrand.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#brand_edit').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="type_add" style="display: none">
                    <?php
                        if(isset($_SESSION['type_action'])){
                            if($_SESSION['type_action'] == "add"){
                                include "layout/AddType.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#type_add').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="type_edit" style="display: none">
                    <?php
                        if(isset($_SESSION['type_action'])){
                            if($_SESSION['type_action'] == "edit"){
                                include "layout/EditType.php"; ?>
                                <script>
                                    $(document).ready(()=>{
                                        $('.menu_option').css({'display' : "none"});
                                        $('#type_edit').css({'display':"block"});
                                    });
                                </script>
                            <?php }
                        }
                    ?>
                </div>
                <div class="menu_option" id="post_add" style="display: none">
                    <?php
                    if(isset($_SESSION['post_action'])){
                        if($_SESSION['post_action'] == "add"){
                            include "layout/AddPost.php"; ?>
                            <script>
                                $(document).ready(()=>{
                                    $('.menu_option').css({'display' : "none"});
                                    $('#post_add').css({'display':"block"});
                                });
                            </script>
                        <?php }
                    }
                    ?>
                </div>
                <div class="menu_option" id="post_edit" style="display: none">
                    <?php
                    if(isset($_SESSION['post_action'])){
                        if($_SESSION['post_action'] == "edit"){
                            include "layout/EditPost.php"; ?>
                            <script>
                                $(document).ready(()=>{
                                    $('.menu_option').css({'display' : "none"});
                                    $('#post_edit').css({'display':"block"});
                                });
                            </script>
                        <?php }
                    }
                    ?>
                </div>
                <div class="menu_option" id="banner_edit" style="display: none">
                    <?php
                    if(isset($_SESSION['banner_action'])){
                        if($_SESSION['banner_action'] == "edit"){
                            include "layout/EditBanner.php"; ?>
                            <script>
                                $(document).ready(()=>{
                                    $('.menu_option').css({'display' : "none"});
                                    $('#banner_edit').css({'display' : "block"});
                                });
                            </script>
                        <?php }
                    }
                    ?>
                </div>
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
            $('#user').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#user_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=user");
                        httpRequest.send();
                    }
                }
            });
            $('#bike').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#bike_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=bike");
                        httpRequest.send();
                    }
                }
            });
            $('#post').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#post_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=post");
                        httpRequest.send();
                    }
                }
            });
            $('#brand').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#brand_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=brand");
                        httpRequest.send();
                    }
                }
            });
            $('#type').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#type_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=type");
                        httpRequest.send();
                    }
                }
            });
            $('#banner').on({
                'change' : (e)=>{
                    if(e.target.checked){
                        $('.menu_option').css({'display' : "none"});
                        $('#banner_manager').css({'display':"block"});
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("post", "<?= $_SERVER['PHP_SELF'] ?>?q=reset&selected_tab=banner");
                        httpRequest.send();
                    }
                }
            });

            window.onresize = ()=>{
                if(window.innerWidth > 576){
                    $('.sidebar').css("margin-left", "0");
                    showSideMenu = true;
                }
                else{
                    showSideMenu = false;
                    $('.sidebar').css("margin-left", "-50%");
                }
            }

        });
    </script>
</body>
</html>