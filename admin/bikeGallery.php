<?php
session_start();

if(isset($_REQUEST['gallery'])){
    $arr_img = [];
    $query_val = $_REQUEST['gallery'];
    $arr_img = explode("||", $query_val);
    foreach ($arr_img as $id => $img){
        $_SESSION['img_'. $id] = $img;
    }
    //echo "DONE";
    echo "<script>location.assign('http://localhost:63342/Website/BikeDetail.php');</script>";
}
