<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php session_start();
    //Tự động thêm các class khi khai báo
    spl_autoload_register(function($class_name){
        include_once "admin/" . $class_name . ".php";
    });
    //phpinfo();

    //Format kiểu hiển thị tiền
    function formatPrice($price){
        $price = number_format($price, 0);
        return $price;
    }

    //Hiển thị query ra thanh tiêu đề
    function showQuery($q){
        $q = str_replace("bikeType=", "", $q);
        $q = str_replace("bikeBrand=", "", $q);
        $arr = explode("&", $q);
        $q = "";
        $arrL = explode("|", $arr[0]);
        foreach($arrL as $i => $item){
            $q .= ucfirst($item) . " | ";
        }
        $q = substr($q, 0, strlen($q) - 3);
        if(count($arr) > 1){
            $arrR = explode("|", $arr[1]);
            $q .= " & ";
            foreach($arrR as $i => $item){
                $q .= ucfirst($item) . " | ";
            }
            $q = substr($q, 0, strlen($q) - 3);
        }
        return $q;
    }

?>
<!DOCTYPE HTML>
<head>
    <title>Free Home Shoppe Website Template | Home :: w3layouts</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/slider.css" rel="stylesheet" type="text/css" media="all"/>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.min.css"> <!--fontAwesome-->
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<style>
    *{
        box-sizing: border-box;
    }
    @font-face {
        font-family: fontAwesome-r;
        src: url("webfonts/fa-regular-400.woff");
    }
    @font-face {
        font-family: fontAwesome-s;
        src: url("webfonts/fa-solid-900.woff");
    }
    .hover-shadow-outline, .hover-to-primary{
        transition: all 0.15s ease;
    }
    .hover-shadow-outline:hover{
        box-shadow: 0px 0px 0px 3px var(--light);
    }
    .hover-to-primary{
        color: white !important;
    }
    .hover-scale-down{
        transition: all 0.25s ease;
    }
    .hover-scale-down:hover{
        transform: scale(0.98);
    }
    .hover-scale-down:hover img{
        filter: opacity(70%);
    }
    .price-discount, .price{
        font-weight: bold;
    }
    .no-discount{
        color: red;
    }
    .discount{
        color: red;
        text-decoration: line-through;
    }
    .price-discount{
        color: red;
    }
    .navbar{
        padding: 0;
    }
    .view-cart{
        background-color: #009688;
        color: white;
        position: relative;
        padding: 10px;
    }
    .view-cart:before{
        content: "";
        border-top: 22px solid transparent;
        border-bottom: 22px solid transparent;
        border-right: 15px solid #009688;
        position: absolute;
        right: 100%;
        top: 0%;
    }
    .bikeType_container input{
        display: none;
    }
    .bikeType_container label{
        position: relative;
        border: 1px solid #bbb;
        padding: 5px;
    }
    .bikeType_container label{
        height: 40px;
    }
    .bikeBrand_container label{
        height: 60px;
    }
    .bikeType_container input:checked + label:after{
        content: "";
        background-image: url("http://localhost:63342/Website/asset/img/checked_icon_top_right_01.png");
        background-size: 100% 100%;
        width: 23px;
        height: 20px;
        position: absolute;
        top: 0;
        right: -1px;
        animation: ani-tick 1s ease-in-out infinite;
    }
    @keyframes ani-tick {
        to{
            opacity: 0.2;
        }
    }
    .bikeType_container input:checked + label{
        border: 2px solid #009688;
    }

</style>
<body>
<?php

    $bikeMan = new BikeProvider();
    $typeMan = new TypeProvider();
    $brandMan = new BrandProvider();
    $types = $typeMan->getAllType();
    $brands = $brandMan->getAllBrand();

    $arrBikes = [];
    $keyword = "";
    $started_filter = "";

    $arr_type = [];
    foreach($types as $id => $type){
        $arr_type[] = "type" . $id;
    }
    foreach($types as $id => $type){
        ${$arr_type[$id]} = false;
    }
    $arr_brand = [];
    foreach($brands as $id => $brand){
        $arr_brand[] = "brand" . $id;
    }
    foreach($brands as $id => $brand){
        ${$arr_brand[$id]} = false;
    }
    $query = "";
    $currentPage = 1;

    $arr_desc_type = [];
    $arr_name_type = [];
    $arr_desc_brand = [];
    $arr_name_brand = [];

    //xử lý dữ liệu filter gửi từ trang HomePage
    if(isset($_SESSION['filter'])){
        $keyword = $_SESSION['filter'];
        $started_filter = $keyword;
        $_SESSION['pageNum'] = 1;
        $arrBikes = $bikeMan->getBikesByColumn($keyword, "bikeType");
        if(count($arrBikes) == 0){
            $arrBikes = $bikeMan->getBikesByColumn($keyword, "bikeBrand");
        }
        foreach($types as $id => $type){
            if($keyword == $type->typeName){
                $arr_name_type[] = "Type" . $id;
                $arr_desc_type[] = $type->typeName;
            }
        }
        foreach($brands as $id => $brand){
            if($keyword == $brand->brandName){
                $arr_name_brand[] = "Brand" . $id;
                $arr_desc_brand[] = $brand->brandName;
            }
        }
        unset($_SESSION['filter']);
    }
    //Xử lý request của các filter trong trang này
    foreach($types as $id => $type){
        if(isset($_REQUEST['chkType' . $id]) || isset($_SESSION['Type' . $id])){
            ${$arr_type[$id]} = $_REQUEST['chkType' . $id] ?? $_SESSION['Type' . $id];
            ${$arr_type[$id]} = boolval(${$arr_type[$id]});
            if(isset($_REQUEST['chkType'. $id])){
                $_SESSION['pageNum'] = 1;
            }
            if(strpos($query, "bikeType") > -1){
                $query .= "|" . $type->typeName;
            }
            else{
                $query .= "bikeType=" . $type->typeName;
            }
            $arr_desc_type[] = $type->typeName;
            $arr_name_type[] = "Type" . $id;
            if(isset($_SESSION['Type'.$id])){
                unset($_SESSION['Type'.$id]);
            }
            //echo "<script>alert('Query = ". $query ."');</script>";
        }
    }
    foreach($brands as $id => $brand){
        if(isset($_REQUEST['chkBrand' . $id]) || isset($_SESSION['Brand' . $id])){
            ${$arr_brand[$id]} = $_REQUEST['chkBrand' . $id] ?? $_SESSION['Brand' . $id];
            ${$arr_brand[$id]} = boolval(${$arr_brand[$id]});
            if(strpos($query, "bikeBrand") > -1){
                $query .= "|" . $brand->brandName;
            }
            else{
                $query .= "&bikeBrand=" . $brand->brandName;
            }

            $arr_desc_brand[] = $brand->brandName;
            $arr_name_brand[] = "Brand" . $id;
            if(isset($_SESSION['Brand'.$id])){
                unset($_SESSION['Brand'.$id]);
            }
            //echo "<script>alert('Query = ". $query ."');</script>";
        }
    }
    if(!(strpos($query, "bikeType") > -1)){
        $query = substr($query, 1);
    }

    //echo "<script>alert('". $query ."');</script>";

    if(strlen($query) > 0){
        $arrBikes = $bikeMan->getBikesByQuery($query);
    }
    $_SESSION['sortType'] = "default";
    if(isset($_REQUEST['comboSort'])){
        $sortType = $_POST['comboSort'];
        $_SESSION['sortType'] = $sortType;
        if(strlen($query) > 0){
            $arrBikes = $bikeMan->getBikesByQuery($query, $sortType);
        }
    }

    if(isset($_SESSION['pageNum'])){
        $currentPage = (int) $_SESSION['pageNum'];
    }


?>
<div class="wrap">
    <div class="header">
        <?php include "layout/header.php" ?>
        <?php include "inc/message.php" ?>
        <?php include "inc/call.php" ?>
        <?php include "inc/scrollToTop.php" ?>
    </div>
    <div class="main pt-3">
        <form action="" method="post">
            <div class="row py-3">
                <div class="col-sm-4">
                    <select <?= $arrBikes == [] ? 'disabled' : '' ?> onchange="this.form.submit();" name="comboSort" id="comboSort" class="custom-select">
                        <option <?= $_SESSION['sortType'] == 'default' ? 'selected' : '' ?> value="default" >Sắp xếp theo mặc định</option>
                        <option <?= $_SESSION['sortType'] == 'price_up' ? 'selected' : ''  ?> value="price_up">Giá tăng dần</option>
                        <option <?= $_SESSION['sortType'] == 'price_down' ? 'selected' : '' ?> value="price_down">Giá giảm dần</option>
                    </select>
                </div>
                <div class="col-sm-8">
                    <?php
                    if(count($arrBikes) > 7){ ?>
                        <ul class="pagination pt-3 justify-content-end">
                            <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                                <a id="pageLinkTopPrev" href="" class="page-link">
                                    <span class="fa fa-arrow-left"></span>
                                </a>
                            </li>
                            <?php
                            $pageNum = floor(count($arrBikes) / 8);
                            $pageNum += 1;
                            for($i = 0; $i < $pageNum; $i++){ ?>
                                <li class="page-item <?= $currentPage == ($i + 1) ? 'active' : '' ?>">
                                    <a id="pageLinkTop<?= $i ?>" href="" class="page-link">
                                        <input type="hidden" name="pageNum" value="<?= $i + 1 ?>">
                                        <span><?= $i + 1 ?></span>
                                    </a>
                                </li>
                                <script>
                                    $('#pageLinkTop<?= $i ?>').on("click", (e)=>{
                                        e.preventDefault();
                                        var httpRequest = new XMLHttpRequest();
                                        var q_str = "http://localhost:63342/Website/admin/setPageNumber.php?pageNum=<?= $i+1 ?>&selection=<?= implode("|", $arr_name_type) . "|" . implode("|", $arr_name_brand) ?>";
                                        httpRequest.onreadystatechange = ()=>{
                                            if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                                location.assign('<?= $_SERVER['PHP_SELF'] ?>?<?= $_SERVER['QUERY_STRING'] ?>');
                                            }
                                        };
                                        httpRequest.open("POST", q_str, true);
                                        httpRequest.send();
                                    });
                                </script>
                            <?php }
                            ?>
                            <li class="page-item <?= $currentPage == $pageNum ? 'disabled' : '' ?>">
                                <a id="pageLinkTopNext" href="" class="page-link">
                                    <span class="fa fa-arrow-right"></span>
                                </a>
                            </li>
                            <script>
                                $('#pageLinkTopPrev').on("click", (e)=>{
                                    e.preventDefault();
                                    var httpRequest = new XMLHttpRequest();
                                    var q_str = "http://localhost:63342/Website/admin/setPageNumber.php?pageNum=<?= $currentPage - 1 ?>&selection=<?= implode("|", $arr_name_type) . "|" . implode("|", $arr_name_brand) ?>";
                                    httpRequest.onreadystatechange = ()=>{
                                        if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                            location.assign('<?= $_SERVER['PHP_SELF'] ?>?<?= $_SERVER['QUERY_STRING'] ?>');
                                        }
                                    };
                                    httpRequest.open("POST", q_str, true);
                                    httpRequest.send();
                                });
                                $('#pageLinkTopNext').on("click", (e)=>{
                                    e.preventDefault();
                                    var httpRequest = new XMLHttpRequest();
                                    var q_str = "http://localhost:63342/Website/admin/setPageNumber.php?pageNum=<?= $currentPage + 1 ?>&selection=<?= implode("|", $arr_name_type) . "|" . implode("|", $arr_name_brand) ?>";
                                    httpRequest.onreadystatechange = ()=>{
                                        if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                            location.assign('<?= $_SERVER['PHP_SELF'] ?>?<?= $_SERVER['QUERY_STRING'] ?>');
                                        }
                                    };
                                    httpRequest.open("POST", q_str, true);
                                    httpRequest.send();
                                });
                            </script>
                        </ul>
                    <?php }
                    ?>
                </div>
            </div>
            <div class="filter_group d-flex justify-content-start" style="gap: 5px">
                <?php
                    foreach($types as $id => $type){ ?>
                        <div class="bikeType_container">
                            <input onchange="this.form.submit()" <?= ${$arr_type[$id]} ? 'checked' : '' ?> <?= $started_filter == $type->typeName ? 'checked' : '' ?> type="checkbox" name="chkType<?= $id ?>" id="chkType<?= $id ?>">
                            <label for="chkType<?= $id ?>" class="text-uppercase">
                                <img class="h-100" src="<?= $type->typeImage ?>" alt="">
                            </label>
                        </div>
                    <?php }
                ?>

                <?php
                    foreach($brands as $id => $brand){ ?>
                        <div class="bikeType_container">
                            <input onchange="this.form.submit()" <?= ${$arr_brand[$id]} ? 'checked' : '' ?> <?= $started_filter == $brand->brandName ? 'checked' : '' ?> type="checkbox" name="chkBrand<?= $id ?>" id="chkBrand<?= $id ?>">
                            <label for="chkBrand<?= $id ?>" class="text-uppercase">
                                <img class="h-100" src="<?= $brand->brandLogo ?>" alt="">
                            </label>
                        </div>
                    <?php }
                ?>
            </div>

            <div class="d-flex align-items-center">
                <?php
                    foreach($arr_name_type as $id => $_name){ ?>
                        <div class="btn-group pr-3 pt-3">
                            <span class="btn btn-info" style="cursor: default; background-color: #009688;"><?= $arr_desc_type[$id] ?></span>
                            <button style="border-left: 0" onclick="$('#chk<?= $_name ?>').click();" class="btn btn-outline-danger dropdown-toggle-split">
                                <span class="fas fa-times"></span>
                            </button>
                        </div>
                    <?php }
                ?>

                <?php
                    foreach($arr_name_brand as $id => $_name){ ?>
                        <div class="btn-group pr-3 pt-3">
                            <span class="btn btn-info" style="cursor: default; background-color: #009688;"><?= $arr_desc_brand[$id] ?></span>
                            <button style="border-left: 0" onclick="$('#chk<?= $_name ?>').click();" class="btn btn-outline-danger dropdown-toggle-split">
                                <span class="fas fa-times"></span>
                            </button>
                        </div>
                    <?php }
                ?>
            </div>
        </form>
        <div class="content">
            <div class="">
                <div class="bikeHeading">
                    <span><?= strlen($query) > 0 ? showQuery($query) : ucfirst($keyword) ?></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="section group row py-3">
                <?php
                    foreach($arrBikes as $id => $bike) {
                        if ($id >= (($currentPage - 1) * 8) && $id < ($currentPage * 8)) { ?>
                            <div class="col-sm-6 col-md-4 col-lg-3 py-2">
                                <div class="card shadow hover-scale-down">
                                    <?php
                                    $fiveDays_ago = strtotime("-8 day");
                                    ?>
                                    <div class="<?= strtotime($bike->dateModified) > $fiveDays_ago ? 'new-product' : '' ?>"></div>
                                    <div class="card-body position-relative">
                                        <a href="http://localhost:63342/Website/BikeDetail.php?bikeId=<?= $bike->bikeId ?>"
                                           class="stretched-link"></a>
                                        <div style="<?= $bike->bikeDiscountPrice == 0 ? 'color: transparent' : '' ?>"
                                             class="price discount text-center"><?= formatPrice($bike->bikePrice) ?>
                                            &#8363;
                                        </div>
                                        <img class="mx-auto w-100" src="<?= $bike->bikeImage ?>"
                                             alt="<?= $bike->bikeImage ?>"/>
                                        <div class="text-center font-weight-bold"
                                             style="font-size: 1.25em; color: #009688;"><?= $bike->bikeName ?></div>
                                        <?php
                                        if ($bike->bikeDiscountPrice == 0) { ?>
                                            <div class="price no-discount text-center pt-2"><?= formatPrice($bike->bikePrice) ?>
                                                &#8363;
                                            </div>
                                        <?php } else { ?>
                                            <div class="price-discount text-center pt-2"><?= formatPrice($bike->bikeDiscountPrice) ?>
                                                &#8363;
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-between">
                                            <button data-toggle="tooltip" title="Thêm vào yêu thích" name="btnAddWish"
                                                    id="btnAddWishFilter<?= $bike->bikeId ?>"
                                                    class="btn btn-danger" <?= array_search($bike->bikeId, $_SESSION['wish'] ?? []) > -1 ? 'disabled' : '' ?>>
                                                <span class="fas fa-heart"></span>
                                            </button>
                                            <button data-toggle="tooltip" title="Thêm vào giỏ hàng" name="btnAddCart"
                                                    id="btnAddCartFilter<?= $bike->bikeId ?>"
                                                    class="btn btn-info" <?= array_search($bike->bikeId, $_SESSION['cart'] ?? []) > -1 ? 'disabled' : '' ?>>
                                                <span class="fas fa-cart-plus"></span>
                                            </button>
                                            <script>
                                                $('#btnAddCartFilter<?= $bike->bikeId ?>').on("click", () => {
                                                    var httpRequest = new XMLHttpRequest();
                                                    httpRequest.onreadystatechange = () => {
                                                        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                                                            location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                        }
                                                    };
                                                    var q = "&selection=" + "<?= implode('|', $arr_name_type) ?>" + "|" + "<?= implode('|', $arr_name_brand) ?>";
                                                    httpRequest.open("POST", "http://localhost:63342/Website/AddToCart.php?addToCart=<?= $bike->bikeId ?>" + q, true);
                                                    httpRequest.send();
                                                });
                                                $('#btnAddWishFilter<?= $bike->bikeId ?>').on("click", () => {
                                                    var httpRequest = new XMLHttpRequest();
                                                    httpRequest.onreadystatechange = () => {
                                                        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                                                            location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                        }
                                                    };
                                                    var q = "&selection=" + "<?= implode('|', $arr_name_type) ?>" + "|" + "<?= implode('|', $arr_name_brand) ?>";
                                                    httpRequest.open("POST", "http://localhost:63342/Website/AddToWish.php?addToWish=<?= $bike->bikeId ?>" + q, true);
                                                    httpRequest.send();
                                                });

                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <?php include "layout/footer.php" ?>
</div>

<a href="#" id="toTop"><span id="toTopHover"> </span></a>
<script>
    $(document).ready(()=>{

    });
</script>
</body>
</html>

