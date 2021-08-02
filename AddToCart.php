<?php session_start();
spl_autoload_register(function($class_name){
    include "admin/" . $class_name . ".php";
});

if(isset($_REQUEST['addToCart'])){
    $bikeId = (int) $_REQUEST['addToCart'];
    $_SESSION['cart'][] = $bikeId;
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
if(isset($_REQUEST['removeFromCart'])){
    $bikeId = (int) $_REQUEST['removeFromCart'];
    $bikeMan = new BikeProvider();
    $bike = $bikeMan->getBikeById($bikeId);
    foreach($_SESSION['cart'] as $id => $item){
        if($item == $bikeId){
            unset($_SESSION['cart'][$id]);
            break;
        }
    }
    $_SESSION['message'][] = ['title'=>'Giỏ hàng', 'status'=>'info' , 'content'=>'<div>Bỏ 01 <b>'. $bike->bikeName .'</b> khỏi giỏ hàng <span class="fas fa-cart-plus text-danger"></span> thành công</div>'];
}

