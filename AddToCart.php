<?php session_start();
spl_autoload_register(function($class_name){
    include "admin/" . $class_name . ".php";
});

if(isset($_REQUEST['addToCart'])){
    $bikeId = (int) $_REQUEST['addToCart'];
    if(isset($_SESSION['cart'])){
        $avai = false;
        foreach($_SESSION['cart'] as $cart){
            if($cart == $bikeId){
                $avai = true;
                break;
            }
        }
        if(!$avai){
            $_SESSION['cart'][] = $bikeId;
        }
    }
    else{
        $_SESSION['cart'][] = $bikeId;
    }
    $bikeMan = new BikeProvider();
    $bike = $bikeMan->getBikeById($bikeId);
    $_SESSION['message'][] = ['title'=>'Giỏ hàng', 'status'=>'success' , 'content'=>'<div>Thêm <b>'. $bike->bikeName .'</b> vào giỏ hàng <span class="fas fa-cart-plus text-danger"></span> thành công</div>'];
}

if(isset($_REQUEST['selection'])){
    $arr = explode("|", $_REQUEST['selection']);
    foreach($arr as $q){
        $_SESSION[$q] = "OK";
    }
}

