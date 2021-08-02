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
        $price .= " &#8363;";
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
    <link rel="stylesheet" href="css/chkeditor-plugins/ckeditor-style.css">
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
        transform: scale(0.95);
    }
    .hover-scale-down:hover img{
        filter: opacity(70%);
    }
    .price-discount, .price{
        font-weight: bold;
    }
    .no-discount{
        color: red !important;
    }
    .discount{
        color: gray !important;
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
    .chkDetailGroup input[type='radio']{
        display: none;
    }
    .chkDetailGroup label{
        background-color: #009688;
        color: white;
        padding: 0.5em;
        transition: all 0.25s ease;
        position: relative;
    }
    .chkDetailGroup label:hover{
        background-color: #006ba1;
    }
    .chkDetailGroup input:checked + label{
        background-color: #006ba1;
        color: white;
    }
    .chkDetailGroup input:checked + label:after{
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-top: 7px solid #006ba1;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
    }
    .img-thumbnail{
        border-color: #009688;
    }
    .gift-title{
        border-bottom: 3px solid #009688;
        padding: 8px 0;
    }
    .gift-title span{
        background-color: #009688;
        color: white;
        padding: 8px 15px;
        position: relative;
        font-weight: bold;
    }
    .gift-title span:after{
        content: "";
        position: absolute;
        top: 0;
        left: 100%;
        border-bottom: 38px solid #009688;
        border-right: 20px solid transparent;
    }
    .radioGroup{
        position: relative;
        border-bottom: 3px solid #009688;
    }
    .ul-style ul{
        list-style: none;
    }
    .ul-style ul li{
        margin-left: 1.5em;
        position: relative;
    }
    .ul-style ul li:before{
        content: "\f06b";
        position: absolute;
        left: -1.5em;
        font-family: fontAwesome-s;
        color: #009688;
    }
    .post-container{
        padding: 10px 0;
        position: relative;
    }
    .product-price, .discount-price{
        color: gray;
        font-size: 1.5em;
        font-weight: bold;
        position: relative;
    }
    .strike .product-price:after{
        content: attr(data-content);
        border-top: 2px solid white;
        position: absolute;
        top: 45%;
        left: 0;
        color: transparent;
    }
    .strike .product-price:before{
        content: attr(data-content);
        border-top: 2px solid white;
        position: absolute;
        top: 55%;
        left: 0;
        color: transparent;
    }

    .image-group .img-thumbnail{
        transition: all 0.25s ease;
    }
    .image-group .img-thumbnail:hover{
        box-shadow: 0 0 0 3px rgba(0, 150, 135, 0.7);
    }
    .carousel-indicators .active .img-thumbnail{
        box-shadow: 0 0 0 3px rgba(0, 150, 135, 0.7);
    }
    #slide_thumb .carousel-indicators, #carousel_modal .carousel-indicators {
        position: relative;
        display: flex;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

</style>
<body>
<?php
    $bikeDetailMan = new BikeProvider();

    $bikeDetail = $bikeDetailMan->getBikeById((int)$_REQUEST['bikeId']);
    $arr_img = [];
    $domDoc = new DOMDocument();
    @$domDoc->loadHTML($bikeDetail->bikeGallery);
    $images = $domDoc->getElementsByTagName('img');
    foreach($images as $img){
        $arr_img[] = $img->getAttribute('src');
    }

    $postMan = new PostProvider();
    $posts = $postMan->getPostByTag($bikeDetail->bikeName);
    //print_r("Post count = " . count($posts));
?>
<div class="wrap">
    <div class="header">
        <?php include "layout/header.php" ?>
        <?php include "inc/message.php" ?>
        <?php include "inc/call.php" ?>
        <?php include "inc/scrollToTop.php" ?>

    </div>

    <div class="main">
        <div class="content">
            <div class="">
                <div class="bikeHeading">
                    <span><?= $bikeDetail->bikeName ?></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="section group py-3">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">
                                <div id="slide_thumb" data-ride="carousel" data-interval="1000" class="row image-group carousel" style="margin: 0;">
                                    <div class="carousel-inner">
                                        <?php
                                            foreach($arr_img as $id => $img){ ?>
                                                <div data-toggle="modal" data-target="#modal_thumb" style="cursor: zoom-in" class="carousel-item <?= $id == 0 ? 'active' : '' ?>">
                                                    <img class="img-thumbnail w-100 border" src="<?= $img ?>" alt="<?= $img ?>>">
                                                </div>
                                            <?php }
                                        ?>
                                    </div>
                                    <div class="carousel-indicators pt-3">
                                        <?php
                                        foreach($arr_img as $id => $img){ ?>
                                            <div class="col-3 p-1 <?= $id == 0 ? 'active' : '' ?>" style="position: relative">
                                                <img data-target="#slide_thumb" data-slide-to="<?= $id ?>" class="img-thumbnail w-100" src="<?= $img ?>" alt="">
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="modal" id="modal_thumb">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button data-dismiss="modal" class="btn btn-danger ml-auto m-3">
                                                <span class="fa fa-times"></span>
                                            </button>
                                            <div class="modal-body">
                                                <div data-ride="carousel" data-interval="1500" class="carousel slide w-100" id="carousel_modal">
                                                    <div class="carousel-indicators pb-3">
                                                        <?php
                                                        foreach($arr_img as $id => $img){ ?>
                                                            <div class="p-1 col-3 <?= $id == 0 ? 'active' : '' ?>">
                                                                <img style="width: 40px;" class="img-thumbnail" data-target="#carousel_modal" data-slide-to="<?= $id ?>" src="<?= $img ?>" alt="">
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <div class="carousel-inner">
                                                        <?php
                                                        foreach($arr_img as $id => $img){ ?>
                                                            <div class="carousel-item <?= $id == 0 ? 'active' : '' ?>">
                                                                <img class="w-100" src="<?= $img ?>" alt="">
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <a href="#carousel_modal" data-slide="prev" class="carousel-control-prev">
                                                        <span class="fa fa-arrow-circle-left" style="color: #009688; font-size: 2em;"></span>
                                                    </a>
                                                    <a href="#carousel_modal" data-slide="next" class="carousel-control-next">
                                                        <span class="fa fa-arrow-circle-right" style="color: #009688; font-size: 2em;"></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="p-0 ck-content text-justify">
                                    <?= $bikeDetail->bikeShortDesc ?>
                                </div>
                                <div class="row" style="gap: 1em; margin: 0">
                                    <button data-toggle="tooltip" title="Thêm vào yêu thích" name="btnAddWish" id="btnAddWish<?= $bikeDetail->bikeId ?>" class="btn btn-danger" <?= array_search($bikeDetail->bikeId, $_SESSION['wish'] ?? []) > -1 ? 'disabled' : '' ?>>
                                        <span class="fas fa-heart"></span>
                                    </button>
                                    <button data-toggle="tooltip" title="Thêm vào giỏ hàng" name="btnAddCart" id="btnAddCart<?= $bikeDetail->bikeId ?>" class="btn btn-info">
                                        <span class="fas fa-cart-plus"></span>
                                    </button>
                                </div>
                                <div class="<?= $bikeDetail->bikeDiscountPrice > 0 ? 'strike' : '' ?>">
                                    <div class="product-price <?= $bikeDetail->bikeDiscountPrice > 0 ? 'discount' : 'no-discount' ?>"><?=  formatPrice($bikeDetail->bikePrice) ?></div>
                                    <div class="discount-price no-discount"><?= $bikeDetail->bikeDiscountPrice > 0 ? "Chỉ còn: " . formatPrice($bikeDetail->bikeDiscountPrice) : '' ?></div>
                                    <script>
                                        $('.product-price').attr("data-content", "<?= formatPrice($bikeDetail->bikePrice) ?>")
                                    </script>
                                </div>
                                <div class="p-0 ck-content">
                                    <div class="gift-title">
                                        <span class="text-uppercase">
                                            khuyến mãi
                                        </span>
                                    </div>
                                    <div class="pt-3 ul-style">
                                        <?= $bikeDetail->bikeGift ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3 radioGroup" style="margin: 0">
                            <div class="chkDetailGroup">
                                <input checked type="radio" name="chkDetail" id="chkHighlight">
                                <label for="chkHighlight">Đặc điểm nổi bật</label>
                            </div>
                            <div class="chkDetailGroup">
                                <input type="radio" name="chkDetail" id="chkSpecs">
                                <label for="chkSpecs">Thông số kỹ thuật</label>
                            </div>
                            <div class="chkDetailGroup">
                                <input type="radio" name="chkDetail" id="chkGallery">
                                <label for="chkGallery">Thư viện ảnh</label>
                            </div>
                        </div>
                        <div style="margin: 0">
                            <div class="content_detail ck-content p-2" id="content_highlight" style="display: block;"><?= $bikeDetail->bikeHighlight ?></div>
                            <div class="content_detail ck-content p-2" id="content_specs" style="display: none;">
                                <div class="font-weight-bold text-uppercase" style="color: #009688; font-size: 1.5em">Thông số kỹ thuật</div>
                                <div>
                                    <?= $bikeDetail->bikeSpecs ?>
                                </div>
                            </div>
                            <div class="content_detail p-2" id="content_gallery" style="display: none;">
                                <div class="w-75 mx-auto">
                                    <div class="carousel slide w-100" id="slide_gallery" data-ride="carousel" data-interval="1500">
                                        <ul class="carousel-indicators">
                                            <?php
                                                foreach($arr_img as $id => $img_src){ ?>
                                                    <li style="background-color: #009688;" data-target="#slide_gallery" data-slide-to="<?= $id ?>">
                                                        <img style="width: 50px;" src="<?= $img_src ?>" alt="">
                                                    </li>
                                                <?php }
                                            ?>
                                        </ul>
                                        <div class="carousel-inner">
                                            <?php
                                            foreach($arr_img as $id => $img){ ?>
                                                <div class="carousel-item <?= $id == 0 ? 'active' : '' ?>">
                                                    <img src="<?= $img ?>" alt="<?= $img ?>" class="w-100">
                                                </div>
                                            <?php }
                                            ?>
                                        </div>
                                        <a data-slide="prev" href="#slide_gallery" class="carousel-control-prev">
                                            <span class="fa fa-arrow-circle-left" style="font-size: 2em; color: #009688;"></span>
                                        </a>
                                        <a data-slide="next" href="#slide_gallery" class="carousel-control-next">
                                            <span class="fa fa-arrow-circle-right" style="font-size: 2em; color: #009688;"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 flex-column" style="padding-left: 1em;">
                        <div class="text-center text-uppercase font-weight-bold p-2" style="font-size: 1.25em; color: white; background-color: #009688">bài viết liên quan</div>
                        <?php
                            foreach($posts as $id => $post){ ?>
                                <div class="post-container p-2 border my-2 rounded">
                                    <a href="PostDetail.php?postId=<?= $post->postId ?>" class="stretched-link"></a>
                                    <img class="img-thumbnail mr-2" style="width: 100px; float: left;" src="storage/<?= $post->postImage ?>" alt="<?= $post->postImage ?>">
                                    <div class="post-body">
                                        <div class="font-weight-bold" style="font-size: 1.15em;"><?= $post->postTitle ?></div>
                                        <div class="text-justify">
                                            <div id="post-content<?= $id ?>"></div>
                                            <script>
                                                var el = document.createElement('body')
                                                el.innerHTML = '<?= $post->postContent ?>';
                                                var Ptags = el.getElementsByTagName('p');
                                                for(var x of Ptags){
                                                    var wordCount = 0;
                                                    for(var i = 0; i < x.innerHTML.length; i++){
                                                        if(x.innerHTML.charAt(i) == " "){
                                                            wordCount++;
                                                        }
                                                        if(wordCount >= 45){
                                                            var txt = x.innerHTML.substr(0, i);
                                                            $('#post-content<?= $id ?>').html(txt + "...");
                                                            break;
                                                        }
                                                    }
                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <?php include "layout/footer.php" ?>
</div>
<script type="text/javascript">
    $(document).ready(()=>{
        $('[data-toggle="tooltip"]').tooltip();
        $('#chkHighlight').on("change", (e)=>{
            if(e.target.value){
                $('.content_detail').css('display', 'none');
                $('#content_highlight').css('display', 'block');
            }
        });
        $('#chkSpecs').on("change", (e)=>{
            if(e.target.value){
                $('.content_detail').css('display', 'none');
                $('#content_specs').css('display', 'block');
            }
        });
        $('#chkGallery').on("change", (e)=>{
            if(e.target.value){
                $('.content_detail').css('display', 'none');
                $('#content_gallery').css('display', 'block');
            }
        });

        $('#btnAddCart<?= $bikeDetail->bikeId ?>').on("click", ()=>{
            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = ()=>{
                if(httpRequest.readyState == 4 && httpRequest.status == 200){
                    location.assign('<?= $_SERVER['PHP_SELF'] ?>' + '?bikeId=<?= $bikeDetail->bikeId ?>');
                }
            };
            httpRequest.open("POST", "AddToCart.php?addToCart=<?= $bikeDetail->bikeId ?>", true);
            httpRequest.send();
        });
        $('#btnAddWish<?= $bikeDetail->bikeId ?>').on("click", ()=>{
            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = ()=>{
                if(httpRequest.readyState == 4 && httpRequest.status == 200){
                    location.assign('<?= $_SERVER['PHP_SELF'] ?>' + '?bikeId=<?= $bikeDetail->bikeId ?>');
                }
            };
            httpRequest.open("POST", "AddToWish.php?addToWish=<?= $bikeDetail->bikeId ?>", true);
            httpRequest.send();
        });
    })
</script>
<a href="#" id="toTop"><span id="toTopHover"> </span></a>
</body>
</html>
