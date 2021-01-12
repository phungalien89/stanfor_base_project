<?php
    $bikeMan = new BikeProvider();
    $is_checkout = false;
    if(stripos($_SERVER['PHP_SELF'], "checkout") != false){
        $is_checkout = true;
    }

    $is_postlist = false;
    if(stripos($_SERVER['PHP_SELF'], "postlist") != false){
        $is_postlist = true;
    }

    $is_homepage = false;
    if(stripos($_SERVER['PHP_SELF'], "homepage") != false){
        $is_homepage = true;
    }

    if(isset($_SESSION['cart'])){
        $carts = $_SESSION['cart'];
        foreach($carts as $id => $c){
            if(isset($_REQUEST['btnRemoveItem' . $id])){
                $id = (int) $_POST['removeId' . $id];
                //echo "<script>alert('". $id ."')</script>";
                $bIkd = (int) $_SESSION['cart'][$id];
                $b = $bikeMan->getBikeById($bIkd);
                unset($_SESSION['cart'][$id]);
                array_values($_SESSION['cart']);
                $_SESSION['message'][] = ['title'=>'Giỏ hàng', 'status'=>'info' , 'content'=>'<div>Đã loại bỏ <b>'. $b->bikeName .'</b> khỏi giỏ hàng <span class="fas fa-cart-plus text-danger"></span> thành công</div>'];
                //echo "<script>location.assign('". $_SERVER['PHP_SELF'] ."')</script>";
            }
        }
    }
    if(isset($_REQUEST['btnCheckOut'])){
        $quantity = "";
        $arrInput = [];
        foreach ($_SESSION['cart'] as $id => $cart){
            $arrInput[] = "nudItemCount" . $id;
        }
        foreach($_SESSION['cart'] as $id => $cart){
            $quantity .= $_POST[$arrInput[$id]] . "|";
        }
        $quantity = substr($quantity, 0, strlen($quantity) - 1);
        echo "<script>location.assign('http://localhost:63342/Website/Checkout.php?quantity=". $quantity ."')</script>";
    }

    if(isset($_REQUEST['btnSearch']) || isset($_REQUEST['txtSearch'])){
        $t = $_POST['txtSearch'];
        echo "<script>location.assign('http://localhost:63342/Website/ProductList.php?q=". $t ."')</script>";
    }
?>
<div class="navbar navbar-dark navbar-expand-sm bg-dark">
    <button data-toggle="collapse" data-target="#main_menu" class="navbar-toggler m-2">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="btn-group d-block d-sm-none p-2">
        <div class="input-group">
            <input class="form-control" placeholder="Bạn tìm gì?" type="text" name="txtSearch" id="txtSearch-xs">
            <div class="input-group-append">
                <button data-toggle="tooltip" title="Tìm kiếm" class="btn btn-primary">
                    <span class="fa fa-search"></span>
                </button>
            </div>
        </div>
    </div>
    <div id="main_menu" class="navbar-collapse collapse">
        <ul class="navbar-nav top-menu">
            <li class="nav-item">
                <a href="http://localhost:63342/Website/HomePage.php" class="nav-link <?= $is_homepage ? 'active' : '' ?> rounded-0 px-3 btn btn-dark btn-block text-center">TRANG CHỦ</a>
            </li>
            <li class="nav-item ">
                <a href="" class="nav-link rounded-0 px-3 btn btn-dark btn-block">TIN TỨC</a>
            </li>
            <li class="nav-item ">
                <a href="http://localhost:63342/Website/PostList.php" class="nav-link <?= $is_postlist ? 'active' : '' ?> rounded-0 px-3 btn btn-dark">BLOG</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link rounded-0 px-3 btn btn-dark">VIDEOS</a>
            </li>
        </ul>
    </div>
    <div class="btn-group ml-auto d-none d-sm-block <?= $is_checkout ? 'pr-1' : 'pr-5' ?>">
        <form action="" method="post">
            <div class="input-group search-group">
                <input class="form-control" placeholder="Bạn tìm gì?" type="text" name="txtSearch" id="txtSearch-sm">
                <div class="input-group-append">
                    <button data-toggle="tooltip" title="Tìm kiếm" name="btnSearch" class="btn btn-primary" style="background-color: #009688; border-color: #009688;">
                        <span class="fa fa-search"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div data-toggle="tooltip" title="Xem giỏ hàng" class="btn-group d-none d-sm-block dropdown position-relative">
        <div data-toggle="dropdown" class="view-cart <?= $is_checkout ? 'collapse' : '' ?>" style="cursor: pointer">
            <span class="fas fa-cart-arrow-down"></span>
            <span class="text-uppercase">giỏ hàng</span>
            <span class="badge badge-danger ml-2">
                <?php
                    if(isset($_SESSION['cart'])){
                        echo count($_SESSION['cart']);
                    }
                    else{
                        echo 0;
                    }
                ?>
            </span>
        </div>
        <div class="shadow bg-light dropdown-menu dropdown-menu-right position-absolute" style="width: 350px; max-height: 400px; overflow-x: hidden; overflow-y: auto;">
            <form action="" method="post">
                <div class="p-2 bg-light">
                    <div class="text-uppercase text-center text-light p-2 mt-0" style="background-color: #009688; color: white; font-weight: bold">
                        <span class="fas fa-shopping-cart"></span>
                        <span class="px-2">giỏ hàng</span>
                    </div>
                    <div style="font-size: 1.25em;" class="text-center font-weight-bold <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? 'collapse' : '') : '' ?>">Không có mục nào</div>
                    <?php
                        if(isset($_SESSION['cart'])){
                            if(count($_SESSION['cart']) > 0){
                                $carts = $_SESSION['cart'];
                                $cartTotal = 0;
                                foreach($carts as $id => $bikeId){
                                    $bike = $bikeMan->getBikeById($bikeId);
                                    global $cartTotal;
                                    $cartTotal += $bike->bikeDiscountPrice > 0 ? $bike->bikeDiscountPrice : $bike->bikePrice; ?>
                                    <div class="clear row mb-2 position-relative">
                                        <div class="col-10">
                                            <a href="http://localhost:63342/Website/BikeDetail.php?bikeId=<?= $bikeId ?>" class="stretched-link"></a>
                                            <img class="img-thumbnail mr-3" style="width: 80px; float: left" src="<?= $bike->bikeImage ?>" alt="">
                                            <div class="cart-heading"><?= $bike->bikeName ?></div>
                                            <div class="form-group" style="margin: 0">
                                                <label for="nudItemCount<?= $id ?>">Số lượng</label>
                                                <input onchange="nudItemCount_onchange(event)" type="number" value="1" min="1" max="999" name="nudItemCount<?= $id ?>" id="nudItemCount<?= $id ?>" class="">
                                            </div>
                                            <div id="cartItemPrice<?= $id ?>" class="cart-item-price"><?= $bike->bikeDiscountPrice > 0 ? formatPrice($bike->bikeDiscountPrice) : formatPrice($bike->bikePrice) ?> &#8363;</div>
                                        </div>
                                        <div class="col-2 d-flex flex-column align-content-center justify-content-center">
                                            <button onclick="return clearCart(this.name)" name="btnRemoveItem<?= $id ?>" id="btnRemoveItem<?= $id ?>" class="close">
                                                <span class="fa fa-times text-danger"></span>
                                            </button>
                                            <input type="hidden" name="removeId<?= $id ?>" value="<?= $id ?>">
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                    ?>

                    <div class="cart-footer pt-3 <?= isset($_SESSION['cart']) ? (count($_SESSION['cart']) > 0 ? '' : 'collapse') : 'collapse' ?>">
                        <div class="cart-total">
                            <span class="text-uppercase">tổng tiền</span>
                            <div class="cart-total-price"><?= isset($cartTotal) ? formatPrice($cartTotal) : 0 ?> &#8363;</div>
                        </div>
                        <div class="row pt-2">
                            <button name="btnCheckOut" class="btn btn-info mx-auto">ĐẶT HÀNG</button>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                var countItem = parseInt("<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>");

                function nudItemCount_onchange(e){
                    var totalPrice = 0;
                    for(var i = 0; i < countItem; i++){
                        var priceUnit = parseInt($('#cartItemPrice' + i).text().replace(/,/g, ""));
                        var quantity = $('#nudItemCount' + i).val();
                        totalPrice += priceUnit * quantity;
                    }
                    totalPrice = Intl.NumberFormat('en').format(totalPrice);
                    $('.cart-total-price').html(totalPrice + " &#8363;");
                }

                function clearCart(e){
                    var r = confirm("Bạn muốn xóa sản phẩm này khỏi giỏ hàng?");
                    if(r){
                        var httpRequest = new XMLHttpRequest();
                        httpRequest.open("POST", "http://localhost:63342/Website/AddToCart.php?reset=true&selection=" + "<?= implode('|', $arr_name_type ?? []) ?>" + "|" + "<?= implode('|', $arr_name_brand ?? []) ?>", true);
                        httpRequest.send();
                    }
                    else{
                        return false;
                    }
                }

            </script>
        </div>
    </div>

</div>
