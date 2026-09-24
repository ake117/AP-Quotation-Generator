{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

    <style>
        @media screen and (min-width: 768px) {
            .a-grande { text-align: left; }
        }
    </style>
    <script>

        var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0];

        jQuery(window).on('load resize', function() {
            x = w.innerWidth || e.clientWidth || g.clientWidth,
            y = w.innerHeight|| e.clientHeight|| g.clientHeight;

            $(".grande-bg-img img").css('height',y)
        });

        $banner_duration = 6;
        $grande_banner = Array(
            '{$base_url}/assets/images/grande/bg/b1.jpg',
            '{$base_url}/assets/images/grande/bg/b2.jpg',
            '{$base_url}/assets/images/grande/bg/b3.jpg'
        );

        $(document).ready(function() {

            $banner_total = $grande_banner.length;
            $banner_total_duration = $banner_duration*$banner_total;

            $(".cb-slideshow.runanimated li span").css("animation","imageAnimation "+$banner_total_duration+"s linear infinite 0s");
            $(".cb-slideshow.nav.runanimated li").css("animation","navAnimation "+$banner_total_duration+"s linear infinite 0s");
            $.each($grande_banner, function( index, value ) {
                $banner_idx = index+1;
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css("background-image","url("+value+")");

                $dalay = (index * $banner_duration)+'s';
                $(".cb-slideshow li:nth-child("+$banner_idx+") span").css('animation-delay',$dalay);
                $(".cb-slideshow.nav li:nth-child("+$banner_idx+")").css('animation-delay',$dalay);
            });


            $(".cb-slideshow.nav li").click(function(){

                $idx = $(this).data('rel');
                $(".cb-slideshow-nav").removeClass('runanimated');
                $(".cb-slideshow").removeClass('runanimated');
                for (i = 0; i < $banner_total; i++) {
                    $banner_idx = (($idx+i-1) % $banner_total)+1;
                    $dalay = (i * $banner_duration)+'s';
                    $(".cb-slideshow li:nth-child("+$banner_idx+") span").css('animation-delay',$dalay);
                    $(".cb-slideshow.nav li:nth-child("+$banner_idx+")").css('animation-delay',$dalay);
                }
                setTimeout(function(){
                    $(".cb-slideshow-nav").addClass('runanimated');
                    $(".cb-slideshow").addClass('runanimated');
                }, 100);

            });
        });

    </script>



<!-- div id="grande-bg">
    <div id="grande-bg-container">
        <div class="grande-bg-img">
            <img src="{$base_url}/assets/images/grande/bg/DSC_9063@1X.jpg" />
        </div>
    </div>
</div -->

        <ul class="cb-slideshow runanimated">
            <li><span>Image 01</span></li>
            <li><span>Image 02</span></li>
            <li><span>Image 03</span></li>
        </ul>

        <ul class="cb-slideshow nav runanimated">
            <li data-rel="1"></li>
            <li data-rel="2"></li>
            <li data-rel="3"></li>
        </ul>


<!-- Page Content -->

        <div class="container booking" style="width: 100%;max-width: 800px;">
            <div class="h-100 row align-items-center" style="margin: 0;">
                <div class="book-info signin" style=''>
                    <!-- a href="#" onclick="/"><span class="icon-close"></span></a -->
                    <div class="text">
                        <h1 class="underline">{$lang['signin_title']}</h1>
                        <form class="grande-form">
                            <p style="margin-bottom: 1rem;text-align:center;">{$lang['signin_subtitle']}</p>
                            <div class="rowx"><div class="form-title">{$lang['signin_email']}</div><input type="text" name="email" value="{$p_email}"/></div>
                            <div class="rowx"><div class="form-title">{$lang['signin_password']}</div><input type="password" name="password"/></div>
                            <div class="rowx">
                                <div class="form-title">&nbsp;</div>
                                <button class="btn-grande g-border btn-signin">{$lang['signin_button']}</button>
                            </div>
                            <div class="rowx">
                                <div class="form-title show-desktop">&nbsp;</div>
                                <a class="a-grande" href="{$base_url}/password/reset">{$lang['signin_forgetpassword']}</a>
                            </div>

                        </form>
                    </div>
                    <div class="panel-btnx in">

                    </div>
                </div>

            </div>
        </div>
</div>
<!-- /#page-content-wrapper -->



<script>

    $(document).ready(function() {



    });

    function signin() {
        if (!check_field("input","email",resource.sign_email,['empty','email'])) { return; }
        if (!check_field("input","password",resource.sign_password,['empty'])) { return; }

        $.ajax({
            type: "POST",
            url: "{$base_url}/signin/submit",
            data: $(".grande-form").serialize(),
            dataType: "json",
            success: function(msg){
                if (msg.status === 'success') {
                    window.location = "{$base_url}/projects/";
                } else {
                    popError(msg.message,"Error");
                }
            }
        });
    }

    $(".btn-signin").click(function (event) {
        event.preventDefault();
        signin();
    });

    $('input[name=email] , input[name=password]').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            signin();
        }
    });


</script>
{include file="footer_grande.tpl"}
