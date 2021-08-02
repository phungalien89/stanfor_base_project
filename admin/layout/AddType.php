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
    <title>Thêm kiểu xe </title>
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
        src: url("../../webfonts/fa-solid-900.woff");
    }
    .form-group label:first-child{
        font-weight: bold;
    }
    .invalid-feedback:before{
        content: "\f071";
        font-family: fontAwesome;
        padding-right: 5px;
    }
</style>
<body>
<?php

    $type_name = "";
    $type_image = "";
    $mes_type_name = $mes_type_image = "";
    $dataOK = true;
    $typeProvider = new TypeProvider();
    if(isset($_REQUEST['btnTypeReturn'])){
        unset($_SESSION['type_action']);
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php')</script>";
    }

    if(isset($_REQUEST['btnAddType'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            function checkData($data){
                $data = htmlspecialchars($data);
                $data = trim($data);
                $data = stripslashes($data);
                return $data;
            }
            $type_name = $_POST['txtTypeName'];
            $image = $_FILES['txtTypeImage'];
            $dir = "uploads/type/";

            $type_name = checkData($type_name);
            if(strlen($type_name) == 0){
                $mes_type_name = "Kiểu xe không được để trống";
                $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'danger', 'content'=>"$mes_type_name"];
                $dataOK = false;
            }
            else {
                if (strlen($type_name) > 20) {
                    $mes_type_name = "Kiểu xe không dài quá 20 kí tự";
                    $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'danger', 'content'=>"$mes_type_name"];
                    $dataOK = false;
                }
            }

            $image_type = basename($image['type']);
            if(strlen(basename($image['name'])) == 0){
                $mes_type_image = "Ảnh đại diện không được để trống";
                $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'danger', 'content'=>"$mes_type_image"];
                $dataOK = false;
            }
            else{
                if($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "bmp"){
                    $mes_type_image = "Ảnh phải có định dạng jpg, jpeg, png hoặc bmp";
                    $mes_type_image .= " Định dạng hiện tại là " . $image_type;
                    $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'danger', 'content'=>"$mes_type_image"];
                    $dataOK = false;
                }
                else{
                    if(file_exists("../storage/" . $dir . basename($image['name']))){
                        $mes_type_image = "Ảnh đại diện đã tồn tại. Hãy chọn ảnh có tên khác";
                        $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'danger', 'content'=>"$mes_type_image"];
                        $dataOK = false;
                    }
                }
            }

            if($dataOK){
                if(strlen(basename($image['name'])) > 0) {
                    $upload_stat = move_uploaded_file($image['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\\type\\" . basename($image['name']));
                    $type_image = $dir . basename($image['name']);
                }

                $type = new Type();
                $type->typeName = $type_name;
                $type->typeImage = $type_image;
                $typeProvider->addType($type);

                $_SESSION['message'][] = ['title'=>'Thêm mới kiểu xe', 'status'=>'success', 'content'=>'Đã thêm kiểu xe <b>'. $type_name .'</b> thành công!'];
                unset($_SESSION['type_action']);
                echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php')</script>";
            }

        }
    }

?>
    <div class="container">
        <div class="row pt-4">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light text-center">
                        <h3>THÊM MỚI KIỂU XE</h3>
                        <div>Nhập thông tin bên dưới để thêm mới</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtTypeName">Kiểu xe</label>
                                <input value="<?= $type_name ?>" class="form-control" type="text" name="txtTypeName" id="txtTypeName">
                                <?php
                                    if(strlen($mes_type_name) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_type_name ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>Ảnh đại diện</label>
                                <div class="custom-control custom-file">
                                    <input class="custom-file-input" type="file" name="txtTypeImage" id="txtTypeImage">
                                    <label id="type_file_label" for="txtTypeImage" class="custom-file-label">Chọn file để upload</label>
                                </div>
                                <div class="row mx-auto w-50 pt-3">
                                    <img id="type_file_image" class="rounded-circle w-100" src="" alt="">
                                </div>
                                <?php
                                if(strlen($mes_type_image) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_type_image ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnAddType" name="btnAddType" class="btn btn-outline-primary">
                                        <span class="fa fa-check"></span>
                                        <span class="text-uppercase">Thêm mới</span>
                                    </button>
                                    <button type="submit" id="btnTypeReturn" name="btnTypeReturn" class="btn btn-outline-danger ml-auto">
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
    var allEditors = document.querySelectorAll('.ckEditor');
    for (let editor of allEditors){
        ClassicEditor.create(editor, {
            ckfinder:{
                uploadUrl: 'http://localhost:63342/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
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
            toolbar:['ckfinder', '|', 'heading', '|', 'alignment', "|", 'indent', 'outdent', '|', 'bold', 'italic', 'link', '|', 'undo', 'redo', 'todoList', 'numberedList', 'bulletedList',
                    '|', 'blockQuote', 'insertTable', 'mediaEmbed',
            ],

        });
    }
    $(document).ready(()=>{
        $("[data-toggle = 'tooltip']").tooltip();

        $('#txtTypeImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#type_file_label').html(name);
                $('#type_file_image').attr("src", path);
                $('#type_file_image').addClass("img-thumbnail");
            },
        });

        $('#txtTypeImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#type_file_label').html(name);
                $('#type_file_image').attr("src", path);
                $('#type_file_image').addClass("img-thumbnail");
            },
        });


    });
</script>
</body>
</html>
