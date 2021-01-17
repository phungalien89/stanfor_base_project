<style>
    .floating-box-right{
        position: fixed;
        bottom: 0;
        right: 0.5em;
        padding: 3em 1.5em;
        z-index: 3;
    }
    .floating-box-right .fas{
        position: absolute;
        top: 0;
        left: 0;
        background-color: #009688;
        color: white;
        padding: 1em;
        border-radius: 50%;
        box-shadow: 0 0 10px 3px #aaa;
        transition: all 0.25s ease;
    }
    .floating-box-right .fas:hover{
        box-shadow: 0 0 0 5px #999;
    }
    .floating-box-right .fa-arrow-up{
        z-index: 2;
    }
    #arrow_up.active{
        box-shadow: 0 0 0 5px #999;
        animation: none;
    }
</style>
<div class="floating-box-right">
    <a class="bell-container" href="#">
        <span id="arrow_up" class="fas fa-arrow-up"></span>
    </a>
</div>
<script>
    $(document).ready(()=>{
        $('#arrow_up').on("click", (e)=> {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 600);
        });
    });
</script>