<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php session_start();
    spl_autoload_register(function($class_name){
        include_once "admin/" . $class_name . ".php";
    });
    //phpinfo();

    function formatPrice($price){
        $price = number_format($price, 0);
        return $price;
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
        transform: scale(0.975);
    }
    .hover-scale-down:hover img{
        filter: opacity(70%);
    }
    .price-discount, .price{
        font-weight: bold;
    }
    .price-discount:before{
        content: "Chỉ còn:";
        padding-right: 0.5em;
    }
    .no-discount{
        color: red;
    }
    .discount{
        color: gray;
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
    .see-all{
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
    }
    .see-all .fa{
        font-size: 1.75em;
        color: #009688;
    }
    .see-all a{
        background-color: #009688;
        border-color: #009688;
        border-radius: 100px;
    }
    .see-all:hover .fa{
        animation: ani-see-all 0.5s ease-in-out infinite;
    }
    @keyframes ani-see-all {
        to{
            transform: translateX(50%);
        }
    }

</style>
<body>
<?php
    $bikeMan = new BikeProvider();

    $newBikes = $bikeMan->getNewBikes();
    $countNewBikes = count($newBikes);
    $newBikes = $bikeMan->getNewBikes('8');

    $_SESSION['pageNum'] = 1;

?>
<div class="wrap">
    <div class="header">
        <?php include "layout/header.php" ?>
        <?php include "layout/sidebar.php" ?>
        <?php include "inc/message.php" ?>
        <?php include "inc/call.php" ?>
        <?php include "inc/scrollToTop.php" ?>
    </div>

    <div class="main">
        <div class="content">
            <div class="content_top position-relative">
                <span>sản phẩm mới</span>
                <div class="see-all <?= $countNewBikes > 8 ? 'd-flex' : 'collapse' ?> align-items-center">
                    <a class="btn btn-info" href="ProductList.php?q=newBike">Xem tất cả</a>
                    <span class="pl-2 fa fa-arrow-circle-right"></span>
                </div>
            </div>
            <div class="section group row py-3">
                <?php
                    foreach($newBikes as $id => $newBike) { ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 py-2">
                            <div class="card shadow hover-scale-down">
                                <div class="new-product"></div>
                                <div class="card-body position-relative">
                                    <a href="BikeDetail.php?bikeId=<?= $newBike->bikeId ?>"
                                       class="stretched-link"></a>
                                    <div style="<?= $newBike->bikeDiscountPrice == 0 ? 'color: transparent' : '' ?>"
                                         class="price discount text-center"><?= formatPrice($newBike->bikePrice) ?>
                                        &#8363;
                                    </div>
                                    <img class="mx-auto w-100" src="storage/<?= $newBike->bikeImage ?>"
                                         alt="<?= $newBike->bikeImage ?>"/>
                                    <div class="text-center font-weight-bold"
                                         style="font-size: 1.25em; color: #009688;"><?= $newBike->bikeName ?></div>
                                    <?php
                                    if ($newBike->bikeDiscountPrice == 0) { ?>
                                        <div
                                            class="price no-discount text-center pt-2"><?= formatPrice($newBike->bikePrice) ?>
                                            &#8363;
                                        </div>
                                    <?php } else { ?>
                                        <div
                                            class="price-discount text-center pt-2"><?= formatPrice($newBike->bikeDiscountPrice) ?>
                                            &#8363;
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <input type="hidden" name="bikeId" value="<?= $newBike->bikeId ?>">
                                        <button data-toggle="tooltip" title="Thêm vào yêu thích" name="btnAddWish"
                                                id="btnAddWishNew<?= $newBike->bikeId ?>"
                                                class="btn btn-danger" <?= array_search($newBike->bikeId, $_SESSION['wish'] ?? []) > -1 ? 'disabled' : '' ?>>
                                            <span class="fas fa-heart"></span>
                                        </button>
                                        <button data-toggle="tooltip" title="Thêm vào giỏ hàng" name="btnAddCart"
                                                id="btnAddCartNew<?= $newBike->bikeId ?>"
                                                class="btn btn-info" <?= array_search($newBike->bikeId, $_SESSION['cart'] ?? []) > -1 ? 'disabled' : '' ?>>
                                            <span class="fas fa-cart-plus"></span>
                                        </button>
                                        <script>
                                            $('#btnAddCartNew<?= $newBike->bikeId ?>').on("click", () => {
                                                var httpRequest = new XMLHttpRequest();
                                                httpRequest.onreadystatechange = () => {
                                                    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                                                        location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                    }
                                                };
                                                httpRequest.open("POST", "/AddToCart.php?addToCart=<?= $newBike->bikeId ?>", true);
                                                httpRequest.send();
                                            });
                                            $('#btnAddWishNew<?= $newBike->bikeId ?>').on("click", () => {
                                                var httpRequest = new XMLHttpRequest();
                                                httpRequest.onreadystatechange = () => {
                                                    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                                                        location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                    }
                                                };
                                                httpRequest.open("POST", "AddToWish.php?addToWish=<?= $newBike->bikeId ?>", true);
                                                httpRequest.send();
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <?php
                $arr_promoted = $bikeMan->getPromotedBikes();
                $countPromoteBike = count($arr_promoted);
                $arr_promoted = $bikeMan->getPromotedBikes(0);//limit 8 offset 0
            ?>
            <div class="content_bottom position-relative">
                <span>đang khuyến mãi</span>
                <div class="see-all <?= $countPromoteBike > 8 ? 'd-flex' : 'collapse' ?> align-items-center">
                    <a class="btn btn-info" href="ProductList.php?q=promoteBike">Xem tất cả</a>
                    <span class="pl-2 fa fa-arrow-circle-right"></span>
                </div>
            </div>
            <div class="section group row py-3">
                <?php
                    foreach($arr_promoted as $id => $bike){ ?>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card shadow hover-scale-down">
                                <?php
                                $fiveDays_ago = strtotime("-5 day");
                                ?>
                                <div class="<?= strtotime($bike->dateModified) > $fiveDays_ago ? 'new-product' : ''?>"></div>
                                <div class="card-body position-relative">
                                    <a href="BikeDetail.php?bikeId=<?= $bike->bikeId ?>" class="stretched-link"></a>
                                    <div style="<?= $bike->bikeDiscountPrice == 0 ? 'color: transparent' : '' ?>" class="price discount text-center"><?= formatPrice($bike->bikePrice) ?> &#8363;</div>
                                    <img class="mx-auto w-100" src="storage/<?= $bike->bikeImage ?>" alt="<?= $bike->bikeImage ?>" />
                                    <div class="text-center font-weight-bold" style="font-size: 1.25em; color: #009688;"><?= $bike->bikeName ?></div>
                                    <?php
                                    if($bike->bikeDiscountPrice == 0){ ?>
                                        <div class="price no-discount text-center pt-2"><?= formatPrice($bike->bikePrice) ?> &#8363;</div>
                                    <?php }
                                    else{ ?>
                                        <div class="price-discount text-center pt-2"><?= formatPrice($bike->bikeDiscountPrice) ?> &#8363;</div>
                                    <?php }
                                    ?>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <input type="hidden" name="bikeId" value="<?= $bike->bikeId ?>">
                                        <button data-toggle="tooltip" title="Thêm vào yêu thích" name="btnAddWish" id="btnAddWishPromote<?= $bike->bikeId ?>" class="btn btn-danger" <?= array_search($bike->bikeId, $_SESSION['wish'] ?? []) > -1 ? 'disabled' : '' ?>>
                                            <span class="fas fa-heart"></span>
                                        </button>
                                        <button data-toggle="tooltip" title="Thêm vào giỏ hàng" name="btnAddCart" id="btnAddCartPromote<?= $bike->bikeId ?>" class="btn btn-info" <?= array_search($bike->bikeId, $_SESSION['cart'] ?? []) > -1 ? 'disabled' : '' ?>>
                                            <span class="fas fa-cart-plus"></span>
                                        </button>
                                        <script>
                                            $('#btnAddCartPromote<?= $bike->bikeId ?>').on("click", ()=>{
                                                var httpRequest = new XMLHttpRequest();
                                                httpRequest.onreadystatechange = ()=>{
                                                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                                        location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                    }
                                                };
                                                httpRequest.open("POST", "AddToCart.php?addToCart=<?= $bike->bikeId ?>", true);
                                                httpRequest.send();
                                            });
                                            $('#btnAddWishPromote<?= $bike->bikeId ?>').on("click", ()=>{
                                                var httpRequest = new XMLHttpRequest();
                                                httpRequest.onreadystatechange = ()=>{
                                                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                                        location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                                                    }
                                                };
                                                httpRequest.open("POST", "AddToWish.php?addToWish=<?= $bike->bikeId ?>", true);
                                                httpRequest.send();
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <?php include "layout/footer.php" ?>
</div>
<script type="text/javascript">
    /*$(document).ready(function() {
        $().UItoTop({ easingType: 'easeOutQuart' });

    });*/
    $('[data-toggle="tooltip"]').tooltip();
</script>
<a href="#" id="toTop"><span id="toTopHover"> </span></a>
</body>
</html>

