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
        color: red;
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
    .bikeHeading{
        border-bottom: 3px solid #009688;
        padding-bottom: 10px;
    }
    .bikeHeading span{
        position: relative;
        background-color: #009688;
        padding: 10px 40px;
        color: white;
        font-weight: bold;;
        font-size: 1.5em;
    }
    .bikeHeading span:after{
        content: "";
        position: absolute;
        top: 0;
        left: 100%;
        border-bottom: 52px solid #009688;
        border-right: 40px solid transparent;
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
    $postMan = new PostProvider();
    $post = null;
    $posts = $postMan->getAllPost();

    $currentPage = 1;
    if(isset($_SESSION['postPage'])){
        $currentPage = (int) $_SESSION['postPage'];
    }
?>
<div class="wrap">
    <div class="header">
        <?php include "layout/header.php" ?>
        <?php include "inc/call.php" ?>
        <?php include "inc/scrollToTop.php" ?>
    </div>

    <div class="main">
        <div class="content">
            <div class="">
                <div class="bikeHeading">
                    <span><?= "BÀI ĐĂNG" ?></span>
                </div>
                <div class="clear"></div>
            </div>
            <?php
                $pageNum = floor(count($posts) / 4);
                if(count($posts) % 4 > 0){
                    $pageNum += 1;
                }
            ?>
            <ul class="pt-3 pagination justify-content-end">
                <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                    <a id="pageLinkTopPrev" href="" class="page-link">
                        <span class="fa fa-arrow-left"></span>
                    </a>
                </li>
                <?php
                    for($i = 0; $i < $pageNum; $i++){ ?>
                        <li class="page-item <?= $currentPage == $i+1 ? 'active' : '' ?>">
                            <a id="pageLinkTop<?= $i ?>" href="" class="page-link"><?= $i+1 ?></a>
                        </li>
                        <script>
                            $('#pageLinkTop<?= $i ?>').on("click", (e)=>{
                                e.preventDefault();
                                var httpRequest = new XMLHttpRequest();
                                httpRequest.onreadystatechange = ()=>{
                                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                        location.assign("<?= $_SERVER['PHP_SELF'] ?>");
                                    }
                                }
                                httpRequest.open("POST", "admin/setPageNumber.php?postPage=<?= $i+1 ?>");
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
                        httpRequest.onreadystatechange = ()=>{
                            if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                location.assign("<?= $_SERVER['PHP_SELF'] ?>");
                            }
                        }
                        httpRequest.open("POST", "admin/setPageNumber.php?postPage=<?= $currentPage - 1 ?>");
                        httpRequest.send();
                    });
                    $('#pageLinkTopNext').on("click", (e)=>{
                        e.preventDefault();
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.onreadystatechange = ()=>{
                            if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                location.assign("<?= $_SERVER['PHP_SELF'] ?>");
                            }
                        }
                        httpRequest.open("POST", "admin/setPageNumber.php?postPage=<?= $currentPage + 1 ?>");
                        httpRequest.send();
                    });
                </script>
            </ul>
            <div class="section group py-3">
                <div class="flex-column">
                    <?php
                    foreach($posts as $id => $post) {
                        if ($id >= (4 * ($currentPage - 1)) && $id < (4 * $currentPage)) { ?>
                            <div class="post-container p-2 border my-2 rounded">
                                <a href="PostDetail.php?postId=<?= $post->postId ?>"
                                   class="stretched-link"></a>
                                <div class="row">
                                    <div class="col-sm-4 col-lg-3">
                                        <img class="img-thumbnail w-100" src="<?= $post->postImage ?>"
                                             alt="<?= $post->postImage ?>">
                                    </div>
                                    <div class="col-sm-8 col-lg-9">
                                        <div class="post-body">
                                            <div class="font-weight-bold"
                                                 style="font-size: 1.15em;"><?= $post->postTitle ?></div>
                                            <div class="text-justify">
                                                <div id="post-content<?= $id ?>"></div>
                                                <script>
                                                    var el = document.createElement('body')
                                                    el.innerHTML = '<?= $post->postContent ?>';
                                                    var Ptags = el.getElementsByTagName('p');
                                                    for (var x of Ptags) {
                                                        var wordCount = 0;
                                                        for (var i = 0; i < x.innerHTML.length; i++) {
                                                            if (x.innerHTML.charAt(i) == " ") {
                                                                wordCount++;
                                                            }
                                                            if (wordCount >= 50) {
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
                                </div>
                            </div>
                        <?php }
                    }
                    ?>
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
        $('.post-container').css({
            'padding-bottom' : '20px',

        });
    });
</script>
<a href="#" id="toTop"><span id="toTopHover"> </span></a>
</body>
</html>
