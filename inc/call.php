<style>
    .floating-box{
        position: fixed;
        bottom: 0;
        left: 1em;
        padding: 3em;
        z-index: 3;
    }
    .floating-box .fas{
        position: absolute;
        top: 0;
        left: 0;
    }
    .floating-box .fas{
        background-color: #009688;
        color: white;
        padding: 1em;
        border-radius: 50%;
        box-shadow: 0 0 10px 3px #aaa;
        transition: all 0.25s ease;
    }
    .floating-box .fas:hover{
        box-shadow: 0 0 0 5px #999;
    }
    .floating-box .fa-bell{
        z-index: 2;
        animation: ani-phone-mail 3s linear infinite;
    }
    .floating-box .fa-phone{
        transform: translateY(0%);
        z-index: 1;
    }
    ..floating-box .fa-envelope{
        transform: translateY(0%);
        z-index: 1;
    }
    #bell.active{
        box-shadow: 0 0 0 5px #999;
        animation: none;
    }
    @keyframes ani-phone-mail {
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
<div class="floating-box">
    <a id="bell-container" class="bell-container" href="">
        <span id="bell" class="fas fa-bell"></span>
    </a>
    <a href="tel:0356078993">
        <span id="phone" class="fas fa-phone"></span>
    </a>
    <a href="mailto:phungalien89@gmail.com">
        <span id="mail" class="fas fa-envelope"></span>
    </a>
</div>
<script>
    $(document).ready(()=>{
        $('#bell-container').on({
            'click' : (e)=> {
                e.preventDefault();
                if(!$('#bell').hasClass('active')){
                    $('#phone').css("transform", "translateY(-150%)");
                    $('#mail').css("transform", "translateY(-300%)");
                    $('#bell').addClass('active');
                }
                else{
                    $('#phone').css("transform", "translateY(0%)");
                    $('#mail').css("transform", "translateY(0%)");
                    $('#bell').removeClass('active');
                }
            },
            "blur" : ()=>{
                $('#phone').css("transform", "translateY(0%)");
                $('#mail').css("transform", "translateY(0%)");
                $('#bell').removeClass('active');
            }
        });
    });
</script>