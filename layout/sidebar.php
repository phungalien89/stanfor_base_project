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
                    <h4>Hãng SX</h4>
                    <?php
                        foreach($brands as $id => $brand){ ?>
                            <li>
                                <button name="btnBrand<?= $id ?>" type="submit" class="btn btn-link"><?= $brand->brandName ?></button>
                            </li>
                        <?php }
                    ?>

                </ul>
            </form>
        </div>
    </div>
    <div class="header_bottom_right">
        <?php
            $bannerMan = new BannerProvider();
            $arrBanner = $bannerMan->getBanner();
            $banner = null;
            if(count($arrBanner) > 0){
                $banner = $arrBanner[0];
            }
            $domDoc = new DOMDocument();
            @$domDoc->loadHTML($banner->bannerContent);
            $arrFig = $domDoc->getElementsByTagName("figure");
            $arrImgSrc = [];
            $arrCaption = [];
            foreach($arrFig as $id => $fig){
                $arrImgSrc[] = $fig->childNodes[0]->getAttribute('src');
                if($fig->childNodes[1]){
                    $arrCaption[] = $fig->childNodes[1]->nodeValue;
                }
                else{
                    $arrCaption[] = "";
                }
            }
        ?>
        <div id="carousel-banner" style="padding:  1px" data-interval="2000" class="carousel slide w-100" data-ride="carousel">
            <ul class="carousel-indicators">
                <?php
                    foreach($arrImgSrc as $id => $src){ ?>
                        <li data-target="#carousel-banner" data-slide-to="<?= $id ?>"></li>
                    <?php }
                ?>
            </ul>
            <div class="carousel-inner">
                <?php
                    foreach($arrImgSrc as $id => $src){ ?>
                        <div class="carousel-item <?= $id == 0 ? 'active' : '' ?>">
                            <img class="w-100" src="<?= $src ?>" alt="<?= $src ?>">
                            <div class="carousel-caption">
                                <h1 class="text-uppercase text-light"><?= $arrCaption[$id] ?></h1>
                            </div>
                        </div>
                    <?php }
                ?>
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