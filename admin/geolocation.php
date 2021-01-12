<?php session_start();
if(isset($_REQUEST['loadedMap'])){
    $status = $_REQUEST['loadedMap'];
    $_SESSION['loadedMap'] = $status;
}
