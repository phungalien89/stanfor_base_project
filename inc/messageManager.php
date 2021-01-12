<?php session_start();

if(isset($_REQUEST['reset'])){
    unset($_SESSION['message']);
}

