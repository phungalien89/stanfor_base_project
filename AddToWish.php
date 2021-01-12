<?php session_start();
spl_autoload_register(function($class_name){
   include "admin/" . $class_name . ".php";
});

if(isset($_REQUEST['addToWish'])){
    $bikeId = (int) $_REQUEST['addToWish'];
    if(isset($_SESSION['wish'])){
        $avai = false;
        foreach($_SESSION['wish'] as $wish){
            if($wish == $bikeId){
                $avai = true;
                break;
            }
        }
        if(!$avai){
            $_SESSION['wish'][] = $bikeId;
        }
    }
    else{
        $_SESSION['wish'][] = $bikeId;
    }
    $bikeMan = new BikeProvider();
    $bike = $bikeMan->getBikeById($bikeId);
    $_SESSION['message'][] = ['title'=>'Yêu thích', 'status'=>'success' , 'content'=>'<div>Thêm <b>'. $bike->bikeName .'</b> vào yêu thích <span class="fas fa-heart text-danger"></span> thành công</div>'];
}
if(isset($_REQUEST['selection'])){
    $arr = explode("|", $_REQUEST['selection']);
    foreach($arr as $q){
        $_SESSION[$q] = "OK";
    }
}

