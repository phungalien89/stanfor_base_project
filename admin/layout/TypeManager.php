<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

    $typeProvider = new TypeProvider();
    $types = $typeProvider->getAllType();
    if(isset($_REQUEST['btn_addNewType'])){
        $_SESSION['type_action'] = "add";
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnEditType'])){
        $_SESSION['type_action'] = "edit";
        $typeId = (int) $_POST['typeId'];
        $_SESSION['typeId'] = $typeId;
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnDeleteType'])){
        $typeId = (int) $_POST['typeId'];
        $t = $typeProvider->getTypeById($typeId);
        $typeProvider->deleteType($typeId);
        $_SESSION['message'][] = ['title'=>'Xóa kiểu xe', 'status'=>'info', 'content'=>'Đã xóa kiểu xe <b>'. $t->typeName .'</b> thành công!'];
        unset($_SESSION['type_action']);
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
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
</style>
<div class="pt-3 container-fluid">
    <h2 class="text-primary text-uppercase">quản lý kiểu xe</h2>

    <div class="d-flex py-3">
        <form action="" method="post">
            <button id="btn_addNewType" name="btn_addNewType" class="btn btn-primary">
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
                <th>Ảnh đại diện</th>
                <th>Kiểu xe</th>
                <th>Ngày tạo</th>
                <th>Ngày sửa</th>
                <th class="text-left">Chỉnh sửa</th>
            </tr>
            </thead>
            <?php
                foreach($types as $id => $type){ ?>
                    <tr style="vertical-align: center">
                        <td><?= $type->typeId ?></td>
                        <td>
                            <div class="hover-scale" style="width: 80px">
                                <img class="img-thumbnail w-100" src="<?= $type->typeImage ?>" alt="">
                            </div>
                        </td>
                        <td><?= $type->typeName ?></td>
                        <td><?= $type->dateCreated ?></td>
                        <td><?= $type->dateModified ?></td>
                        <td>
                            <form action="" method="post">
                                <div class="d-flex justify-content-start">
                                    <button type="submit" name="btnEditType" class="btn btn-link"><span class="fa fa-pencil-alt"></span></button>
                                    <button type="submit" onclick="return confirm('Bạn thực sự muốn xóa type này?');" name="btnDeleteType" class="btn btn-link"><span class="fa fa-trash-alt"></span></button>
                                    <input type="hidden" name="typeId" value="<?= $type->typeId ?>">
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php }
            ?>
        </table>
    </div>
</div>