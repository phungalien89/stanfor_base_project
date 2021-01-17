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
    <title>Sửa thương hiệu </title>
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
        src: url("http://localhost:63342/Website/webfonts/fa-solid-900.woff");
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

    #btnReload_editBrandDesc.busy #autosave-loading-icon:after{
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

    $brand_name = $brand_desc = $brand_image = "";
    $mes_brand_name = $mes_brand_desc = $mes_brand_image = "";
    $dataOK = true;
    $brandProvider = new BrandProvider();
    if(isset($_SESSION['brandId'])){
        $brandId = (int) $_SESSION['brandId'];
        $brand = $brandProvider->getBrandById($brandId);
        $brand_name = $brand->brandName;
        $brand_desc = $brand->brandDesc;
        $brand_image = $brand->brandLogo;
    }

    if(isset($_REQUEST['btnBrandReturn'])){
        unset($_SESSION['brand_action']);
        echo "<script>location.assign('http://localhost:63342/Website/admin/AdminPage.php')</script>";
    }

    if(isset($_REQUEST['btnUpdateBrand'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            function checkData($data){
                $data = htmlspecialchars($data);
                $data = trim($data);
                $data = stripslashes($data);
                return $data;
            }
            $brand_name = $_POST['txtBrandName'];
            $brand_desc = $_POST['txtBrandDesc'];
            $image = $_FILES['txtBrandImage'];

            $brand_name = checkData($brand_name);
            if(strlen($brand_name) == 0){
                $mes_brand_name = "Tên thương hiệu không được để trống";
                $dataOK = false;
            }
            else{
                if(strlen($brand_name) > 50){
                    $mes_brand_name = "Tên thương hiệu không dài quá 50 kí tự";
                    $dataOK = false;
                }
            }

            if(strlen($brand_desc) > 999){
                $mes_brand_desc = "Mô tả không dài quá 999 kí tự. Độ dài hiện tại là" . strlen($brand_desc);
                $dataOk = false;
            }

            $image_type = basename($image['type']);
            if(strlen(basename($image['name'])) > 0){
                if($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "bmp"){
                    $mes_brand_image = "Ảnh phải có định dạng jpg, jpeg, png hoặc bmp";
                    $mes_brand_image .= " Định dạng hiện tại là " . $image_type;
                    $dataOK = false;
                }
            }


            if($dataOK){
                if(strlen(basename($image['name'])) > 0) {
                    $upload_stat = move_uploaded_file($image['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\brand\\" . basename($image['name']));
                    // to finally create image instances
                    /*$img = $manager->make($_SERVER['DOCUMENT_ROOT'] . "\storage\uploads\brand\\" . basename($image['name']))->fit(1200);
                    $img->save();*/

                    $prefix = "http://localhost:63342/Website";
                    $filePath = substr($brand_image, strlen($prefix));
                    $filePath = str_replace("/", "\\", $filePath);
                    unlink($_SERVER['DOCUMENT_ROOT'] . $filePath);//also delete the brand image
                    $brand_image = $prefix . "/storage/uploads/brand/" . basename($image['name']);
                }
                $brand = new Brand();
                $brand->brandId = (int) $_SESSION['brandId'];
                $brand->brandName = $brand_name;
                $brand->brandDesc = $brand_desc;
                $brand->brandLogo = $brand_image;
                $brandProvider->updateBrand($brand);
                $_SESSION['message'][] = ['title'=>'Cập nhật thương hiệu', 'status'=>'success', 'content'=>'Đã cập nhật thương hiệu <b>'. $brand_name .'</b> thành công!'];

                unset($_SESSION['brand_action']);
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
                        <h3>CHỈNH SỬA THƯƠNG HIỆU</h3>
                        <div>Nhập thông tin bên dưới để chỉnh sửa</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtBrandName">Tên thương hiệu</label>
                                <input value="<?= $brand_name ?>" class="form-control" type="text" name="txtBrandName" id="txtBrandName">
                                <?php
                                    if(strlen($mes_brand_name) > 0){ ?>
                                        <div class="invalid-feedback d-block"><?= $mes_brand_name ?></div>
                                    <?php }
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="txtBrandDesc">Mô tả</label>
                                <div>
                                    <textarea class="form-control ckEditor" name="txtBrandDesc" id="txtBrandDesc" cols="30" rows="10" style="resize: vertical"><?= $brand_desc ?></textarea>
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
                                        <button type="button" id="btnReload_editBrandDesc" data-toggle="tooltip" title="Reload data on server" class="btn btn-primary">
                                            <span id="autosave-loading-icon"></span>
                                        </button>
                                    </div>
                                </div>
                                <?php
                                if(strlen($mes_brand_desc) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_brand_desc ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group">
                                <label>Ảnh logo</label>
                                <div class="custom-control custom-file">
                                    <input class="custom-file-input" type="file" name="txtBrandImage" id="txtBrandImage">
                                    <label id="brand_file_label" for="txtBrandImage" class="custom-file-label">Chọn file để upload</label>
                                </div>
                                <div class="row mx-auto w-50 pt-3">
                                    <img id="brand_file_image" class="rounded-circle w-100" src="<?= $brand_image ?>" alt="">
                                </div>
                                <?php
                                if(strlen($mes_brand_image) > 0){ ?>
                                    <div class="invalid-feedback d-block"><?= $mes_brand_image ?></div>
                                <?php }
                                ?>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnUpdateBrand" name="btnUpdateBrand" class="btn btn-outline-primary">
                                        <span class="fa fa-check"></span>
                                        <span class="text-uppercase">Cập nhật</span>
                                    </button>
                                    <button type="submit" id="btnBrandReturn" name="btnBrandReturn" class="btn btn-outline-danger ml-auto">
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
    let editor = document.querySelector('#txtBrandDesc');
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
                    console.log("Response_edit_brand = " + httpRequest.responseText);
                    statusIndicator.classList.remove('busy');
                }
            };
            if(!saved){
                statusIndicator.classList.add('busy');
                httpRequest.open('POST', "http://localhost:63342/Website/admin/autosave.php?edit_brand_desc=" + editor.getData().replace("&nbsp;", "").replace(/%/g, "o_o"));
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

        $('#txtBrandImage').on({
            'change' : (e)=>{
                var path = URL.createObjectURL(e.target.files[0]);
                var name = e.target.files[0].name;
                $('#brand_file_label').html(name);
                $('#brand_file_image').attr("src", path);
                $('#brand_file_image').addClass("img-thumbnail");
            },
        });

        $('#btnReload_editBrandDesc').on({
            "click" : ()=>{
                var r = confirm('Tất cả thông tin bạn đã nhập sẽ được thay thế bằng dữ liệu trên server?');
                if(r){
                    window.originEditor.setData("");
                    httpRequest.onreadystatechange = ()=>{
                        //console.log(httpRequest.readyState + " | " + httpRequest.status);
                        if(httpRequest.readyState == 4 && httpRequest.status == 200){
                            $('#btnReload_editBrandDesc').removeClass("busy");
                            var data = httpRequest.responseText.replace(/o_o/g, "%");
                            console.log("Server = " + data);
                            setTimeout(()=>{window.originEditor.setData(data);}, 500);
                            $('#btnReload_editBrandDesc').removeClass("busy");
                        }
                    };
                    httpRequest.open("GET", "http://localhost:63342/Website/admin/autosave.php?get_edit_brand_desc=true", true);
                    httpRequest.send();
                    $('#btnReload_editBrandDesc').addClass("busy");
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
