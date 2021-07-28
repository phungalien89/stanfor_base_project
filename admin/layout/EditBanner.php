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
    <title>Chỉnh sửa banner </title>
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
    #autosave-header{
        background-color: var(--ck-color-toolbar-background);
        border: 1px solid var(--ck-color-toolbar-border);
        border-radius: var(--ck-border-radius);
        padding: var(--ck-spacing-medium);
        border-top: none;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    #autosave-header input{
        width: 100px;
        display: inline-block;
        margin: 0 var(--ck-spacing-medium);
    }
    #autosave-status-label:after{
        content: 'Saved!';
        color: green;
    }
    #autosave-status.busy #autosave-status-label:after{
        content: 'Saving...';
        color: red;
    }
    #autosave-status #autosave-status-icon{
        display: none;
    }
    #autosave-status.busy #autosave-status-icon{
        display: inline-block;
        animation: ani-status 1s linear infinite;
    }
    #btnReload_banner.busy span{
        animation: ani-status 1s linear infinite;
    }
    @keyframes ani-status{
        to{
            transform: rotate(360deg);
        }
    }
</style>
<body>
<?php

    if(isset($_REQUEST['btnBannerReturn'])){
        unset($_SESSION['banner_action']);
        echo "<script>location.assign('". $_SERVER['PHP_SELF'] ."')</script>";
    }


    $bannerMan = new BannerProvider();

    $banner_gallery = "";
    if(isset($_SESSION['banner_action'])){
        if($_SESSION['banner_action'] == "edit"){
            $arrBanner = $bannerMan->getBanner();
            if(count($arrBanner) > 0){
                $banner_gallery = $arrBanner[0]->bannerContent;
            }
        }
    }
    if(isset($_REQUEST['btnUpdateBanner'])){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $banner_gallery = $_POST['txtBannerGallery'];

            $arrBanner = $bannerMan->getBanner();
            $banner = new Banner();
            $banner->bannerContent = $banner_gallery;
            if(count($arrBanner) == 0){
                $bannerMan->addBanner($banner);
                $_SESSION['message'][] = ['title'=>'Banner', 'status'=>'success', 'content'=>'Đã <b>THÊM</b> banner thành công!'];
            }
            else{
                $bannerMan->updateBanner($banner);
                $_SESSION['message'][] = ['title'=>'Banner', 'status'=>'success', 'content'=>'Đã <b>CẬP NHẬT</b> banner thành công!'];
            }
            unset($_SESSION['banner_action']);
            echo "<script>location.assign('". $_SERVER['PHP_SELF'] ."')</script>";
        }
    }

?>
<?php
    include "../inc/message.php";
    include "../inc/scrollToTop.php";
?>
    <div class="container">
        <div class="row pt-4">
            <div class="col-sm-10 col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-header bg-primary text-light text-center">
                        <h3>CHỈNH SỬA BANNER</h3>
                        <div>Nhập thông tin bên dưới để chỉnh sửa</div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="txtBannerGallery">Nhấp chọn nhiều ảnh vào ô bên dưới</label>
                                <textarea class="form-control" name="txtBannerGallery" id="txtBannerGallery" cols="30" rows="10"><?= $banner_gallery ?></textarea>
                                <div id="autosave-header" class="d-flex justify-content-between align-items-center">
                                    <div id="autosave-interval">
                                        <span style="font-size: 1.25em" class="far fa-clock text-primary"></span>
                                        <input min="2000" max="100000" value="5000" step="500" data-toggle="tooltip" title="Saving interval" class="form-control" type="number" name="txtInterval" id="txtInterval">
                                    </div>
                                    <div id="autosave-status">
                                        <span class="font-weight-bold">Status:</span>
                                        <span id="autosave-status-label"></span>
                                        <span id="autosave-status-icon" class="fas fa-sync-alt text-primary"></span>
                                    </div>
                                    <div id="autosave-action">
                                        <button data-trigger="hover" title="Save data" data-toggle="tooltip" type="button" id="btnSave_banner" class="btn btn-outline-primary">
                                            <span class="fas fa-save"></span>
                                        </button>
                                        <button data-trigger="hover" title="Load data from server" data-toggle="tooltip" type="button" id="btnReload_banner" class="btn btn-outline-primary">
                                            <span class="fas fa-history"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group pt-3">
                                <div class="d-flex">
                                    <button id="btnUpdateBanner" name="btnUpdateBanner" class="btn btn-outline-primary">
                                        <span class="fa fa-check"></span>
                                        <span class="text-uppercase">Cập nhật</span>
                                    </button>
                                    <button type="submit" id="btnBannerReturn" name="btnBannerReturn" class="btn btn-outline-danger ml-auto">
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
    $(document).ready(()=>{
        $("[data-toggle = 'tooltip']").tooltip();
        let originEditor = null;
        let timeHandler_banner = null;
        let savingInterval = 10000;
        var httpRequest = new XMLHttpRequest();

        var editor = document.querySelector('#txtBannerGallery');
        ClassicEditor.create(editor, {
            ckFinder : {
                uploadUrl: '../vendor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
            },
            image : {
                resizeUnit: '%',
                resizeOptions : [
                    {
                        name: 'Mặc định',
                        value: null
                    },
                    {
                        name: '25%',
                        value: '25'
                    },
                    {
                        name: '50%',
                        value: '50'
                    },
                    {
                        name: '75%',
                        value: '75'
                    }
                ],
                styles : [
                    'alignLeft',
                    'alignCenter',
                    'alignRight'
                ],
                toolbar : [
                    'imageResize',
                    '|',
                    'imageStyle:alignLeft',
                    'imageStyle:alignCenter',
                    'imageStyle:alignRight',
                ]
            },

            toolbar: ['ckFinder', '|', 'fontFamily', 'fontSize', 'heading', '|', 'alignment', 'indent', 'outdent', 'bold', 'italic', 'link', 'undo', 'redo', 'todolist', 'numberedList', 'bulletedList',
            'blockQuote', 'insertTable', 'mediaEmbed'
            ],
        })
        .then(editor => {
            originEditor = editor;
            setTimer(savingInterval);
        })
        .catch(error => {
            console.log("Error: " + error);
        });

        $('#txtInterval').val(savingInterval);
        let savedData_gallery = "";
        let savedGallery = false;
        function setTimer(interval){
            timeHandler_banner = setInterval(()=>{
                var data = originEditor.getData();
                data = data.replace(/%/g, "o_o").replace(/&nbsp;/g, "");
                if(data == savedData_gallery || data == ""){
                    savedGallery = true;
                }
                else{
                    savedGallery = false;
                }
                httpRequest.onreadystatechange = ()=>{
                    var responseText = httpRequest.responseText;
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                        savedData_gallery = responseText;
                        //console.log("Saved data = " + savedData_gallery);
                        $('#autosave-status').removeClass("busy");
                    }
                };
                if(!savedGallery){
                    httpRequest.open("POST", "autosave.php?edit_banner=" + data, true);
                    httpRequest.send();
                    $('#autosave-status').addClass("busy");
                }
            }, interval);
        }

        $('#txtInterval').on({
            "change" : (e)=>{
                clearInterval(timeHandler_banner);
                setTimer(e.target.value);
            },
            "keydown" : (e)=>{
                if(e.key == "Enter"){
                    originEditor.focus();
                }
            }
        })

        $('#btnReload_banner').on({
           "click" : ()=>{
               var r = confirm("Do you want to load data saved on server?");
               if(r){
                   httpRequest.onreadystatechange = ()=>{
                       if(httpRequest.readyState == 4 && httpRequest.status == 200){
                           var data = httpRequest.responseText;
                           data = data.replace(/o_o/g, "%");
                           setTimeout(()=>{
                               originEditor.setData(data);
                           }, 1000);
                           $('#btnReload_banner').removeClass("busy");
                           $('#btnReload_banner span').removeClass("fa-sync-alt").addClass("fa-history");
                       }
                   };
                   httpRequest.open("GET", "autosave.php?get_edit_banner=true", true);
                   httpRequest.send();
                   $('#btnReload_banner span').removeClass("fa-history").addClass("fa-sync-alt");
                   $('#btnReload_banner').addClass("busy");
               }
           },
        });

        $('#btnSave_banner').on({
            "click" : ()=>{
                var data = originEditor.getData();
                data = data.replace(/%/g, "o_o").replace(/$nbsp;/g, "");
                if(data != ""){
                    httpRequest.onreadystatechange = ()=>{
                        if(httpRequest.readyState == 4 && httpRequest.status == 200){
                            $('#btnSave_banner').tooltip('hide').attr('title', 'Saving completed').tooltip('_fixTitle').tooltip('show');
                            $('#autosave-status').removeClass("busy");
                        }
                    };
                    httpRequest.open("POST", "autosave.php?edit_banner=" + data, true);
                    httpRequest.send();
                    $('#btnSave_banner').tooltip('hide').attr('title', 'Saving...').tooltip('_fixTitle').tooltip('show');
                    $('#autosave-status').addClass("busy");
                }
                else{
                    alert("There is nothing to save!");
                }
            },
            "mouseleave" : ()=>{
                $('#btnSave_banner').tooltip('hide').attr('title', 'Save data').tooltip('_fixTitle');
            }
        })

    });
</script>
</body>
</html>
