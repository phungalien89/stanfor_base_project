<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

    $brandProvider = new BrandProvider();
    $brands = $brandProvider->getAllBrand();
    if(isset($_REQUEST['btn_addNewBrand'])){
        $_SESSION['brand_action'] = "add";
        echo "<script>location.assign('/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnEditBrand'])){
        $_SESSION['brand_action'] = "edit";
        $brandId = (int) $_POST['brandId'];
        $_SESSION['brandId'] = $brandId;
        echo "<script>location.assign('/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnDeleteBrand'])){
        $brandId = (int) $_POST['brandId'];
        $br = $brandProvider->getBrandById($brandId);
        $brandProvider->deleteBrand($brandId);
        unset($_SESSION['brand_action']);

        $prefix = "/Website";
        $filePath = substr($br->brandLogo, strlen($prefix));
        $filePath = str_replace("/", "\\", $filePath);
        unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the brand image
        $_SESSION['message'][] = ['title'=>'Xóa thương hiệu', 'status'=>'info', 'content'=>'Đã xóa thương hiệu <b>'. $br->brandName .'</b> thành công!'];
        echo "<script>location.assign('/Website/admin/AdminPage.php');</script>";
    }

?>
<style>
    .hover-scale{
        transition: all 0.15s ease;
    }
    .hover-scale:hover{
        transform: scale(2);
    }
    .user-table th, .user-table td{
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
<div class="pt-3 container-fluid">
    <h2 class="text-primary text-uppercase">quản lý thương hiệu</h2>

    <div class="d-flex py-3">
        <form action="" method="post">
            <input type="hidden" name="add_user" value="add">
            <button id="btn_addNewBrand" name="btn_addNewBrand" class="btn btn-primary">
                <span class="fa fa-plus"></span>
                <span class="pl-2 text-uppercase">Thêm mới</span>
            </button>
        </form>
    </div>
    <div style="overflow-x: auto">
        <table class="user-table table table-hover table-striped">
            <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Logo</th>
                <th>Tên</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Ngày sửa</th>
                <th>Chỉnh sửa</th>
            </tr>
            </thead>
            <?php
                foreach($brands as $id => $brand){ ?>
                    <tr class="parentRowTable" style="vertical-align: center">
                        <td><?= $brand->brandId ?></td>
                        <td>
                            <div class="hover-scale" style="width: 80px">
                                <?php
                                    if(strlen($brand->brandLogo) > 0){ ?>
                                        <img class="img-thumbnail w-100" src="../storage/<?= $brand->brandLogo ?>" alt="../storage/<?= $brand->brandLogo ?>">
                                    <?php }
                                ?>
                            </div>
                        </td>
                        <td><?= $brand->brandName ?></td>
                        <td>
                            <div class="overlay">
                                <button data-toggle="modal" data-target="#modal_brandDesc<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                            </div>
                            <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                                <?= $brand->brandDesc ?>
                            </div>
                            <div id="modal_brandDesc<?= $id ?>" class="modal">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-center">
                                            <h3 class="text-uppercase text-light">Mô tả</h3>
                                        </div>
                                        <div class="modal-body">
                                            <div class="ck-content"><?= $brand->brandDesc ?></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><?= $brand->dateCreated ?></td>
                        <td><?= $brand->dateModified ?></td>
                        <td>
                            <form action="" method="post">
                                <div class="d-flex justify-content-start">
                                    <button type="submit" name="btnEditBrand" class="btn btn-link"><span class="fa fa-pencil-alt"></span></button>
                                    <button type="submit" onclick="return confirm('Bạn thực sự muốn xóa brand này?');" name="btnDeleteBrand" class="btn btn-link"><span class="fa fa-trash-alt"></span></button>
                                    <input type="hidden" name="brandId" value="<?= $brand->brandId ?>">
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php }
            ?>
        </table>
    </div>
</div>