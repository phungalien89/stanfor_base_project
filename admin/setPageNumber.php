<?php session_start();
if(isset($_REQUEST['pageNum'])){
    $_SESSION['pageNum'] = $_REQUEST['pageNum'];
}
if(isset($_REQUEST['selection'])){
    $arr = explode("|", $_REQUEST['selection']);
    foreach($arr as $q){
        $_SESSION[$q] = "OK";
    }
}
if(isset($_REQUEST['postPage'])){
    $_SESSION['postPage'] = (int) $_REQUEST['postPage'];
}