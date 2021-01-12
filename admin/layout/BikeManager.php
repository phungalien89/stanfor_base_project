<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

    $bikeProvider = new BikeProvider();
    if(isset($_REQUEST['btnAddProduct'])){
        $_SESSION['bike_action'] = "add";
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnEditBike'])){
        $_SESSION['bike_action'] = "edit";
        $bikeId = (int) $_POST['bikeId'];
        $_SESSION['bikeId'] = $bikeId;
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnDeleteBike'])){
        $bikeId = (int) $_POST['bikeId'];
        $b = $bikeProvider->getBikeById($bikeId);
        $bikeProvider->deleteBike($bikeId);
        unset($_SESSION['bike_action']);
        $prefix = "http://localhost:63342/Website";
        $filePath = substr($b->bikeImage, strlen($prefix));
        $filePath = str_replace("/", "\\", $filePath);
        unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the bike image
        $_SESSION['message'][] = ['title'=>'Xóa sản phẩm', 'status'=>'info', 'content'=>'Đã xóa <b>'. $b->bikeName .'</b> thành công!'];
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }

    /**
     * get a number of words in a string
     * @param $str: origin string
     * @param $wordCount: numbers of words to get
     * @return $subStr: sub-string
    */
    function getSomeWords($str, $wordCount = 5){
        $splitStr = explode(" ", $str);
        $subString = "";
        foreach($splitStr as $id => $item){
            if($id >= $wordCount){
                break;
            }
            $subString .= $item . " ";
        }
        return $subString;
    }
?>
<head>
    <link rel="stylesheet" href="../css/chkeditor-plugins/ckeditor-style.css">
</head>
<style>
    *{
        box-sizing: border-box;
    }
    .bike-table th, .bike-table td{
        vertical-align: middle !important;
    }
    .parentRowTable > td{
        position: relative;
    }
    .parentRowTable > td .overlay{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        opacity: 0;
        justify-content: center;
        transition: all 0.85s ease;
        background-color: rgba(0, 123, 255, 0.35);
    }
    .parentRowTable > td:hover .overlay{
        opacity: 1;
    }
</style>
<?php
    $bikeProvider = new BikeProvider();
    $bikes = $bikeProvider->getAllBike();
?>
<div class="pt-3 container-fluid">
    <h2 class="text-primary text-uppercase">quản lý sản phẩm</h2>
    <div class="d-flex py-3">
        <form action="" method="post">
            <input type="hidden" name="add_user" value="add">
            <button id="btnAddProduct" name="btnAddProduct" class="btn btn-success">
                <span class="fa fa-plus"></span>
                <span class="pl-2 text-uppercase">Thêm mới</span>
            </button>
        </form>
    </div>
    <table class="bike-table table table-striped table-hover">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Ảnh đại diện</th>
            <th>Tên sản phẩm</th>
            <th>Hãng sản xuất</th>
            <th>Kiểu xe</th>
            <th>Giá xe</th>
            <th>Giá chiết khấu</th>
            <th>Mô tả ngắn</th>
            <th>Quà tặng kèm</th>
            <th>Đặc điểm nổi bật</th>
            <th>Thông số kỹ thuật</th>
            <th style="width: 50px !important;">Thư viện ảnh</th>
            <th>Ngày tạo</th>
            <th>Ngày sửa</th>
            <th>Chỉnh sửa</th>
        </tr>
        </thead>
        <?php
        foreach($bikes as $id => $bike){ ?>
            <tr class="parentRowTable">
                <td><?= $bike->bikeId ?></td>
                <td>
                    <div class="hover-scale" style="width: 80px">
                        <?php
                        if(strlen($bike->bikeImage) > 0){ ?>
                            <img class="img-thumbnail w-100" src="<?= $bike->bikeImage ?>" alt="">
                        <?php }
                        ?>
                    </div>
                </td>
                <td><?= $bike->bikeName ?></td>
                <td><?= $bike->bikeBrand ?></td>
                <td><?= $bike->bikeType ?></td>
                <td><?= $bike->bikePrice ?></td>
                <td><?= $bike->bikeDiscountPrice ?></td>
                <td>
                    <div class="overlay">
                        <button data-toggle="modal" data-target="#modal_bikeShortDesc<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                        <?= $bike->bikeShortDesc ?>
                    </div>
                    <div id="modal_bikeShortDesc<?= $id ?>" class="modal">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-center">
                                    <h3 class="text-uppercase text-light">Mô tả ngắn</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="ck-content"><?= $bike->bikeShortDesc ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="overlay">
                        <button data-toggle="modal" data-target="#modal_bikeGift<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                        <?= $bike->bikeGift ?>
                    </div>
                    <div id="modal_bikeGift<?= $id ?>" class="modal">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-center">
                                    <h3 class="text-uppercase text-light">Quà tặng kèm</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="ck-content"><?= $bike->bikeGift ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="overlay">
                        <button data-toggle="modal" data-target="#modal_bikeHighlight<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                        <?= $bike->bikeHighlight ?>
                    </div>
                    <div id="modal_bikeHighlight<?= $id ?>" class="modal">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-center">
                                    <h3 class="text-uppercase text-light">Đặc điểm nổi bật</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="ck-content"><?= $bike->bikeHighlight ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="overlay">
                        <button data-toggle="modal" data-target="#modal_bikeSpecs<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                        <?= $bike->bikeSpecs ?>
                    </div>
                    <div id="modal_bikeSpecs<?= $id ?>" class="modal">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-center">
                                    <h3 class="text-uppercase text-light">Thông số kỹ thuật</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="ck-content"><?= $bike->bikeSpecs ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="overlay">
                        <button data-toggle="modal" data-target="#modal_bikeGallery<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                        <?= $bike->bikeGallery ?>
                    </div>
                    <div id="modal_bikeGallery<?= $id ?>" class="modal">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-center">
                                    <h3 class="text-uppercase text-light">Thư viện ảnh</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="ck-content"><?= $bike->bikeGallery ?></div>
                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td><?= $bike->dateCreated ?></td>
                <td><?= $bike->dateModified ?></td>
                <td>
                    <form action="" method="post">
                        <div class="d-flex justify-content-start">
                            <button type="submit" name="btnEditBike" class="btn btn-link"><span class="fa fa-pencil-alt"></span></button>
                            <button type="submit" onclick="return confirm('Bạn thực sự muốn xóa Bike này?');" name="btnDeleteBike" class="btn btn-link"><span class="fa fa-trash-alt"></span></button>
                            <input type="hidden" name="bikeId" value="<?= $bike->bikeId ?>">
                        </div>
                    </form>
                </td>
            </tr>
        <?php }
        ?>
    </table>

</div>
