<?php session_start();

spl_autoload_register(function($class_name){
   include $class_name . ".php";
});

if(isset($_REQUEST['banner_action'])){
    switch ($_REQUEST['banner_action']){
        case "edit":
            $_SESSION['banner_action'] = "edit";
            break;
        case "delete":
            $bannerMan = new BannerProvider();
            $bannerMan->deleteBanner();
            break;
    }
}