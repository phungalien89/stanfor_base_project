<?php
    $typeMan = new TypeProvider();
    $types = $typeMan->getAllType();

    $brandMan = new BrandProvider();
    $brands = $brandMan->getAllBrand();
    foreach($types as $id => $type){
        if(isset($_REQUEST['btnType' . $id])){
            $_SESSION['filter'] = $type->typeName;
            echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
        }
    }

    foreach($brands as $id => $brand){
        if(isset($_REQUEST['btnBrand' . $id])){
            $_SESSION['filter'] = $brand->brandName;
            echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
        }
    }

    /*if(isset($_REQUEST['btnXeSo'])){
        $_SESSION['filter'] = "xe số";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnXeHybrid'])){
        $_SESSION['filter'] = "xe hybrid";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnXeDien'])){
        $_SESSION['filter'] = "xe điện";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnHonda'])){
        $_SESSION['filter'] = "honda";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnYamaha'])){
        $_SESSION['filter'] = "yamaha";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnSuzuki'])){
        $_SESSION['filter'] = "suzuki";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }
    if(isset($_REQUEST['btnSym'])){
        $_SESSION['filter'] = "sym";
        echo "<script>location.assign('http://localhost:63342/Website/ProductFilter.php')</script>";
    }*/

?>
<div class="header_slide">
    <div class="header_bottom_left">
        <div class="categories">
            <form action="" method="post">
                <ul>
                    <h3>
                        <span class="fa fa-list"></span>
                        <span class="pl-2">Danh mục</span>
                    </h3>
                    <h4>
                        <span class="title">Kiểu xe</span>
                        <span class="title-right"></span>
                    </h4>
                    <?php
                        foreach($types as $id => $type){ ?>
                            <li>
                                <button name="btnType<?= $id ?>" type="submit" class="btn btn-link"><?= $type->typeName ?></button>
                            </li>
                        <?php }
                    ?>
                    <!--<li>
                        <button name="btnXeSo" type="submit" class="btn btn-link">Xe số</button>
                    </li>
                    <li>
                        <button name="btnXeHybrid" type="submit" class="btn btn-link">Xe hybrid</button>
                    </li>
                    <li>
                        <button name="btnXeDien" type="submit" class="btn btn-link">Xe điện</button>
                    </li>-->
                    <h4>Hãng SX</h4>
                    <?php
                        foreach($brands as $id => $brand){ ?>
                            <li>
                                <button name="btnBrand<?= $id ?>" type="submit" class="btn btn-link"><?= $brand->brandName ?></button>
                            </li>
                        <?php }
                    ?>
                    <!--<li>
                        <button name="btnYamaha" type="submit" class="btn btn-link">Yamaha</button>
                    </li>
                    <li>
                        <button name="btnSuzuki" type="submit" class="btn btn-link">Suziki</button>
                    </li>
                    <li>
                        <button name="btnSym" type="submit" class="btn btn-link">Sym</button>
                    </li>-->

                </ul>
            </form>
        </div>
    </div>
    <div class="header_bottom_right">
        <div id="carousel-banner" style="padding:  1px" data-interval="2000" class="carousel slide w-100" data-ride="carousel">
            <ul class="carousel-indicators">
                <li data-target="#carousel-banner" data-slide-to="0"></li>
                <li data-target="#carousel-banner" data-slide-to="1"></li>
            </ul>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="http://localhost:63342/Website/images/Winner X - Chất X.jpg" alt="">
                    <div class="carousel-caption">
                        <h1 class="text-uppercase text-light">Winner X</h1>
                        <div class="text-uppercase">Chất X</div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="http://localhost:63342/Website/images/Air Blade tem mới.jpg" alt="">
                    <div class="carousel-caption">
                        <h1 class="text-uppercase text-light">Air Blade</h1>
                        <div class="text-upppercase">Tem mới</div>
                    </div>
                </div>
            </div>
            <a href="#carousel-banner" data-slide="prev" class="carousel-control-prev">
                <span style="font-size: 2em; color: #009688" class="fa fa-arrow-circle-left"></span>
            </a>
            <a href="#carousel-banner" data-slide="next" class="carousel-control-next">
                <span style="font-size: 2em; color: #009688" class="fa fa-arrow-circle-right"></span>
            </a>
        </div>
    </div>
    <div class="clear"></div>
</div>