
<div class="headertop_desc">
    <?php
        include "layout/navbar.php";
    ?>
</div>
<div class="header_top">
    <div class="logo">
        <a href="index.html"><img src="images/logo.png" alt="" /></a>
    </div>
    <script type="text/javascript">
        function DropDown(el) {
            this.dd = el;
            this.initEvents();
        }
        DropDown.prototype = {
            initEvents : function() {
                var obj = this;

                obj.dd.on('click', function(event){
                    $(this).toggleClass('active');
                    event.stopPropagation();
                });
            }
        }

        $(function() {

            var dd = new DropDown( $('#dd') );

            $(document).click(function() {
                // all dropdowns
                $('.wrapper-dropdown-2').removeClass('active');
            });

        });

    </script>
    <div class="clear"></div>
</div>
<div class="header_bottom">
    <?php include "menu.php" ?>
    <div class="clear"></div>
</div>