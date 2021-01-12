<style>
    .message-notifier{
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        z-index: 2;
        transition: all 0.35s ease;
    }
</style>
<div class="message-notifier">
    <?php
        if(isset($_SESSION['message'])){
            foreach ($_SESSION['message'] as $id => $message){ ?>
                <div class="toast my-2" data-delay="<?= 5000 + $id * 1500 ?>">
                    <div class="toast-header bg-<?= $message['status'] ?> text-light">
                        <span class="font-weight-bold"><?= $message['title'] ?></span>
                        <span style="cursor: pointer" data-dismiss="toast" class="ml-auto">
                            <span class="fas fa-times"></span>
                        </span>
                    </div>
                    <div class="toast-body">
                        <span><?= $message['content'] ?></span>
                    </div>
                </div>
            <?php }
        }
    ?>
</div>

<script>
    $(document).ready(()=>{
        $('.toast').toast('show');
        var toasts = document.getElementsByClassName('message-notifier');
        var originTop = toasts[0].offsetTop;
        window.onscroll = ()=>{
            toasts[0].style.top = originTop + window.scrollY + "px";
        }

        $('.toast').on("hidden.bs.toast", ()=>{
            var httpRequest = new XMLHttpRequest();
            httpRequest.open("POST", "http://localhost:63342/Website/inc/messageManager.php?reset=true", true);
            httpRequest.send();
        });
    });
</script>
