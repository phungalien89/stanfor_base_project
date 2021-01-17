<?php
    $bikeNavBarMan = new BikeProvider();
    $is_login = false;
    $is_register = false;
    $is_userAdmin = false;
    $is_homepage = false;
    $is_adminPage = false;
    $is_logged = false;
    $user = null;
    if(stripos($_SERVER['PHP_SELF'], "login") != false){
        $is_login = true;
    }
    if(stripos($_SERVER['PHP_SELF'], "register") != false){
        $is_register = true;
    }
    if(stripos($_SERVER['PHP_SELF'], "homepage") != false){
        $is_homepage = true;
    }
    if(stripos($_SERVER['PHP_SELF'], "adminpage") != false){
        $is_adminPage = true;
    }
    if(isset($_SESSION['is_logged'])){
        if($_SESSION['is_logged'] == "true"){
            $is_logged = true;
        }
    }
    if(isset($_SESSION['is_userAdmin'])) {
        if($_SESSION['is_userAdmin'] == "true"){
            $is_userAdmin = true;
        }
        else{
            $is_userAdmin = false;
        }
    }
    $userProvider = new UserProvider();
    if(isset($_SESSION['loggedId'])){
        $userId = (int) $_SESSION['loggedId'];
        $user = $userProvider->getUserById($userId);
    }
    if(isset($_REQUEST['btn_logout'])){
        unset($_SESSION['loggedId']);
        if($is_userAdmin){
            unset($_SESSION['is_userAdmin']);
        }
        unset($_SESSION['is_logged']);
        header('location: http://localhost:63342/Website/HomePage.php');
    }

    if(isset($_SESSION['wish'])){
        $wishes = $_SESSION['wish'];
        foreach($wishes as $id => $wish){
            if(isset($_REQUEST['btnRemoveWish' . $id])){
                $id = (int) $_POST['removeWish' . $id];
                $bId1 = $_SESSION['wish'][$id];
                $b1 = $bikeNavBarMan->getBikeById($bId1);
                unset($_SESSION['wish'][$id]);
                array_values($_SESSION['wish']);
                $_SESSION['message'][] = ['title'=>'Yêu thích', 'status'=>'info' , 'content'=>'<div>Đã loại bỏ <b>'. $b1->bikeName .'</b> khỏi yêu thích <span class="fas fa-heart text-danger"></span> thành công</div>'];
            }
        }
    }
?>
<div class="navbar p-1 navbar-dark navbar-expand text-light fixed-top" style="background-color: #009688;">
    <ul class="navbar-nav">
        <li class="nav-item">
            <button id="btnSideMenu" class="btn sidebar-menu nav-link <?= $is_adminPage ? '' : 'collapse' ?>">
                <span class="fas fa-bars" style="font-size: 1.5em;"></span>
            </button>
        </li>
        <li data-toggle="tooltip" title="Trang chủ" class="nav-item <?= $is_homepage ? 'active' : '' ?>" >
            <a href="http://localhost:63342/Website/HomePage.php" class="nav-link">
                <span class="fa fa-home" style="font-size: 1.5em;"></span>
            </a>
        </li>
    </ul>
    <div id="main_menu" class="collapse navbar-collapse ">
        <ul class="navbar-nav align-items-center ml-auto">
            <li data-toggle="tooltip" title="Yêu thích" class="dropdown wish-icon nav-item <?= ($is_login || $is_register) ? 'collapse' : '' ?> <?= isset($_SESSION['wish']) ? (count($_SESSION['wish']) > 0 ? 'active' : '') : '' ?>">
                <a data-toggle="dropdown" href="" class="nav-link">
                    <span class="fa fa-heart" style="font-size: 1.5em;"></span>
                </a>
                <span class="badge badge-danger"><?= isset($_SESSION['wish']) ? count($_SESSION['wish']) : 0 ?></span>
                <div class="dropdown-menu dropdown-menu-right bg-light">
                    <form action="" method="post">
                        <div class="bg-light" style="width: 350px; max-height: 300px; overflow-y: auto; overflow-x: hidden">
                            <div class="text-uppercase text-center text-light p-2 mt-0" style="background-color: #009688; color: white; font-weight: bold">
                                <span class="fas fa-heart"></span>
                                <span class="px-2">yêu thích</span>
                            </div>
                            <div class="text-center font-weight-bold p-2 <?= isset($_SESSION['wish']) ? (count($_SESSION['wish']) > 0 ? 'collapse' : '') : '' ?>">Không có mục nào</div>
                            <?php
                            if(isset($_SESSION['wish'])){
                                $wishes = $_SESSION['wish'];
                                foreach($wishes as $id => $bikeNavBarId){
                                    $bikeNavBar = $bikeNavBarMan->getBikeById($bikeNavBarId); ?>
                                    <div class="p-2 row clear">
                                        <div class="col-10">
                                            <a href="http://localhost:63342/Website/BikeDetail.php?bikeId=<?= $bikeNavBarId ?>" class="stretched-link"></a>
                                            <div class="mr-2" style="width: 80px; float: left">
                                                <img class="w-100 img-thumbnail" src="<?= $bikeNavBar->bikeImage ?>" alt="">
                                            </div>
                                            <div class="">
                                                <div class="cart-heading"><?= $bikeNavBar->bikeName ?></div>
                                                <div style="<?= $bikeNavBar->bikeDiscountPrice > 0 ? 'text-decoration: line-through; color: gray;' : '' ?>" class="cart-item-price <?= $bikeNavBar->bikeDiscountPrice > 0 ? '' : 'collapse' ?>"><?= $bikeNavBar->bikeDiscountPrice > 0 ? formatPrice($bikeNavBar->bikePrice) : '' ?> &#8363;</div>
                                                <div class="cart-item-price"><?= $bikeNavBar->bikeDiscountPrice > 0 ? formatPrice($bikeNavBar->bikeDiscountPrice) : formatPrice($bikeNavBar->bikePrice) ?> &#8363;</div>
                                            </div>
                                        </div>
                                        <div class="col-2 d-flex flex-column justify-content-center">
                                            <button onclick="return clearWish()" class="close" name="btnRemoveWish<?= $id ?>" id="btnRemoveWish<?= $id ?>">
                                                <span class="fa fa-times text-danger"></span>
                                            </button>
                                            <input type="hidden" name="removeWish<?= $id ?>" value="<?= $id ?>">
                                        </div>
                                    </div>
                                <?php }
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </li>
            <li class="nav-item pr-0">
                <img style="width: 45px; height: 45px; display: <?= $user ? "block" : "none" ?>" class="rounded-circle hover-shadow-outline" src="<?= $is_adminPage ? '../' : '' ?><?= isset($user) ? 'storage/' . $user->userImage : "" ?>" alt="<?= $is_adminPage ? '../' : '' ?> <?= isset($user) ? 'storage/' . $user->userImage : "" ?>">
            </li>
            <li class="nav-item <?= ($is_login | $is_logged) ? 'collapse' : '' ?>">
                <a href="http://localhost:63342/Website/login.php" class="nav-link">Đăng nhập</a>
            </li>
            <li class="nav-item <?= ($is_register | $is_logged) ? 'collapse' : '' ?>">
                <a href="http://localhost:63342/Website/register.php" class="nav-link">Đăng ký</a>
            </li>
            <li class="nav-item px-2 <?= $is_adminPage ? 'collapse' : '' ?> <?= $is_userAdmin ? "" : "collapse" ?>">
                <a href="admin/AdminPage.php" class="nav-link"><span class="fa fa-cog" style="font-size: 1.5em;"></span></a>
            </li>
            <li class="nav-item">
                <div class="btn-group">
                    <div class="btn text-light" style="cursor: default"><?= isset($user) ? $user->userDisplayName : "Guest" ?></div>
                    <div class="dropdown <?= $is_logged ? '' : 'collapse' ?>">
                        <button data-toggle="dropdown" class="btn btn-light dropdown-toggle-split">
                            <span class="fa fa-caret-down"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <form action="" method="post">
                                <button type="submit" name="btn_logout" class="dropdown-item btn text-center">Log out</button>
                            </form>
                        </div>
                    </div>

                </div>
            </li>
        </ul>
    </div>
</div>
<script>
    $('[data-toggle="tooltip"]').tooltip();


    function clearWish(){
        var r = confirm("Bạn muốn xóa sản phẩm này khỏi mục yêu thích?");
        if(r){
            var httpRequest = new XMLHttpRequest();
            httpRequest.open("POST", "http://localhost:63342/Website/AddToWish.php?selection=" + "<?= implode('|', $arr_name_type ?? []) ?>" + "|" + "<?= implode('|', $arr_name_brand ?? []) ?>", true);
            httpRequest.send();
        }
        else{
            return false;
        }
    }
</script>