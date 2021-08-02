<?php
    spl_autoload_register(function($class_name){
        include "../" . $class_name . ".php";
    });

    //phpinfo();
    //var_dump($_SERVER['DOCUMENT_ROOT']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thêm sản phẩm </title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/all.min.css">
    <script src="../vendor/ckeditor_mine/ckeditor.js"></script>
    <script src="../vendor/ckfinder/ckfinder.js"></script>
</head>
<style>
    @font-face {
        font-family: fontAwesome;
        src: url("../webfonts/fa-solid-900.woff");
    }
    .form-group label:first-child{
        font-weight: bold;
    }
    .invalid-feedback:before{
        content: "\f071";
        font-family: fontAwesome;
        padding-right: 5px;
    }
    .autosave-header{
        background-color: var(--ck-color-toolbar-background);
        border-radius: var(--ck-border-radius);
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border: 1px solid var(--ck-color-toolbar-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5em;
        border-top-width: 0;
    }
    .autosave-server{
        display: flex;
        justify-content: space-between;
        align-items: center;
        column-gap: var(--ck-spacing-medium);
    }
    .autosave-server .fa-clock{
        font-size: 1.25em;
    }
    .addBikeShortDesc-icon:after, .addBikeGift-icon:after, .addBikeHighlight-icon:after, .addBikeSpecs-icon:after, .addBikeGallery-icon:after{
        content: "\f1da";
        font-family: fontAwesome;
        font-size: 1.25em;
    }
    #btnReload_addBikeShortDesc.busy .addBikeShortDesc-icon:after{
        content: "\f2f1";
        display: inline-block;
        animation: ani-reload 1s linear infinite;
    }
    #btnReload_addBikeGift.busy .addBikeGift-icon:after{
        content: "\f2f1";
        display: inline-block;
        animation: ani-reload 1s linear infinite;
    }
    #btnReload_addBikeHighlight.busy .addBikeHighlight-icon:after{
        content: "\f2f1";
        display: inline-block;
        animation: ani-reload 1s linear infinite;
    }
    #btnReload_addBikeSpecs.busy .addBikeSpecs-icon:after{
        content: "\f2f1";
        display: inline-block;
        animation: ani-reload 1s linear infinite;
    }
    #btnReload_addBikeGallery.busy .addBikeGallery-icon:after{
        content: "\f2f1";
        display: inline-block;
        animation: ani-reload 1s linear infinite;
    }
    @keyframes ani-reload {
        to{transform: rotate(360deg)}
    }
    .autosave-status-content{
        display: flex;
        align-items: center;
    }
    .autosave-status-label:after{
        content: "Saved!";
        color: var(--success);
        padding-right: var(--ck-spacing-medium);
    }
    .autosave-status-spinner{
        display: none;
    }
    .autosave-status.busy .autosave-status-label:after{
        content: "Saving...";
        color: var(--danger);
    }
    .autosave-status.busy .autosave-status-spinner{
        display: block;
        animation: ani-autosave-status-spinner 1s linear infinite;
    }

    @keyframes ani-autosave-status-spinner {
        to{transform: rotate(360deg)}
    }
</style>
<body>
<?php
    // include composer autoload
    include '../vendor/autoload.php';

    // import the Intervention Image Manager Class
    use Intervention\Image\ImageManager;

    // create an image manager instance with favored driver
    $manager = new ImageManager(array('driver' => 'imagick'));

    $brandProvider = new BrandProvider();
    $brands = $brandProvider->getAllBrand();

    $typeProvider = new TypeProvider();
    $types = $typeProvider->getAllType();
?>
<?php

    $bikeName = $bikeBrand = $bikeType = $bikeImage = $bikeShortDesc = $bikeGift = $bikeHighlight = $bikeSpecs = $bikeGallery = "";
    $bikePrice = $bikeDiscountPrice = 0;
    $mes_bike_name = $mes_bike_brand = $mes_bike_type = $mes_bike_price = $mes_bike_discount_price = $mes_bike_image = $mes_bike_short_desc = $mes_bike_gift = $mes_bike_highlight = $mes_bike_specs = $mes_bike_gallery = "";
    $dataOK = true;
    $bikeProvider = new BikeProvider();

    if(isset($_REQUEST['btnBikeEditReturn'])){
        unset($_SESSION['bike_action']);
        echo "<script>location.assign('/Website/admin/AdminPage.php')</script>";
    }

    if(isset($_SESSION['bikeId'])){
        $bike = $bikeProvider->getBikeById((int)$_SESSION['bikeId']);
        $bikeName = $bike->bikeName;
        $bikeBrand = $bike->bikeBrand;
        $bikeType = $bike->bikeType;
        $bikePrice = $bike->bikePrice;
        $bikeDiscountPrice = $bike->bikeDiscountPrice;
        $bikeShortDesc = $bike->bikeShortDesc;
        $bikeGift = $bike->bikeGift;
        $bikeHighlight = $bike->bikeHighlight;
        $bikeSpecs = $bike->bikeSpecs;
        $bikeGallery = $bike->bikeGallery;
        $bikeImage = $bike->bikeImage;
    }

    if(isset($_REQUEST['btnBikeReturn'])){
        unset($_SESSION['bike_action']);
        echo "<script>location.assign('/Website/admin/AdminPage.php')</script>";
    }

    if(isset($_REQUEST['btnUpdateBike'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            function checkData($data){
                $data = htmlspecialchars($data);
                $data = trim($data);
                $data = stripslashes($data);
                return $data;
            }
            $bikeName = $_POST['txtBikeName'];
            $bikeBrand = $_POST['comboBikeBrand'];
            $bikeType = $_POST['comboBikeType'];
            $bikePrice = $_POST['txtBikePrice'];
            $bikeDiscountPrice = $_POST['txtBikeDiscountPrice'];
            $bikeShortDesc = $_POST['txtBikeShortDesc'];
            $bikeGift = $_POST['txtBikeGift'];
            $bikeHighlight = $_POST['txtBikeHighlight'];
            $bikeSpecs = $_POST['txtBikeSpecs'];
            $bikeGallery = $_POST['txtBikeGallery'];
            $image = $_FILES['txtBikeImage'];
            $dir = "uploads/bike/";

            //processing bikeName
            $bikeName = checkData($bikeName);
            if(strlen($bikeName) == 0){
                $mes_bike_name = "Tên xe không thể để trống";
                $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_name"];
                $dataOK = false;
            }
            else{
                if(strlen($bikeName) > 50){
                    $mes_bike_name = "Tên xe dài tối đa 50 ký tự";
                    $mes_bike_name .= " Độ dài hiện tại là " . strlen($bikeName) . " ký tự";
                    $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_name"];
                    $dataOK = false;
                }
            }//bikeName

            //processing bikePrice
            $bikePrice = checkData($bikePrice);
            if(strlen($bikePrice) == 0){
                $mes_bike_price = "Giá không thể để trống";
                $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_price"];
                $dataOK = false;
            }
            else{
                if(strlen($bikePrice) > 12){
                    $mes_bike_price = "Giá dài tối đa 12 ký tự";
                    $mes_bike_price .= " Độ dài hiện tại là " . strlen($bikePrice) . " ký tự";
                    $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_price"];
                    $dataOK = false;
                }

            }//bikePrice

            //processing bikeDiscountPrice
            $bikeDiscountPrice = checkData($bikeDiscountPrice);
            if(strlen($bikeDiscountPrice) > 12){
                $mes_bike_discount_price = "Giá chiết khấu dài tối đa 12 ký tự";
                $mes_bike_discount_price .= " Độ dài hiện tại là " . strlen($bikeDiscountPrice) . " ký tự";
                $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_discount_price"];
                $dataOK = false;
            }//bikeDiscountPrice

            $image_type = basename($image['type']);
            if(strlen(basename($image['name'])) > 0){
                if($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "bmp"){
                    $mess_bike_image = "Ảnh phải có định dạng jpg, jpeg, png hoặc bmp";
                    $mess_bike_image .= " Định dạng hiện tại là " . $image_type;
                    $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_image"];
                    $dataOK = false;
                }
                else{
                    if(file_exists("../storage/" . $dir . basename($image['name']))){
                        $mes_bike_image = "Ảnh đại diện đã tồn tại. Hãy chọn ảnh có tên khác";
                        $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'danger', 'content'=>"$mes_bike_image"];
                        $dataOK = false;
                    }
                }
            }


            if($dataOK){
                if(strlen(basename($image['name'])) > 0){
                    $file = $_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\bike\\" . basename($image['name']);
                    $upload_stat = move_uploaded_file($image['tmp_name'], $file);
                    // to finally create image instances
                    $img = $manager->make($_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\bike\\" . basename($image['name']))->fit(1200);
                    $img->save();

                    $filePath = "/storage/" . $dir . $bikeImage;
                    $filePath = str_replace("/", "\\", $filePath);
                    unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the bike image
                    $bikeImage = $dir . basename($image['name']);
                }

                $bike = new Bike();
                $bike->bikeId = (int) $_SESSION['bikeId'];
                $bike->bikeName = $bikeName;
                $bike->bikeBrand = $bikeBrand;
                $bike->bikeType = $bikeType;
                $bike->bikePrice = (int) $bikePrice;
                $bike->bikeDiscountPrice = (int) $bikeDiscountPrice;
                $bike->bikeShortDesc = $bikeShortDesc;
                $bike->bikeGift = $bikeGift;
                $bike->bikeHighlight = $bikeHighlight;
                $bike->bikeSpecs = $bikeSpecs;
                $bike->bikeGallery = $bikeGallery;
                $bike->bikeImage = $bikeImage;
                $bikeProvider->updateBike($bike);

                unset($_SESSION['bike_action']);
                $_SESSION['message'][] = ['title'=>'Cập nhật sản phẩm', 'status'=>'success', 'content'=>'Đã cập nhật <b>'. $bike->bikeName .'</b> thành công!'];
                echo "<script>location.assign('/Website/admin/AdminPage.php')</script>";
            }

        }
    }

?>
    <div class="container">
        <div class="row pt-4">
            <div class="col-sm-12 col-md-10 col-lg-10 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light text-center">
                        <h3>CẬP NHẬT SẢN PHẨM</h3>
                        <div>Nhập thông tin bên dưới để cập nhật</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtBikeName">Tên sản phẩm</label>
                                <input value="<?= $bikeName ?>" class="form-control" type="text" name="txtBikeName" id="txtBikeName">
                                <?php
                                    if(strlen($mes_bike_name) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_bike_name ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="comboBikeBrand">Hãng sản xuất</label>
                                <select name="comboBikeBrand" id="comboBikeBrand" class="custom-select">
                                    <?php
                                        foreach($brands as $id => $brand){ ?>
                                            <option <?= $brand->brandName == $bikeBrand ? "selected" : "" ?> value="<?= $brand->brandName ?>"><?= $brand->brandName; ?></option>
                                        <?php }
                                    ?>
                                </select>
                                <?php
                                    if(strlen($mes_bike_brand) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_bike_brand ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="comboBikeType">Kiểu xe</label>
                                <select name="comboBikeType" id="comboBikeType" class="custom-select">
                                    <?php
                                        foreach($types as $id => $type){ ?>
                                            <option <?= $type->typeName == $bikeType ? "selected" : "" ?> value="<?= $type->typeName ?>"><?= $type->typeName ?></option>
                                        <?php }
                                    ?>
                                </select>
                                <?php
                                if(strlen($mes_bike_type) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_type ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikePrice">Giá</label>
                                <input value="<?= $bikePrice ?>" class="form-control" min="0" type="number" name="txtBikePrice" id="txtBikePrice">
                                <?php
                                if(strlen($mes_bike_price) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_price ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeDiscountPrice">Giá khuyến mãi</label>
                                <input value="<?= $bikeDiscountPrice ?>" class="form-control" min="0" type="number" name="txtBikeDiscountPrice" id="txtBikeDiscountPrice">
                                <?php
                                if(strlen($mes_bike_discount_price) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_discount_price ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeShortDesc">Mô tả ngắn</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBikeShortDesc" id="txtBikeShortDesc" cols="30" rows="10" style="resize: vertical"><?= $bikeShortDesc ?></textarea>
                                    <div class="autosave-header">
                                        <div class="autosave-server">
                                            <span class="far fa-clock text-primary"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control" value="500" type="number" name="" id="autosave-bikeShortDesc-interval" min="500" max="100000" step="500">
                                            <span>(ms)</span>
                                        </div>
                                        <div class="autosave-status autosave-status-short-desc">
                                            <span class="font-weight-bold">Status</span>
                                            <div class="autosave-status-content">
                                                <span class="autosave-status-label"></span>
                                                <span class="autosave-status-spinner fa fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" data-toggle="tooltip" title="Reload data from server" class="btn btn-primary" id="btnReload_addBikeShortDesc">
                                            <span class="addBikeShortDesc-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_bike_short_desc) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_short_desc ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeGift">Quà tặng kèm</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBikeGift" id="txtBikeGift" cols="30" rows="10" style="resize: vertical"><?= $bikeGift ?></textarea>
                                    <div class="autosave-header">
                                        <div class="autosave-server">
                                            <span class="far fa-clock text-primary"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control"
                                                   value="500" type="number" name="" id="autosave-bikeGift-interval" min="500"
                                                   max="100000" step="500">
                                            <span>(ms)</span>
                                        </div>
                                        <div class="autosave-status autosave-status-gift">
                                            <span class="font-weight-bold">Status</span>
                                            <div class="autosave-status-content">
                                                <span class="autosave-status-label"></span>
                                                <span class="autosave-status-spinner fa fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" data-toggle="tooltip" title="Reload data from server"
                                                class="btn btn-primary" id="btnReload_addBikeGift">
                                            <span class="addBikeGift-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_bike_gift) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_gift ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeHighlight">Đặc điểm nổi bật</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBikeHighlight" id="txtBikeHighlight" cols="30" rows="10" style="resize: vertical"><?= $bikeHighlight ?></textarea>
                                    <div class="autosave-header">
                                        <div class="autosave-server">
                                            <span class="far fa-clock text-primary"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control"
                                                   value="500" type="number" name="" id="autosave-bikeHighlight-interval" min="500"
                                                   max="100000" step="500">
                                            <span>(ms)</span>
                                        </div>
                                        <div class="autosave-status autosave-status-highlight">
                                            <span class="font-weight-bold">Status</span>
                                            <div class="autosave-status-content">
                                                <span class="autosave-status-label"></span>
                                                <span class="autosave-status-spinner fa fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" data-toggle="tooltip" title="Reload data from server"
                                                class="btn btn-primary" id="btnReload_addBikeHighlight">
                                            <span class="addBikeHighlight-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_bike_highlight) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_highlight ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeSpecs">Thông số kỹ thuật</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBikeSpecs" id="txtBikeSpecs" cols="30" rows="10" style="resize: vertical"><?= $bikeSpecs ?></textarea>
                                    <div class="autosave-header">
                                        <div class="autosave-server">
                                            <span class="far fa-clock text-primary"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control"
                                                   value="500" type="number" name="" id="autosave-bikeSpecs-interval" min="500"
                                                   max="100000" step="500">
                                            <span>(ms)</span>
                                        </div>
                                        <div class="autosave-status autosave-status-specs">
                                            <span class="font-weight-bold">Status</span>
                                            <div class="autosave-status-content">
                                                <span class="autosave-status-label"></span>
                                                <span class="autosave-status-spinner fa fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" data-toggle="tooltip" title="Reload data from server"
                                                class="btn btn-primary" id="btnReload_addBikeSpecs">
                                            <span class="addBikeSpecs-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_bike_specs) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_specs ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBikeGallery">Thư viện ảnh</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBikeGallery" id="txtBikeGallery" cols="30" rows="10" style="resize: vertical"><?= $bikeGallery ?></textarea>
                                    <div class="autosave-header">
                                        <div class="autosave-server">
                                            <span class="far fa-clock text-primary"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control"
                                                   value="500" type="number" name="" id="autosave-bikeGallery-interval" min="500"
                                                   max="100000" step="500">
                                            <span>(ms)</span>
                                        </div>
                                        <div class="autosave-status autosave-status-gallery">
                                            <span class="font-weight-bold">Status</span>
                                            <div class="autosave-status-content">
                                                <span class="autosave-status-label"></span>
                                                <span class="autosave-status-spinner fa fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" data-toggle="tooltip" title="Reload data from server"
                                                class="btn btn-primary" id="btnReload_addBikeGallery">
                                            <span class="addBikeGallery-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_bike_gallery) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_gallery ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group">
                                <label>Ảnh đại diện</label>
                                <div class="custom-control custom-file">
                                    <input class="custom-file-input" type="file" name="txtBikeImage" id="txtBikeImage">
                                    <label id="bike_file_label" for="txtBikeImage" class="custom-file-label">Chọn file để upload</label>
                                </div>
                                <div class="row mx-auto w-50 pt-3">
                                    <img id="bike_file_image" class="rounded-circle w-100" src="<?= '../storage/' . $bikeImage ?>" alt="<?= $bikeImage ?>">
                                </div>
                                <?php
                                if(strlen($mes_bike_image) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_bike_image ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnUpdateBike" name="btnUpdateBike" class="btn btn-outline-primary">
                                        <span class="fa fa-check"></span>
                                        <span class="text-uppercase">Cập nhật</span>
                                    </button>
                                    <button type="submit" id="btnBikeEditReturn" name="btnBikeEditReturn" class="btn btn-outline-danger ml-auto">
                                        <span class="fas fa-undo-alt"></span>
                                        <span class="text-uppercase">Trở lại</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    var intervalId_bike_short_desc = null;
    var intervalId_bike_gift = null;
    var intervalId_bike_highlight = null;
    var intervalId_bike_specs = null;
    var intervalId_bike_gallery = null;
    var savingInterval = 10000;

    //Creating editor SHORT_DESC
    var originEditor_bike_short_desc = null;
    let edit_bike_short_desc_Editor = document.querySelector('#txtBikeShortDesc');
    ClassicEditor.create(edit_bike_short_desc_Editor, {
        ckfinder:{
            uploadUrl: '/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        fontFamily: {
            options: [
                'default',
                'Ubuntu, sans-serif',
                'Ubuntu Mono, monospace'
            ],
            //supportAllValues: true,
        },
        fontSize:{
            options: ['default', 9, 10, 11, 12, 16, 18, 20, 24, 32, 40, 48],
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify'],
        },
        image: {
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
            resizeUnit: "%",
            resizeOptions: [
                {
                    name: 'imageResize:original',
                    value: null,
                    label: 'Original size'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:25%',
                    value: '25',
                    label: '25%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:50',
                    value: '50',
                    label: '50%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:75',
                    value: '75',
                    label: '75%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                } ],
            toolbar: [
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
                '|',
                'imageResize',
                '|',
                'imageTextAlternative'
            ],
        },
        toolbar:['ckfinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
            '|', 'blockQuote', 'insertTable', 'mediaEmbed',
        ],
    })
        .then(edit_bike_short_desc_Editor => {
            originEditor_bike_short_desc = edit_bike_short_desc_Editor;
            startTimer_shortDesc(savingInterval);
        })
        .catch(error => {
            console.log("Error at initializing Editor: " + error);
        });

    //Creating editor GIFT
    var originEditor_bike_gift = null;
    let edit_bike_gift_Editor = document.querySelector('#txtBikeGift');
    ClassicEditor.create(edit_bike_gift_Editor, {
        ckfinder:{
            uploadUrl: '/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        fontFamily: {
            options: [
                'default',
                'Ubuntu, sans-serif',
                'Ubuntu Mono, monospace'
            ],
            //supportAllValues: true,
        },
        fontSize:{
            options: ['default', 9, 10, 11, 12, 16, 18, 20, 24, 32, 40, 48],
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify'],
        },
        image: {
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
            resizeUnit: "%",
            resizeOptions: [
                {
                    name: 'imageResize:original',
                    value: null,
                    label: 'Original size'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:25%',
                    value: '25',
                    label: '25%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:50',
                    value: '50',
                    label: '50%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:75',
                    value: '75',
                    label: '75%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                } ],
            toolbar: [
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
                '|',
                'imageResize',
                '|',
                'imageTextAlternative'
            ],
        },
        toolbar:['ckfinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
            '|', 'blockQuote', 'insertTable', 'mediaEmbed',
        ],
    })
        .then(edit_bike_gift_Editor => {
            originEditor_bike_gift = edit_bike_gift_Editor;
            startTimer_gift(savingInterval);
        })
        .catch(error => {
            console.log("Error at initializing Editor: " + error);
        });
    //Creating editor HIGHLIGHT
    var originEditor_bike_highlight = null;
    let edit_bike_highlight_Editor = document.querySelector('#txtBikeHighlight');
    ClassicEditor.create(edit_bike_highlight_Editor, {
        ckfinder:{
            uploadUrl: '/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        fontFamily: {
            options: [
                'default',
                'Ubuntu, sans-serif',
                'Ubuntu Mono, monospace'
            ],
            //supportAllValues: true,
        },
        fontSize:{
            options: ['default', 9, 10, 11, 12, 16, 18, 20, 24, 32, 40, 48],
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify'],
        },
        image: {
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
            resizeUnit: "%",
            resizeOptions: [
                {
                    name: 'imageResize:original',
                    value: null,
                    label: 'Original size'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:25%',
                    value: '25',
                    label: '25%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:50',
                    value: '50',
                    label: '50%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:75',
                    value: '75',
                    label: '75%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                } ],
            toolbar: [
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
                '|',
                'imageResize',
                '|',
                'imageTextAlternative'
            ],
        },
        toolbar:['ckfinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
            '|', 'blockQuote', 'insertTable', 'mediaEmbed',
        ],
    })
        .then(edit_bike_highlight_Editor => {
            originEditor_bike_highlight = edit_bike_highlight_Editor;
            startTimer_highlight(savingInterval);
        })
        .catch(error => {
            console.log("Error at initializing Editor: " + error);
        });

    //Creating editor SPECS
    var originEditor_bike_specs = null;
    let edit_bike_specs_Editor = document.querySelector('#txtBikeSpecs');
    ClassicEditor.create(edit_bike_specs_Editor, {
        ckfinder:{
            uploadUrl: '/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        fontFamily: {
            options: [
                'default',
                'Ubuntu, sans-serif',
                'Ubuntu Mono, monospace'
            ],
            //supportAllValues: true,
        },
        fontSize:{
            options: ['default', 9, 10, 11, 12, 16, 18, 20, 24, 32, 40, 48],
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify'],
        },
        image: {
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
            resizeUnit: "%",
            resizeOptions: [
                {
                    name: 'imageResize:original',
                    value: null,
                    label: 'Original size'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:25%',
                    value: '25',
                    label: '25%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:50',
                    value: '50',
                    label: '50%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:75',
                    value: '75',
                    label: '75%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                } ],
            toolbar: [
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
                '|',
                'imageResize',
                '|',
                'imageTextAlternative'
            ],
        },
        toolbar:['ckfinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
            '|', 'blockQuote', 'insertTable', 'mediaEmbed',
        ],
    })
        .then(edit_bike_specs_Editor => {
            originEditor_bike_specs = edit_bike_specs_Editor;
            startTimer_specs(savingInterval);
        })
        .catch(error => {
            console.log("Error at initializing Editor: " + error);
        });

    //Creating editor GALLERY
    var originEditor_bike_gallery = null;
    let edit_bike_gallery_Editor = document.querySelector('#txtBikeGallery');
    ClassicEditor.create(edit_bike_gallery_Editor, {
        ckfinder:{
            uploadUrl: '/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
        },
        fontFamily: {
            options: [
                'default',
                'Ubuntu, sans-serif',
                'Ubuntu Mono, monospace'
            ],
            //supportAllValues: true,
        },
        fontSize:{
            options: ['default', 9, 10, 11, 12, 16, 18, 20, 24, 32, 40, 48],
        },
        alignment: {
            options: ['left', 'center', 'right', 'justify'],
        },
        image: {
            styles: ['alignLeft', 'alignCenter', 'alignRight'],
            resizeUnit: "%",
            resizeOptions: [
                {
                    name: 'imageResize:original',
                    value: null,
                    label: 'Original size'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:25%',
                    value: '25',
                    label: '25%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:50',
                    value: '50',
                    label: '50%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                },
                {
                    name: 'imageResize:75',
                    value: '75',
                    label: '75%'
                    // Note: add the "icon" property if you're configuring a standalone button.
                } ],
            toolbar: [
                'imageStyle:alignLeft',
                'imageStyle:alignCenter',
                'imageStyle:alignRight',
                '|',
                'imageResize',
                '|',
                'imageTextAlternative'
            ],
        },
        toolbar:['ckfinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
            '|', 'blockQuote', 'insertTable', 'mediaEmbed',
        ],
    })
        .then(edit_bike_gallery_Editor => {
            originEditor_bike_gallery = edit_bike_gallery_Editor;
            startTimer_specs(savingInterval);
        })
        .catch(error => {
            console.log("Error at initializing Editor: " + error);
        });

    //Creating startTimer SHORT_DESC
    var savedData_shortDesc = "";
    var saved_shortDesc = true;
    var saved_gift = true;
    var httpRequest_shortDesc = new XMLHttpRequest();
    function startTimer_shortDesc(interval){
        intervalId_bike_short_desc = setInterval(()=>{
            var e = window.originEditor_bike_short_desc;
            //console.log("Data__short_desc = " + e.getData());
            if(e.getData() != savedData_shortDesc && e.getData() != ""){
                saved_shortDesc = false;
            }
            httpRequest_shortDesc.onreadystatechange = ()=>{
                //console.log("SHORT_DESC = " + httpRequest_shortDesc.readyState + " | " + httpRequest_shortDesc.status);
                if(httpRequest_shortDesc.readyState == 4 && httpRequest_shortDesc.status == 200){
                    console.log("Response__short_desc = " + httpRequest_shortDesc.responseText);
                    var data = httpRequest_shortDesc.responseText;
                    if(e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o") == data){
                        saved_shortDesc = true;
                        savedData_shortDesc = e.getData();
                    }
                    $('.autosave-status-short-desc').removeClass("busy");
                }
            };
            if(!saved_shortDesc){
                httpRequest_shortDesc.open("POST", "/Website/admin/autosave.php?edit_bike_short_desc=" + e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o"), true);
                httpRequest_shortDesc.send();
                $('.autosave-status-short-desc').addClass("busy");
            }
        }, interval);
    }
    var httpRequest_gift = new XMLHttpRequest();
    var savedData_gift = "";
    function startTimer_gift(interval){
        intervalId_bike_gift = setInterval(()=>{
            var e = window.originEditor_bike_gift;
            //console.log("Data__gift = " + e.getData());
            if(e.getData() != savedData_gift && e.getData() != ""){
                saved_gift = false;
            }
            httpRequest_gift.onreadystatechange = ()=>{
                if(httpRequest_gift.readyState == 4 && httpRequest_gift.status == 200){
                    console.log("Response__gift = " + httpRequest_gift.responseText);
                    var data = httpRequest_gift.responseText;
                    if(e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o") == data){
                        saved_gift = true;
                        savedData_gift = e.getData();
                    }
                    $('.autosave-status-gift').removeClass("busy");
                }
            };
            if(!saved_gift){
                httpRequest_gift.open("POST", "/Website/admin/autosave.php?edit_bike_gift=" + e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o"), true);
                httpRequest_gift.send();
                $('.autosave-status-gift').addClass("busy");
            }
        }, interval);
    }

    var httpRequest_highlight = new XMLHttpRequest();
    var savedData_highlight = "";
    var saved_highlight = true;
    function startTimer_highlight(interval){
        intervalId_bike_highlight = setInterval(()=>{
            var e = window.originEditor_bike_highlight;
            //console.log("Data__highlight = " + e.getData());
            if(e.getData() != savedData_highlight && e.getData() != ""){
                saved_highlight = false;
            }
            httpRequest_highlight.onreadystatechange = ()=>{
                if(httpRequest_highlight.readyState == 4 && httpRequest_highlight.status == 200){
                    console.log("Response__highlight = " + httpRequest_highlight.responseText);
                    var data = httpRequest_highlight.responseText;
                    if(e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o") == data){
                        saved_highlight = true;
                        savedData_highlight = e.getData();
                    }
                    $('.autosave-status-highlight').removeClass("busy");
                }
            };
            if(!saved_highlight){
                httpRequest_highlight.open("POST", "/Website/admin/autosave.php?edit_bike_highlight=" + e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o"), true);
                httpRequest_highlight.send();
                $('.autosave-status-highlight').addClass("busy");
            }
        }, interval);
    }

    var httpRequest_specs = new XMLHttpRequest();
    var savedData_specs = "";
    var saved_specs = true;
    function startTimer_specs(interval){
        intervalId_bike_specs = setInterval(()=>{
            var e = window.originEditor_bike_specs;
            //console.log("Data__specs = " + e.getData());
            if(e.getData() != savedData_specs && e.getData() != ""){
                saved_specs = false;
            }
            httpRequest_specs.onreadystatechange = ()=>{
                if(httpRequest_specs.readyState == 4 && httpRequest_specs.status == 200){
                    console.log("Response__specs = " + httpRequest_specs.responseText);
                    var data = httpRequest_specs.responseText;
                    if(e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o") == data){
                        saved_specs = true;
                        savedData_specs = e.getData();
                    }
                    $('.autosave-status-specs').removeClass("busy");
                }
            };
            if(!saved_specs){
                httpRequest_specs.open("POST", "/Website/admin/autosave.php?edit_bike_specs=" + e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o"), true);
                httpRequest_specs.send();
                $('.autosave-status-specs').addClass("busy");
            }
        }, interval);
    }

    var httpRequest_gallery = new XMLHttpRequest();
    var savedData_gallery = "";
    var saved_gallery = true;
    function startTimer_gallery(interval){
        intervalId_bike_gallery = setInterval(()=>{
            var e = window.originEditor_bike_gallery;
            //console.log("Data__gallery = " + e.getData());
            if(e.getData() != savedData_gallery && e.getData() != ""){
                saved_gallery = false;
            }
            httpRequest_gallery.onreadystatechange = ()=>{
                if(httpRequest_gallery.readyState == 4 && httpRequest_gallery.status == 200){
                    console.log("Response__gallery = " + httpRequest_gallery.responseText);
                    var data = httpRequest_gallery.responseText;
                    if(e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o") == data){
                        saved_gallery = true;
                        savedData_gallery = e.getData();
                    }
                    $('.autosave-status-gallery').removeClass("busy");
                }
            };
            if(!saved_gallery){
                httpRequest_gallery.open("POST", "/Website/admin/autosave.php?edit_bike_gallery=" + e.getData().replace(/&nbsp;/g, "").replace(/%/g, "o_o"), true);
                httpRequest_gallery.send();
                $('.autosave-status-gallery').addClass("busy");
            }
        }, interval);
    }

    $(document).ready(()=>{
        $("[data-toggle = 'tooltip']").tooltip();
        $('input[step=500]').val(savingInterval);

        $('#txtBikeImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#bike_file_label').html(name);
                $('#bike_file_image').attr("src", path);
                $('#bike_file_image').addClass("img-thumbnail");
            },
        });

        $('#autosave-bikeShortDesc-interval').on({
            "change" : (e)=> {
                var interval = parseInt(e.target.value);
                clearInterval(intervalId_bike_short_desc);
                startTimer_shortDesc(interval);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    e.preventDefault();
                    window.originEditor_bike_short_desc.focus();
                }
            },
        });

        $('#autosave-bikeGift-interval').on({
            "change" : (e)=> {
                var interval = parseInt(e.target.value);
                clearInterval(intervalId_bike_gift);
                startTimer_gift(interval);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    e.preventDefault();
                    window.originEditor_bike_gift.focus();
                }
            }
        });

        $('#autosave-bikeHighlight-interval').on({
            "change" : (e)=> {
                var interval = parseInt(e.target.value);
                clearInterval(intervalId_bike_highlight);
                startTimer_highlight(interval);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    e.preventDefault();
                    window.originEditor_bike_highlight.focus();
                }
            }
        });

        $('#autosave-bikeSpecs-interval').on({
            "change" : (e)=> {
                var interval = parseInt(e.target.value);
                clearInterval(intervalId_bike_specs);
                startTimer_specs(interval);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    e.preventDefault();
                    window.originEditor_bike_specs.focus();
                }
            },
        });

        $('#autosave-bikeGallery-interval').on({
            "change" : (e)=> {
                var interval = parseInt(e.target.value);
                clearInterval(intervalId_bike_gallery);
                startTimer_gallery(interval);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    e.preventDefault();
                    window.originEditor_bike_gallery.focus();
                }
            },
        });



        $('#btnReload_addBikeShortDesc').on("click", ()=>{
            var r = confirm("All data in this textarea will be replaced by data on server?");
            if(r){
                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = ()=>{
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        var data = httpRequest.responseText.replace(/o_o/g, "%");
                        window.originEditor_bike_short_desc.setData(data);
                        $('#btnReload_addBikeShortDesc').removeClass("busy");
                    }
                };
                httpRequest.open("GET", "/Website/admin/autosave.php?get_edit_bike_short_desc=true", true);
                httpRequest.send();
                $('#btnReload_addBikeShortDesc').addClass("busy");
            }
        });

        $('#btnReload_addBikeGift').on("click", ()=>{
            if(confirm("All data in this textarea will be replaced by data on server?")){
                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = ()=>{
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        var data = httpRequest.responseText.replace(/o_o/g, "%");
                        window.originEditor_bike_gift.setData(data);
                        $('#btnReload_addBikeGift').removeClass("busy");
                    }
                };
                httpRequest.open("GET", "/Website/admin/autosave.php?get_edit_bike_gift=true", true);
                httpRequest.send();
                $('#btnReload_addBikeGift').addClass("busy");
            }

        });

        $('#btnReload_addBikeHighlight').on("click", ()=>{
            if(confirm("All data in this textarea will be replaced by data on server?")){
                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = ()=>{
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        var data = httpRequest.responseText.replace(/o_o/g, "%");
                        window.originEditor_bike_highlight.setData(data);
                        $('#btnReload_addBikeHighlight').removeClass("busy");
                    }
                };
                httpRequest.open("GET", "/Website/admin/autosave.php?get_edit_bike_highlight=true", true);
                httpRequest.send();
                $('#btnReload_addBikeHighlight').addClass("busy");
            }

        });

        $('#btnReload_addBikeSpecs').on("click", ()=>{
            if(confirm("All data in this textarea will be replaced by data on server?")){
                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = ()=>{
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        var data = httpRequest.responseText.replace(/o_o/g, "%");
                        window.originEditor_bike_specs.setData(data);
                        $('#btnReload_addBikeSpecs').removeClass("busy");
                    }
                };
                httpRequest.open("GET", "/Website/admin/autosave.php?get_edit_bike_specs=true", true);
                httpRequest.send();
                $('#btnReload_addBikeSpecs').addClass("busy");
            }

        });

        $('#btnReload_addBikeGallery').on("click", ()=>{
            if(confirm("All data in this textarea will be replaced by data on server?")){
                var httpRequest = new XMLHttpRequest();
                httpRequest.onreadystatechange = ()=>{
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        var data = httpRequest.responseText.replace(/o_o/g, "%");
                        window.originEditor_bike_gallery.setData(data);
                        $('#btnReload_addBikeGallery').removeClass("busy");
                    }
                };
                httpRequest.open("GET", "/Website/admin/autosave.php?get_edit_bike_gallery=true", true);
                httpRequest.send();
                $('#btnReload_addBikeGallery').addClass("busy");
            }

        });

    });
</script>
</body>
</html>