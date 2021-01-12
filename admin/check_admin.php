<?php

if(isset($_SESSION['is_logged'])){
    if(isset($_SESSION['is_userAdmin'])){
        if($_SESSION['is_userAdmin'] != "true"){
            header("location: http://localhost:63342/Website/HomePage.php");
        }
    }
}
else{
    header("location: http://localhost:63342/Website/HomePage.php");
}
