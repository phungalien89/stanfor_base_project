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
    <title>Sửa bài đăng </title>
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
    #autosave-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--ck-color-toolbar-background);
        border: 1px solid var(--ck-color-toolbar-border);
        padding: 10px;
        border-radius: var(--ck-border-radius);
        border-top: 0;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    #autosave-status-spinner{
        display: flex;
        align-items: center;
        position: relative;
    }
    #autosave-status-spinner{
        position: relative;
        display: none;
    }

    #autosave-status-label::after {
        content: 'Saved!';
        color: green;
        display: inline-block;
        margin-right: var(--ck-spacing-medium);
    }

    /* During "Saving" display spinner and change content of label. */
    #autosave-status.busy #autosave-status-label::after  {
        content: 'Saving...';
        color: red;
    }

    #autosave-status.busy #autosave-status-spinner{
        display: block;
        animation: autosave-status-spinner 1s linear infinite;
    }

    #autosave-loading-icon:after{
        content: "\f1da";
        font-family: fontAwesome;
        font-size: 1.25em;
    }

    #btnReload_editPostContent.busy #autosave-loading-icon:after{
        content: '\f2f1';
        font-family: fontAwesome;
        display: inline-block;
        animation: autosave-status-spinner 1s linear infinite;
    }

    #autosave-status,
    #autosave-server {
        display: flex;
        align-items: center;
    }

    #snippet-autosave + .ck.ck-editor .ck-editor__editable {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    @keyframes autosave-status-spinner {
        to { transform: rotate( 360deg ); }
    }
</style>
<body>
<div>
    <?php
        // include composer autoload
        include '../vendor/autoload.php';

        // import the Intervention Image Manager Class
        use Intervention\Image\ImageManager;

        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'imagick'));

    ?>
</div>
<?php

    $post_title = $post_content = $post_image = $post_tag = "";
    $mes_post_title = $mes_post_content = $mes_post_image = $mes_post_tag = "";
    $dataOK = true;
    $postProvider = new PostProvider();

    if(isset($_SESSION['postId'])){
        $postId = (int) $_SESSION['postId'];
        $post = $postProvider->getPostById($postId);
        $post_title = $post->postTitle;
        $post_content = $post->postContent;
        $post_tag = $post->postTag;
        $post_image = $post->postImage;
    }

    if(isset($_REQUEST['btnPostReturn'])){
        unset($_SESSION['post_action']);
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php')</script>";
    }

    if(isset($_REQUEST['btnUpdatePost'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            function checkData($data){
                $data = htmlspecialchars($data);
                $data = trim($data);
                $data = stripslashes($data);
                return $data;
            }
            $post_title = $_POST['txtPostTitle'];
            $post_tag = $_POST['txtPostTag'];
            $post_content = $_POST['txtPostContent'];
            $image = $_FILES['txtPostImage'];

            $post_title = checkData($post_title);
            if(strlen($post_title) == 0){
                $mes_post_title = "Tiêu đề bài đăng không được để trống";
                $dataOK = false;
            }
            else{
                if(strlen($post_title) > 100){
                    $mes_post_title = "Tiêu đề bài đăng không dài quá 100 kí tự. Độ dài hiện tại là: " . strlen($post_title);
                    $dataOK = false;
                }
            }

            $post_content = str_replace("'", '"', $post_content);

            $post_tag = checkData($post_tag);
            if(strlen($post_tag) == 0){
                $mes_post_tag = "Từ khóa không thể để trống";
                $dataOK = false;
            }

            $image_type = basename($image['type']);
            if(strlen(basename($image['name'])) > 0){
                if($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "bmp"){
                    $mes_post_image = "Ảnh phải có định dạng jpg, jpeg, png hoặc bmp. Định dạng hiện tại là " . $image_type;
                    $dataOK = false;
                }
            }

            if($dataOK){
                if(strlen(basename($image['name'])) > 0) {
                    $upload_stat = move_uploaded_file($image['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\post\\" . basename($image['name']));
                    // to finally create image instances
                    $img = $manager->make($_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\post\\" . basename($image['name']))->fit(1200);
                    $img->save();

                    $prefix = "http://localhost:63342/Website";
                    $filePath = substr($post_image, strlen($prefix));
                    $filePath = str_replace("/", "\\", $filePath);
                    unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the post image
                    $post_image = $prefix . "/storage/uploads/post/" . basename($image['name']);
                }
                $post = new Post();
                $post->postId = (int) $_SESSION['postId'];
                $post->postTitle = $post_title;
                $post->postTag = $post_tag;
                $post->postContent = $post_content;
                $post->postImage = $post_image;
                $postProvider->updatePost($post);

                $_SESSION['message'][] = ['title'=>'Cập nhật bài đăng', 'status'=>'success', 'content'=>'Đã cập nhật bài đăng <b>'. $post->postTitle .'</b> thành công!'];
                unset($_SESSION['post_action']);
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
                        <h3>CHỈNH SỬA BÀI ĐĂNG</h3>
                        <div>Nhập thông tin bên dưới để thêm mới</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtPostTitle">Tiêu đề bài đăng</label>
                                <input value="<?= $post_title ?>" class="form-control" type="text" name="txtPostTitle" id="txtPostTitle">
                                <?php
                                    if(strlen($mes_post_title) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_post_title ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtPostTag">Từ khóa</label>
                                <input placeholder="pcx, honda, tay ga" value="<?= $post_tag ?>" class="form-control" type="text" name="txtPostTag" id="txtPostTag">
                                <?php
                                if(strlen($mes_post_tag) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_post_tag ?></div>
                                <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtPostContent">Nội dung</label>
                                <div class="">
                                    <textarea class="form-control" name="txtPostContent" id="txtPostContent" cols="30" rows="10" style="resize: vertical"><?= $post_content ?></textarea>
                                    <div id="autosave-header">
                                        <div id="autosave-server">
                                            <span style="font-size: 1.25em;" class="far fa-clock text-primary pr-2"></span>
                                            <input data-toggle="tooltip" title="Saving interval" class="form-control" id="autosave-interval" type="number" value="5000" step="500" min="0" max="100000">
                                            <span class="pl-2">(ms)</span>
                                        </div>

                                        <div>
                                            <div class="font-weight-bold">Status:</div>
                                            <div id="autosave-status">
                                                <span id="autosave-status-label"></span>
                                                <span id="autosave-status-spinner" class="fas fa-sync-alt text-primary"></span>
                                            </div>
                                        </div>
                                        <button type="button" id="btnReload_editPostContent" data-toggle="tooltip" title="Reload data on server" class="btn btn-primary">
                                            <span id="autosave-loading-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_post_content) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_post_content ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group">
                                <label>Ảnh bài đăng</label>
                                <div class="custom-control custom-file">
                                    <input class="custom-file-input" type="file" name="txtPostImage" id="txtPostImage">
                                    <label id="post_file_label" for="txtPostImage" class="custom-file-label">Chọn file để upload</label>
                                </div>
                                <div class="row mx-auto w-50 pt-3">
                                    <img id="post_file_image" class="rounded-circle w-100" src="<?= $post_image ?>" alt="">
                                </div>
                                <?php
                                if(strlen($mes_post_image) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_post_image ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnUpdatePost" name="btnUpdatePost" class="btn btn-outline-primary">
                                        <span class="fa fa-check"></span>
                                        <span class="text-uppercase">Cập nhật</span>
                                    </button>
                                    <button type="submit" id="btnPostReturn" name="btnPostReturn" class="btn btn-outline-danger ml-auto">
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
    let editor = document.querySelector('#txtPostContent');
    var originEditor = null;
    let savingInterval = 10000;
    var intervalId = null;
    let statusIndicator = document.querySelector('#autosave-status');
    let savedData = "";
    var httpRequest = new XMLHttpRequest();
    let saved = true;
    ClassicEditor.create(editor, {
        ckfinder:{
            uploadUrl: 'http://localhost:63342/Website/vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
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
    .then(editor =>{
        window.originEditor = editor;
        saved = true;
        //Send request to server until the content of the written file is equal to the content of editor
        startInterval(savingInterval);
    })
    .catch(error => {console.log("Error: " + error)});

    function startInterval(interval){
        var editor = window.originEditor;
        intervalId = setInterval( () =>{
            if(editor.getData() != savedData && editor.getData() != ""){
                saved = false;
                //console.log("Data = " + editor.getData());
            }
            httpRequest.onreadystatechange = ()=>{
                //console.log(httpRequest.readyState + " | " + httpRequest.status);
                if(httpRequest.readyState == 4 && httpRequest.status == 200){
                    if(httpRequest.responseText == editor.getData().replace("&nbsp;", "").replace(/%/g, "o_o")){
                        saved = true;
                        savedData = editor.getData();
                    }
                    //console.log("Saved data = " + savedData);
                    console.log("Response_edit_post = " + httpRequest.responseText);
                    statusIndicator.classList.remove('busy');
                }
            };
            if(!saved){
                statusIndicator.classList.add('busy');
                httpRequest.open('POST', "http://localhost:63342/Website/admin/autosave.php?edit_post_content=" + editor.getData().replace("&nbsp;", "").replace(/%/g, "o_o"));
                httpRequest.send();
                //console.log("Edited = " + editor.getData().replace("&nbsp;", "").replace(/"/g, "'"));
                //console.log("Edited = " + editor.getData().replace("&nbsp;", ""));
            }
        }, interval);
    }

    $(document).ready(()=>{
        $("[data-toggle = 'tooltip']").tooltip();
        $('#autosave-interval').val(savingInterval);

        $('#autosave-interval').on({
           "change" : (e)=>{
               window.savingInterval = parseInt(e.target.value);
               clearInterval(window.intervalId);
               startInterval(window.savingInterval);
           },
            "keydown" : (e)=>{
               if(e.key == "Enter"){
                   e.preventDefault();
                   window.originEditor.focus();
               }
            },
        });
        $('#txtPostImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#post_file_label').html(name);
                $('#post_file_image').attr("src", path);
                $('#post_file_image').addClass("img-thumbnail");
            },
        });

        $('#btnReload_editPostContent').on({
           "click" : ()=>{
               var r = confirm('Tất cả thông tin bạn đã nhập sẽ được thay thế bằng dữ liệu trên server. Bạn có chắc chắn?');
               if(r){
                   window.originEditor.setData("");
                   httpRequest.onreadystatechange = ()=>{
                       //console.log(httpRequest.readyState + " | " + httpRequest.status);
                       if(httpRequest.readyState == 4 && httpRequest.status == 200){
                           $('#btnReload_editPostContent').removeClass("busy");
                           var data = httpRequest.responseText.replace(/o_o/g, "%");
                           console.log("Server = " + data);
                           setTimeout(()=>{window.originEditor.setData(data);}, 500);
                           $('#btnReload_editPostContent').removeClass("busy");
                       }
                   };
                   httpRequest.open("GET", "http://localhost:63342/Website/admin/autosave.php?get_edit_post_content=true", true);
                   httpRequest.send();
                   $('#btnReload_editPostContent').addClass("busy");
               }
           }
        });

        /*window.addEventListener('beforeunload', (e)=>{
           e.preventDefault();
           e.returnValue = "";
        });*/

    });
</script>
</body>
</html>
