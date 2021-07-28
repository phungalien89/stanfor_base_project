<?php
function responseData($query){
    $mFile = fopen('../storage/'. $query .'.txt', 'w');
    $txt = $_REQUEST[$query];
    fwrite($mFile, $txt);
    fclose($mFile);
    $mFile1 = fopen('../storage/'. $query .'.txt', 'r');
    $txt = "";
    while(!feof($mFile1)){
        $txt .= fgets($mFile1);
    }
    fclose($mFile1);
    sleep(1);
    echo $txt;
}
function responseGETdata($query){
    $mFile = fopen('../storage/'. $query .'.txt', 'r');
    $txt = "";
    while(!feof($mFile)){
        $txt .= fgets($mFile);
    }
    fclose($mFile);
    sleep(1);
    echo $txt;
}
//BRAND response
//ADD
if(isset($_REQUEST['add_brand_desc'])){
    responseData('add_brand_desc');
}
if(isset($_REQUEST['edit_brand_desc'])){
    responseData('edit_brand_desc');
}
//GET
if(isset($_REQUEST['get_add_brand_desc'])){
    responseGETdata('add_brand_desc');
}
if(isset($_REQUEST['get_edit_brand_desc'])){
    responseGETdata('edit_brand_desc');
}

//BIKE response
//ADD
if(isset($_REQUEST['add_bike_short_desc'])){
    responseData('add_bike_short_desc');
}
if(isset($_REQUEST['add_bike_gift'])){
    responseData("add_bike_gift");
}
if(isset($_REQUEST['add_bike_highlight'])){
    responseData("add_bike_highlight");
}
if(isset($_REQUEST['add_bike_specs'])){
    responseData("add_bike_specs");
}
if(isset($_REQUEST['add_bike_gallery'])){
    responseData("add_bike_gallery");
}
//EDIT
if(isset($_REQUEST['edit_bike_short_desc'])){
    responseData('edit_bike_short_desc');
}
if(isset($_REQUEST['edit_bike_gift'])){
    responseData("edit_bike_gift");
}
if(isset($_REQUEST['edit_bike_highlight'])){
    responseData("edit_bike_highlight");
}
if(isset($_REQUEST['edit_bike_specs'])){
    responseData("edit_bike_specs");
}
if(isset($_REQUEST['edit_bike_gallery'])){
    responseData("edit_bike_gallery");
}


//GET BIKE
//ADD
if(isset($_REQUEST['get_add_bike_short_desc'])){
    responseGETdata('add_bike_short_desc');
}
if(isset($_REQUEST['get_add_bike_gift'])){
    responseGETdata('add_bike_gift');
}
if(isset($_REQUEST['get_add_bike_highlight'])){
    responseGETdata('add_bike_highlight');
}
if(isset($_REQUEST['get_add_bike_specs'])){
    responseGETdata('add_bike_specs');
}
if(isset($_REQUEST['get_add_bike_gallery'])){
    responseGETdata('add_bike_gallery');
}
//EDIT
if(isset($_REQUEST['get_edit_bike_short_desc'])){
    responseGETdata('edit_bike_short_desc');
}
if(isset($_REQUEST['get_edit_bike_gift'])){
    responseGETdata('edit_bike_gift');
}
if(isset($_REQUEST['get_edit_bike_highlight'])){
    responseGETdata('edit_bike_highlight');
}
if(isset($_REQUEST['get_edit_bike_specs'])){
    responseGETdata('edit_bike_specs');
}
if(isset($_REQUEST['get_edit_bike_gallery'])){
    responseGETdata('edit_bike_gallery');
}

//POST
//ADD
if(isset($_REQUEST['add_post_content'])){
    responseData('add_post_content');
}
if(isset($_REQUEST['edit_post_content'])){
    responseData('edit_post_content');
}

//GET
if(isset($_REQUEST['get_add_post_content'])){
    responseGETdata('add_post_content');
}
if(isset($_REQUEST['get_edit_post_content'])){
    responseGETdata('edit_post_content');
}

/* EDIT BANNER */
//POST
if(isset($_REQUEST['edit_banner'])){
    responseData('edit_banner');
}
//GET
if(isset($_REQUEST['get_edit_banner'])){
    responseGETdata('edit_banner');
}
//Dont add the end of PHP here


