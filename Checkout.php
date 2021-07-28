<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<?php session_start();
    spl_autoload_register(function($class_name){
        if(file_exists("admin/$class_name" . ".php")){
            include "admin/" . $class_name . ".php";
        }
        else{
            $class_name = explode("\\", $class_name);
            $class_name = $class_name[count($class_name) - 1];
            include "vendor/phpmailer/phpmailer/src/" . $class_name . ".php";
        }
    });
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    //phpinfo();


    function formatPrice($price){
        $price = number_format($price, 0);
        return $price;
    }
    function checkData($data){
        $data = htmlspecialchars($data);
        $data = trim($data);
        $data = stripslashes($data);
        return $data;
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
    .invalid-feedback:before{
        content: "\f071";
        font-family: fontAwesome-s;
        padding-right: 0.5em;
    }

</style>
<body>
<?php
    $postMan = new PostProvider();
    $post = null;
    $posts = null;
    $arr_quantity = [];
    $arr = [];
    $carts = [];
    if(isset($_SESSION['cart'])){
        $carts = $_SESSION['cart'];
    }
    if(isset($_REQUEST['quantity'])){
        $arr = explode("|", $_REQUEST['quantity']);
    }
    foreach($arr as $item){
        $arr_quantity[] = intval($item);
    }
    $name = $address = $mobile = $payMethod = "";
    $mes_name = $mes_address = $mes_mobile = $mes_payMethod = "";
    $place_id = "place_id:ChIJXx5qc016FTERvmL-4smwO7A";//default value is place_id of Viet Nam
    //$place_id = "";
    $mapOK = false;
    if(isset($_REQUEST['btnCheckout'])){
        $name = $_POST['txtName'];
        $address = $_POST['txtAddress'];
        $mobile = $_POST['txtMobile'];
        $payMethod = $_POST['rdPayment'];
        $dataOK = true;

        $name = checkData($name);
        if(strlen($name) == 0){
            $mes_name = "Bạn chưa điền tên";
            $dataOK = false;
        }

        $address = checkData($address);
        if(strlen($address) == 0){
            $mes_address = "Địa chỉ không thể để trống";
            $dataOK = false;
        }
        else{
            $url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=". urlencode($address) ."&key=add_your_api_key_here&inputtype=textquery";
            $response = file_get_contents($url);

            $arr = json_decode($response, true);
            //var_dump("Search result = " . $arr['status']);
            //var_dump($arr);
            $status = $arr['status'];
            if ($status == "OK"){
                $place_id = "place_id:" . $arr['candidates'][0]['place_id'];
                $mapOK = true;
            }
            else{
                $mes_address = "Địa chỉ bạn nhập không có thật";
                $dataOK = false;
            }
        }

        $mobile = checkData($mobile);
        if(strlen($mobile) == 0){
            $mes_mobile = "Số điện thoại không thể để trống";
            $dataOK = false;
        }
        else{
            $matched = preg_match("/[0-9]{10,11}+/", $mobile);
            if(!$matched){
                $mes_mobile = "Số điện thoại không hợp lệ (10 hoặc 11 số)";
                $dataOK = false;
            }
        }

        if($dataOK){
            $from = "sales@bikestore.com";
            $to = "phungalien89@gmail.com";
            $subject = "New Order";
            $body = '<!doctype html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport"
                                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                            <meta http-equiv="X-UA-Compatible" content="ie=edge">
                            <title>Đơn hàng mới</title>
                        </head>
                        <style>
                            .main-title{
                                text-align: center;
                                font-size: 2em;
                                color: white;
                                font-weight: bold;
                                padding: 10px 20px;
                                background-color: #009688;
                                text-transform: uppercase;
                            }
                            .table{
                                margin-top: 1em;
                                width: 100%;
                            }
                            table, th, td{
                                border: 1px solid #009688;
                                border-collapse: collapse;
                                padding: 10px 20px;
                            }
                            .table th{
                                background-color: #009688;
                                color: white;
                                font-weight: bold;
                                font-size: 1.25em;
                                text-transform: uppercase;
                                border: 1px solid white;
                            }
                            .table td{
                                text-align: center;
                            }
                            .title1{
                                background-color: #009688;
                                color: white;
                                padding: 10px 20px;
                                font-size: 1.25em;
                                text-transform: uppercase;
                                text-align: center;
                                width: fit-content;
                                margin: 1em auto;
                                font-weight: bold;
                            }
                            .title2{
                                color: #009688;
                                font-size: 1.25em;
                                text-transform: uppercase;
                                width: fit-content;
                                margin: 1em auto;
                                font-weight: bold;
                            }
                            .table1 tr td:first-child{
                                font-weight: bold;
                            }
                        </style>
                        <body>
                            <div class="main-title">Bạn có đơn hàng mới</div>
                            <table class="table">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                </tr>';
            $bikeMan = new BikeProvider();
            $totalPrice = 0;
            foreach($carts as $id => $cart){
                $bike = $bikeMan->getBikeById($cart);
                $price = $bike->bikeDiscountPrice > 0 ? $bike->bikeDiscountPrice : $bike->bikePrice;
                $body .= "<tr>";
                $body .= "<td>" . $id . "</td>";
                $body .= "<td>" . $bike->bikeName . "</td>";
                $body .= "<td>" . $_POST['nudCount' . $id] . "</td>";
                $body .= "<td>" . $price . "</td>";
                $totalPrice += intval($_POST['nudCount' . $id]) * $price;
            }
            $body .= '</table>
                            <div class="title1">Thông tin khách hàng</div>
                            <div class="title2">Thành tiền: <span style="color: red;">'. formatPrice($totalPrice) .'</span></div>
                            <table class="table1">
                                <tr>
                                    <td>Người nhận</td>
                                    <td>'. $name .'</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ</td>
                                    <td>'. $address .'</td>
                                </tr>
                                <tr>
                                    <td>Số điện thoại</td>
                                    <td>'. $mobile .'</td>
                                </tr>
                                <tr>
                                    <td>Hình thức thanh toán</td>
                                    <td>'. ($payMethod == 'bank' ? 'Chuyển khoản' : 'Tiền mặt') .'</td>
                                </tr>
                            </table>
                        </body>
                        </html>';
            $host = "smtp.mailtrap.io";
            $username = "06d9ba905bd8c0";
            $password = "66dafd192d0f26";
            $header = ['From' => $from, 'To' => $to, 'Subject' => $subject];

            try {
                $mail = new PHPMailer(true);
                $mail->setLanguage('vi', 'vendor/phpmailer/phpmailer/language/');
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->CharSet = 'utf-8';
                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->Username = $username;
                $mail->Password = $password;
                $mail->SMTPSecure = 'secret';
                $mail->Port = 2525;

                $mail->setFrom($from, "Admin");
                $mail->addAddress($to, 'Sales');
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->send();

                $name = $address = $mobile = "";
                $payMethod = "bank";
                unset($_SESSION['cart']);
                $_SESSION['message'][] = ['title'=>'Đặt hàng', 'status'=>'success' , 'content'=>'<div>Đặt hàng của bạn đã được đặt thành công.</div><div>Bạn sẽ nhận được cuộc gọi từ chúng tôi để xác nhận đơn hàng.</div>'];
            }
            catch (Exception $ex){
                echo "<script>alert('". $mail->ErrorInfo ."')</script>";
                $_SESSION['message'][] = ['title'=>'Đặt hàng', 'status'=>'danger' , 'content'=>'<div>'. $mail->ErrorInfo .'</div>'];
            }

        }
    }
    //var_dump($carts);
?>
<div class="wrap">
    <div class="header">
        <?php
            include "layout/header.php";
            include "inc/call.php";
        ?>
    </div>

    <div class="main">
        <div class="content">
            <?php
                include "inc/message.php";
                include "inc/scrollToTop.php";
            ?>
            <div class="">
                <div class="bikeHeading">
                    <span><?= 'ĐẶT HÀNG' ?></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="main-title">
                <span>đơn hàng của bạn</span>
            </div>
            <form action="" method="post">
                <div class="section group col-sm-10 col-md-10 col-lg-8" style="padding: 0;">
                    <div class="font-weight-bold <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? 'collapse' : '') : '' ?>">Không có sản phẩm nào</div>
                    <div class="bg-light <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? '' : 'collapse') : 'collapse' ?> <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? 'border' : '') : '' ?>">
                        <fieldset class="p-2">
                            <legend>Sản phẩm của bạn</legend>
                            <div class="row">
                                <div class="col-sm-8">
                                    <?php
                                    $bikeMan = new BikeProvider();
                                    $simpleCarts = array_count_values($carts);
                                    $cartTotal = 0;
                                    foreach($simpleCarts as $bikeId => $quantity){
                                        $bike = $bikeMan->getBikeById($bikeId);
                                        $price = $bike->bikeDiscountPrice > 0 ? $bike->bikeDiscountPrice : $bike->bikePrice;
                                        $cartTotal += $price * $quantity;
                                        ?>
                                        <div class="p-2 row">
                                            <div class="col-sm-3">
                                                <img class="w-100" src="storage/<?= $bike->bikeImage ?>" alt="<?= $bike->bikeImage ?>">
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="d-flex flex-column">
                                                    <div class="cart-heading"><?= $bike->bikeName ?></div>
                                                    <div id="cartItemPrice" class="cart-item-price">Đơn giá: <?= formatPrice($bike->bikeDiscountPrice > 0 ? $bike->bikeDiscountPrice : $bike->bikePrice) ?> &#8363;</div>
                                                    <div class="d-flex align-items-center pt-2">
                                                        <label for="">Số lượng</label>
                                                        <input value="<?= $quantity ?>" min="0" max="999" onchange="calculateTotal(event, '<?= $bike->bikeName ?>')" class="form-control mx-2 text-center" style="width: 100px;" type="number" name="nudCount<?= $bikeId ?>" id="nudCount<?= $bikeId ?>">
                                                        <div class="cart-item-price">
                                                            <div style="color: #009688 !important;">Thành tiền:</div>
                                                            <div><?= formatPrice($price * $quantity) ?> &#8363;</div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    <?php }
                                    ?>
                                </div>
                                <div class="col-sm-4 d-flex flex-column align-items-center justify-content-center">
                                    <div class="  p-2 justify-content-center align-content-center">
                                        <div class="cart-total">
                                            <span class="text-uppercase">tạm tính</span>
                                            <div class="cart-total-price"><?= formatPrice($cartTotal) ?> &#8363;</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="p-2 mt-3 bg-light border <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? '' : 'collapse') : 'collapse' ?>">
                        <fieldset style="width: 100%;">
                            <legend>Thông tin thanh toán</legend>
                            <div class="form-group">
                                <label for="txtName">Họ tên</label>
                                <input class="form-control" value="<?= $name ?>" type="text" name="txtName" id="txtName">
                                <?php
                                if(strlen($mes_name) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_name ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtAddress">Địa chỉ nhận hàng</label>
                                <input class="form-control" value="<?= $address ?>" type="text" name="txtAddress" id="txtAddress">
                                <?php
                                if(strlen($mes_address) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_address ?></div>
                                <?php }
                                ?>
                                <div id="Gmap" class="pt-3">

                                </div>
                                <div class="pt-3 <?= $mapOK ? '' : 'collapse' ?>">
                                    <iframe width="100%" height="350" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBuURuyU9pV82S4sQpfo6c3OaYGyX26mYU&q=<?= $place_id ?>" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="txtMobile">SĐT người nhận</label>
                                <input class="form-control" value="<?= $mobile ?>" type="tel" pattern="[0-9]{10, 11}" name="txtMobile" id="txtMobile">
                                <?php
                                if(strlen($mes_mobile) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_mobile ?></div>
                                <?php }
                                ?>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label for="txtName">Hình thức thanh toán</label>
                                <div class="form-check">
                                    <div class="custom-control custom-radio form-check-inline">
                                        <input value="bank" checked class="custom-control-input" type="radio" name="rdPayment" id="rdPayment1">
                                        <label class="custom-control-label" for="rdPayment1">Chuyển khoản</label>
                                    </div>
                                    <div class="custom-control custom-radio form-check-inline">
                                        <input value="cash" class="custom-control-input" type="radio" name="rdPayment" id="rdPayment2">
                                        <label class="custom-control-label" for="rdPayment2">Tiền mặt</label>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <div id="bankPolicy" class="paymentInfo text-justify" style="display: block">
                                        <div>Quý khách thực hiện chuyển khoản đến tài khoản của chúng tôi như bên dưới để được hưởng ưu đãi.</div>
                                        <div>
                                            <div class="pt-2">
                                                <span class="font-weight-bold">Ngân hàng:</span>
                                                <span>Techcombank</span>
                                            </div>
                                            <div class="pt-2">
                                                <span class="font-weight-bold">Số tài khoản:</span>
                                                <span>19020000123456</span>
                                            </div>
                                            <div class="pt-2">
                                                <span class="font-weight-bold">Chủ tài khoản:</span>
                                                <span>Nguyễn Văn Phụng</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="cashPolicy" class="paymentInfo text-justify" style="display: none">
                                        <div>Quý khách gửi tiền mặt trực tiếp tại cửa hàng</div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="row py-3">
                            <button name="btnCheckout" class="btn btn-success mx-auto">ĐẶT HÀNG</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="footer">
    <?php include "layout/footer.php" ?>
</div>
<script type="text/javascript">
    var countItem = parseInt("<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>");

    function calculateTotal(e, bikeName){
        var httpRequest = new XMLHttpRequest();
        var bikeId = e.target.name.substr("nudCount".length);
        if(e.target.value == 0){
            var r = confirm("Bạn có muốn bỏ sản phẩm "+ bikeName +" khỏi giỏ hàng?")
            if(r){
                httpRequest.open("POST", "AddToCart.php?removeFromCart=" + bikeId);
            }
            else{
                e.target.value = 1;
                return false;
            }
        }
        httpRequest.onreadystatechange = ()=>{
            location.assign("<?= $_SERVER['PHP_SELF'] ?>");
        };
        if(e.target.defaultValue < e.target.value){
            httpRequest.open("POST", "AddToCart.php?addToCart=" + bikeId);
        }
        else{
            httpRequest.open("POST", "AddToCart.php?removeFromCart=" + bikeId);
        }
        httpRequest.send();
    }
    var loadedMap = '<?= isset($_SESSION['loadedMap']) ? 'true' : 'false' ?>';
    if(loadedMap == "false" ){
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition((loc)=>{
                var map = '<iframe width="100%" height="350" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBuURuyU9pV82S4sQpfo6c3OaYGyX26mYU&q='+ loc.coords.latitude + ',' + loc.coords.longitude +'" allowfullscreen></iframe>';
                $('#Gmap').html(map);
                var httpRequest = new XMLHttpRequest();
                httpRequest.open("POST", "admin/geolocation.php?loadedMap=OK", true);
                httpRequest.send();
            });
        }
    }

    $(document).ready(()=>{
        calculateTotal();
        $('#rdPayment1').on("change", (e)=>{
            $(".paymentInfo").css("display", "none");
            if(e.target.value){
                $('#bankPolicy').css('display', "block");
            }
        });
        $('#rdPayment2').on("click", (e)=>{
            $(".paymentInfo").css("display", "none");
            if(e.target.value){
                $('#cashPolicy').css('display', "block");
            }
        });
    })
</script>
<a href="#" id="toTop"><span id="toTopHover"> </span></a>
</body>
</html>
