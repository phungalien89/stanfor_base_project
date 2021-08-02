<?php
spl_autoload_register(function($class_name){
    include_once "../" . $class_name . ".php";
});

    $bannerMan = new BannerProvider();
    $arrBanner = $bannerMan->getBanner();
    $arrImgSrc = [];
    $arrCaption = [];
    if(count($arrBanner) > 0){
        $domDoc = new DOMDocument();
        @$domDoc->loadHTML($arrBanner[0]->bannerContent);
        $figArr = $domDoc->getElementsByTagName("figure");
        foreach ($figArr as $fig){
            $arrImgSrc[] = $fig->childNodes[0]->getAttribute('src');
            if($fig->childNodes[1]){
                $arrCaption[] = $fig->childNodes[1]->nodeValue;
            }
            else{
                $arrCaption[] = "";
            }
        }
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
    #carousel-banner .carousel-indicators li{
        background-color: #009688;
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
    #carousel-banner .carousel-caption{
        top: 0;
        color: #009688;
    }
    #carousel-banner .carousel-control-prev span, #carousel-banner .carousel-control-next span{
        color: #009688;
        font-size: 2em;
    }
    #banner-editor{
        position: absolute;
        top: 0;
        right: -100px;
    }
    #banner-editor .fas{
        background-color: #009688;
        color: white;
        font-size: 1.25em;
        padding: 15px;
        border-radius: 50%;
        position: absolute;
        top: 0;
        left: 0;
        transition: all 0.5s ease;
    }
    #banner-editor #banner-pencil-main{
        z-index: 2;
        animation: ani-banner-editor 2s ease-in-out infinite 1s;
    }
    #banner-editor #banner-edit{
        z-index: 1;
        transform: translateY(0%);
        opacity: 0;
    }
    #banner-editor #banner-delete{
        z-index: 1;
        background-color: red;
        transform: translateY(0%);
        opacity: 0;

    }
    @keyframes ani-banner-editor {
        2.775%, 13.875%, 24.975%{
            transform: rotate(-20deg);
        }
        0%, 5.55%, 11.1%, 16.65%, 22.2%, 27.75%, 33.3%, 100%{
            transform: rotate(0deg);
        }
        8.325%, 19.425%, 30.535%{
            transform: rotate(20deg);
        }
    }
</style>
<div class="pt-3 container-fluid">
    <h2 class="text-primary text-uppercase">quản lý banner</h2>

    <div class="col-sm-8 col-md-6 mx-auto position-relative">
        <?php
        if(count($arrBanner) > 0){ ?>
            <div class="carousel slide" id="carousel-banner" data-interval="500" data-ride="carousel">
                <ul class="carousel-indicators">
                    <?php
                    foreach($arrImgSrc as $id => $src){ ?>
                        <li class="<?= $id == 0 ? 'active' : '' ?>" data-slide-to="<?= $id ?>" data-target="#carousel-banner"></li>
                    <?php }
                    ?>
                </ul>
                <div class="carousel-inner">
                    <?php
                    foreach($arrImgSrc as $id => $src){ ?>
                        <div class="carousel-item <?= $id == 0 ? 'active' : '' ?>">
                            <img class="w-100" src="<?= $src ?>" alt="<?= $src ?>">
                            <div class="carousel-caption">
                                <h3><?= $arrCaption[$id] ?></h3>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
                <a href="#carousel-banner" data-slide="prev" class="carousel-control-prev">
                    <span class="fas fa-arrow-circle-left"></span>
                </a>
                <a href="#carousel-banner" data-slide="next" class="carousel-control-next">
                    <span class="fas fa-arrow-circle-right"></span>
                </a>
            </div>
        <?php }
        ?>
        <div id="banner-editor">
            <a href="#" data-trigger="hover" id="banner-pencil" data-toggle="tooltip" title="Mở Banner Editor">
                <span id="banner-pencil-main" class="fas fa-pencil-alt"></span>
            </a>
            <a id="banner-edit-container" href="#">
                <span data-toggle="tooltip" title="Chỉnh sửa" id="banner-edit" class="fas fa-pencil-alt"></span>
            </a>
            <a id="banner-delete-container" href="#">
                <span data-toggle="tooltip" title="Xóa" id="banner-delete" class="fas fa-trash-alt"></span>
            </a>
        </div>
    </div>

</div>
<script>
    $(document).ready(()=>{
        var focus = false;
        var showBanner = false;
        $('[data-toggle="tooltip"').tooltip();
        $('#banner-pencil').on({
            "mouseover" : ()=>{
                $('#banner-pencil span').removeClass("fa-pencil-alt");
                $('#banner-pencil span').addClass("fa-plus");
                $('#banner-pencil span').css({
                    'animation' : 'none'
                });
            },
            "mouseleave" : ()=>{
                if(!focus){
                    $('#banner-pencil span').addClass("fa-pencil-alt");
                    $('#banner-pencil span').removeClass("fa-plus");
                }
            },
            "blur" : ()=>{
                /*focus = false;
                showBanner = false;
                hideBannerEditor();
                $('#banner-pencil').tooltip('hide');*/
            },
            "click" : (e)=>{
                focus = true;
                e.preventDefault();
                showBanner = !showBanner;
                showBannerEditor(showBanner);
            },
        });
        $('#banner-edit').on({
           "click" : (e)=>{
               e.preventDefault();
               var httpRequest = new XMLHttpRequest();
               httpRequest.onreadystatechange = ()=>{
                   if(httpRequest.readyState == 4 && httpRequest.status == 200){
                       location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                   }
               };
               httpRequest.open("POST", "BannerAction.php?banner_action=edit", true);
               httpRequest.send();
           },
        });
        $('#banner-delete').on({
            "click" : (e)=>{
                e.preventDefault();
                var r = confirm("Do you want to delete the banner?");
                if(r){
                    var httpRequest = new XMLHttpRequest();
                    httpRequest.onreadystatechange = ()=>{
                        if(httpRequest.readyState == 4 && httpRequest.status == 200){
                            location.assign('<?= $_SERVER['PHP_SELF'] ?>');
                        }
                    };
                    httpRequest.open("POST", "BannerAction.php?banner_action=delete", true);
                    httpRequest.send();
                }
            }
        })

        function hideBannerEditor() {
            $('#banner-pencil span').addClass("fa-pencil-alt");
            $('#banner-pencil span').removeClass("fa-plus");
            $('#banner-pencil span').css({
                'transform' : 'rotate(0deg)',
                'background-color' : '#009688',
                'color' : 'white',
                'animation' : 'ani-banner-editor 2s ease-in-out infinite 1s'
            });
            $('#banner-edit').css({
                'transform' : 'translateY(0%)',
                'opacity' : '0'
            });
            $('#banner-delete').css({
                'transform' : 'translateY(0%)',
                'opacity' : '0'
            });
            $('#banner-pencil').tooltip('hide').attr('title', 'Mở Banner Editor').tooltip('_fixTitle').tooltip('show');
        }

        function showBannerEditor(showBanner) {
            $('#banner-pencil span').removeClass("fa-pencil-alt");
            $('#banner-pencil span').addClass("fa-plus");
            $('#banner-pencil span').css({
                'animation' : 'none'
            });
            if(showBanner){
                $('#banner-pencil span').css({
                    'transform' : 'rotate(45deg)',
                });
                $('#banner-edit').css({
                    'transform' : 'translateY(150%)',
                    'opacity' : '1'
                });
                $('#banner-delete').css({
                    'transform' : 'translateY(300%)',
                    'opacity' : '1'
                });
                $('#banner-pencil').tooltip('hide').attr('title', 'Đóng Banner Editor').tooltip('_fixTitle').tooltip('show');
            }
            else{
                hideBannerEditor();
            }
        }
    });
</script>