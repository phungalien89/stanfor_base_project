<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

    $userProvider = new UserProvider();
    $users = $userProvider->getAllUser();
    if(isset($_REQUEST['btnAddUser'])){
        $_SESSION['user_action'] = "add";
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnEditUser'])){
        $_SESSION['user_action'] = "edit";
        $userId = (int) $_POST['userId'];
        $_SESSION['userId'] = $userId;
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php');</script>";
    }
    if(isset($_REQUEST['btnDeleteUser'])){
        $userId = (int) $_POST['userId'];
        $usr = $userProvider->getUserById($userId);//For show the deleted name
        $userProvider->deleteUser($userId);
        $filePath = "/storage/" . $usr->userImage;
        $filePath = str_replace("/", "\\", $filePath);
        unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the user image
        unset($_SESSION['user_action']);
        $_SESSION['message'][] = ['title'=>'Xóa người dùng', 'status'=>'info', 'content'=>'Đã xóa <b>'. $usr->userDisplayName .'</b> thành công!'];
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
    <h2 class="text-primary text-uppercase">quản lý người dùng</h2>

    <div class="d-flex py-3">
        <form action="" method="post">
            <input type="hidden" name="add_user" value="add">
            <button id="btnAddUser" name="btnAddUser" class="btn btn-primary">
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
                <th>Email</th>
                <th>Mật khẩu</th>
                <th>Tên hiển thị</th>
                <th>Ngày tạo</th>
                <th>Ngày sửa</th>
                <th>Chỉnh sửa</th>
            </tr>
            </thead>
            <?php
                foreach($users as $id => $user){ ?>
                    <tr style="vertical-align: center">
                        <td><?= $user->userId ?></td>
                        <td>
                            <div class="hover-scale" style="width: 80px">
                                <?php
                                    if(strlen($user->userImage) > 0){ ?>
                                        <img class="img-thumbnail w-100" src="../storage/<?= $user->userImage ?>" alt="">
                                    <?php }
                                ?>
                            </div>
                        </td>
                        <td><?= $user->userEmail ?></td>
                        <td><?= $user->userPassword ?></td>
                        <td><?= $user->userDisplayName ?></td>
                        <td><?= $user->dateCreated ?></td>
                        <td><?= $user->dateModified ?></td>
                        <td>
                            <form action="" method="post">
                                <div class="d-flex justify-content-start">
                                    <button type="submit" name="btnEditUser" class="btn btn-link"><span class="fa fa-pencil-alt"></span></button>
                                    <button type="submit" onclick="return confirm('Bạn thực sự muốn xóa người dùng này?');" name="btnDeleteUser" class="btn btn-link"><span class="fa fa-trash-alt"></span></button>
                                    <input type="hidden" name="userId" value="<?= $user->userId ?>">
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php }
            ?>
        </table>
    </div>
</div>