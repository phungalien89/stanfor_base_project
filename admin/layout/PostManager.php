<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

$postProvider = new PostProvider();
$posts = $postProvider->getAllPost();
if(isset($_REQUEST['btn_addNewPost'])){
    $_SESSION['post_action'] = "add";
    echo "<script>location.assign('/Website/admin/AdminPage.php');</script>";
}
if(isset($_REQUEST['btnEditPost'])){
    $_SESSION['post_action'] = "edit";
    $postId = (int) $_POST['postId'];
    $_SESSION['postId'] = $postId;
    echo "<script>location.assign('/Website/admin/AdminPage.php');</script>";
}
if(isset($_REQUEST['btnDeletePost'])){
    $postId = (int) $_POST['postId'];
    $postProvider->deletePost($postId);
    $p = $postProvider->getPostById($postId);

    $prefix = "/Website";
    $filePath = substr($p->postImage, strlen($prefix));
    $filePath = str_replace("/", "\\", $filePath);
    unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the post image
    $_SESSION['message'][] = ['title'=>'Xóa bài đăng', 'status'=>'info', 'content'=>'Đã xóa bài đăng <b>'. $p->postTitle .'</b> thành công!'];
    unset($_SESSION['post_action']);
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
    <h2 class="text-primary text-uppercase">quản lý bài đăng</h2>

    <div class="d-flex py-3">
        <form action="" method="post">
            <input type="hidden" name="add_user" value="add">
            <button id="btn_addNewPost" name="btn_addNewPost" class="btn btn-primary">
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
                <th>Ảnh bài đăng</th>
                <th>Tiêu đề</th>
                <th>Từ khóa</th>
                <th>Nội dung</th>
                <th>Ngày tạo</th>
                <th>Ngày sửa</th>
                <th>Chỉnh sửa</th>
            </tr>
            </thead>
            <?php
            foreach($posts as $id => $post){ ?>
                <tr class="parentRowTable" style="vertical-align: center">
                    <td><?= $post->postId ?></td>
                    <td>
                        <div class="hover-scale" style="width: 80px">
                            <?php
                            if(strlen($post->postImage) > 0){ ?>
                                <img class="img-thumbnail w-100" src="../storage/<?= $post->postImage ?>" alt="">
                            <?php }
                            ?>
                        </div>
                    </td>
                    <td><?= $post->postTitle ?></td>
                    <td><?= $post->postTag ?></td>
                    <td>
                        <div class="overlay">
                            <button data-toggle="modal" data-target="#modal_postContent<?= $id ?>" class="btn btn-primary align-self-center">Xem</button>
                        </div>
                        <div style="overflow: hidden; text-overflow: ellipsis; width: 150px; height: 150px;">
                            <?= $post->postContent ?>
                        </div>
                        <div id="modal_postContent<?= $id ?>" class="modal">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-center">
                                        <h3 class="text-uppercase text-light">Mô tả</h3>
                                    </div>
                                    <div class="modal-body">
                                        <div class="ck-content"><?= $post->postContent ?></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button data-dismiss="modal" class="btn btn-danger ml-auto">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><?= $post->dateCreated ?></td>
                    <td><?= $post->dateModified ?></td>
                    <td>
                        <form action="" method="post">
                            <div class="d-flex justify-content-start">
                                <button type="submit" name="btnEditPost" class="btn btn-link"><span class="fa fa-pencil-alt"></span></button>
                                <button type="submit" onclick="return confirm('Bạn thực sự muốn xóa post này?');" name="btnDeletePost" class="btn btn-link"><span class="fa fa-trash-alt"></span></button>
                                <input type="hidden" name="postId" value="<?= $post->postId ?>">
                            </div>
                        </form>
                    </td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
</div>