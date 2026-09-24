{include file="header_grande.tpl"}
<div id="page-content-wrapper">

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}

{literal}
    <style>
        .book-info {
            margin: 11rem auto 0rem;
        }
        .book-info.signin .form-title {
            width: 10rem; 
        }
        .book-info.signin .grande-form {
            min-height: 11rem !important;
            width: auto;            
        }
        .book-info.signin .grande-form p{
            text-align: center;
            line-height: 1.5rem;
        }
        .book-info.signin .text {
            margin-bottom: 1rem;
        }
        
        @media screen and (min-width: 768px) {
            .book-info {
                margin: 1rem auto 0rem;
            }
            .book-info.signin .text {
                width: 90%;
            }
            
            .book-info.signin .grande-form >div{
                padding: 0 9em;
            }
            .book-info.signin .form-title {
                width: 4rem;
                float: unset;
            }
            .btn-grande {
                padding: 0 1.5rem;
            }
            .show-desktop {
                display: inline-block;
            }
            
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
        $grande_banner = Array({/literal}
            '{$base_url}/assets/images/grande/bg/b1.jpg',
            '{$base_url}/assets/images/grande/bg/b2.jpg',
            '{$base_url}/assets/images/grande/bg/b3.jpg'
        {literal});
        
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
{/literal}    



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
                    <div id="resetpass" class="text">
                        <h1 class="underline">{$lang['resetpass_title']}</h1>                                            
                        <form class="grande-form">
                            <input type="hidden" name="sign_member_id" value="{$member.member_id}"/>
                            <p>{$lang['resetpass_detail']}</p>
                            <div class="rowx" style="text-align: center;"><div class="form-title">{$lang['resetpass_email']}</div><input type="text" name="sign_email" value="{$member.email}"/></div>                           
                            <div class="rowx" style="text-align: center;">
                                <div class="form-title show-desktop">&nbsp;</div><button class="btn-grande g-border btn-signin">{$lang['resetpass_button']}</button>                                
                            </div>                            
                        </form>
                    </div>
                    <div id="success_resetpass" class="text" style="display:none;">
                        <h1 class="underline">{$lang['chpass_title']}</h1>                                            
                        <form class="grande-form">
                            <p style="margin-bottom: 4rem;text-align:center;">{$lang['resetpass_success']}</p>                            
                            <div class="rowx" style="margin-top: 2rem;">                                
                                <button class="btn-grande g-border btn-home" style="margin: 0 auto;display: block;">{$lang['resetpass_home']}</button>                                
                            </div>                            
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
</div>    
<!-- /#page-content-wrapper -->



<script>
    {literal}
    $(document).ready(function() {



    });
    
    function signin() {
        
        if (!check_field("input","sign_email",resource.sign_email,['empty','email'])) {return;};                
        
        $.ajax({
            type: "POST",
            url: "{/literal}{$base_url}{literal}/password/submitreset",
            data: $(".grande-form").serialize(),
            dataType: "json",
            success: function(msg){
                if (msg.status === 'success') {                    
                    $("#resetpass").css('display','none');
                    $("#success_resetpass").css('display','block');
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
    $(".btn-home").click(function (event) {
        event.preventDefault();
        window.location = "{/literal}{$base_url}{literal}";
    });
        
    
    {/literal}
</script>
{include file="footer_grande.tpl"}
